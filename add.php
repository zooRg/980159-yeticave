<?php

require_once('functions.php');
$db = require_once('init.php');

$conn = mysqli_connect($db['SERVER'], $db['DBUSERNAME'], $db['PASS'], $db['DBNAME']);
mysqli_set_charset($conn, "utf8");

$submenu = '';
$data_html = [];
$errors = [];
$errors['category'] = 'Выберите категорию';
$errors['name'] = 'Введите наименование лота';
$errors['message'] = 'Напишите описание лота';
$errors['path'] = 'Добавьте фотографию';
$errors['startPrice'] = 'Введите начальную цену';
$errors['step'] = 'Введите шаг ставки';
$errors['dateEnd'] = 'Введите дату завершения торгов';

if ($is_auth) {
    if (!$conn) {
        $error = mysqli_connect_error();
        print ($error);
        die();
    } else {
        $sqlCat = 'SELECT id, name AS name FROM category';

        $resultCat = mysqli_query($conn, $sqlCat);

        if ($resultCat) {
            $submenu = mysqli_fetch_all($resultCat, MYSQLI_ASSOC);
        } else {
            $error = mysqli_error($conn);
            print ($error);
            die();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $photo = $_POST['lot'];

            if (!empty($_FILES['lot']['name']['photo'])) {
                $photoExt = explode('.', htmlspecialchars($_FILES['lot']['name']['photo']))[1];
                $filename = uniqid() . '.' . $photoExt;
                $photo['path'] = 'img/' . $filename;
            }

            $sql = 'INSERT INTO lot (data_add, category_id, autor_id, name, description, img, start_price, step, date_end) VALUES (NOW(), ?, 1, ?, ?, ?, ?, ?, ?)';

            $date_end = date('Y-m-d H:i:s', strtotime($conn->real_escape_string($photo['dateEnd'])));
            $date_now = date('Y-m-d H:i:s');

            echo $date_now;
            if($date_end > $date_now) {
                $stmt = db_get_prepare_stmt(
                    $conn,
                    $sql,
                    [
                        $conn->real_escape_string($photo['category']),
                        $conn->real_escape_string($photo['name']),
                        $conn->real_escape_string($photo['message']),
                        $conn->real_escape_string($photo['path']),
                        $conn->real_escape_string($photo['startPrice']),
                        $conn->real_escape_string($photo['step']),
                        $date_end
                    ]
                );
                $res = mysqli_stmt_execute($stmt);

                if ($res) {
                    $result = move_uploaded_file($_FILES['lot']['tmp_name']['photo'], 'img/' . $filename);
                    $photo_id = mysqli_insert_id($conn);
                    header("Location: /lot.php?lot_id=" . $photo_id);
                    exit();
                }
                else{
                    print $stmt->error;
                }
            }
            else {
                $errors['dateEnd'] = 'Дата должна быть больше текущей и меньше 2 месяцев';
            }
            foreach ($photo as $key => $err) {
                if (!$err && array_key_exists($key, $errors)) {
                    $data_html['errors'][$key] = $errors[$key];
                }
            }
            $data_html['lot'] = $photo;
        }

        $contents = include_template('add-lot.php', [
            'submenu' => $submenu,
            'errors' => $data_html['errors'],
            'values' => $data_html['lot']
        ]);

        $html = include_template('layout.php', [
            'is_auth'    => $is_auth,
            'user_name'  => $user_name,
            'title'      => 'YetiCave - Добавление лота',
            'submenu'    => $submenu,
            'index_page' => 'non',
            'contents'   => $contents
        ]);
    }
} else {
    http_response_code(403);
}

print($html);
?>