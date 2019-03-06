<?php

require_once('functions.php');
require_once('init.php');

$submenu = '';
$contents = '';
$data_html = [];
$errors = [];
$errors['category'] = 'Выберите категорию';
$errors['name'] = 'Введите наименование лота';
$errors['message'] = 'Напишите описание лота';
$errors['path'] = 'Добавьте фотографию';
$errors['startPrice'] = 'Введите начальную цену';
$errors['step'] = 'Введите шаг ставки';
$errors['dateEnd'] = 'Введите дату завершения торгов';

if (!$is_auth) {
    http_response_code(403);
    exit();
}

if (!$conn) {
    $error = mysqli_connect_error();
    print ($error);
    exit();
}
else {
    $sqlCat = 'SELECT id, name AS name FROM category';

    $resultCat = mysqli_query($conn, $sqlCat);

    if ($resultCat) {
        $submenu = mysqli_fetch_all($resultCat, MYSQLI_ASSOC);
    }
    else {
        $error = mysqli_error($conn);
        print ($error);
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $add_lot = $_POST['lot'];

        if (!empty($_FILES['lot']['name']['photo'])) {
            $photoExt = explode('.', htmlspecialchars($_FILES['lot']['name']['photo']))[1];
            $filename = uniqid() . '.' . $photoExt;
            $add_lot['path'] = 'img/' . $filename;
        }

        $sql = 'INSERT INTO lot'
            . ' (dt_add, category_id, autor_id, name, description, img, start_price, step, dt_end)'
            . ' VALUES'
            . ' (NOW(), ?, 1, ?, ?, ?, ?, ?, ?)';

        $date_end = date('Y-m-d H:i:s', strtotime($conn->real_escape_string($add_lot['dateEnd'])));
        $date_now = date('Y-m-d H:i:s');

        if($date_end < $date_now) {
            $data_html['errors']['dateEnd'] = 'Дата должна быть больше текущей';
        }

        $stmt = db_get_prepare_stmt(
            $conn,
            $sql,
            [
                $conn->real_escape_string($add_lot['category']),
                $conn->real_escape_string($add_lot['name']),
                $conn->real_escape_string($add_lot['message']),
                $conn->real_escape_string($add_lot['path']),
                $conn->real_escape_string($add_lot['startPrice']),
                $conn->real_escape_string($add_lot['step']),
                $date_end
            ]
        );
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $result = move_uploaded_file($_FILES['lot']['tmp_name']['photo'], 'img/' . $filename);
            $lot_id = mysqli_insert_id($conn);
            header("Location: /lot.php?lot_id=" . $lot_id);
            exit();
        }

        foreach ($errors as $key => $err) {
            if (!array_key_exists($key, $add_lot)) {
                $data_html['errors'][$key] = $errors[$key];
            }
        }

        if (stripos(strval(mysqli_error($conn)), 'name')) {
            $data_html['errors']['name'] = 'Лот с таким наименованием уже существует';
        }

        $add_lot['category'] === $errors['category'] ? $data_html['errors']['category'] = $errors['category'] : null;

        $data_html['lot'] = $add_lot;
    }

    $contents = include_template('add-lot.php', [
        'submenu' => $submenu,
        'errors'  => $data_html['errors'],
        'values'  => $data_html['lot']
    ]);
}

$html = include_template('layout.php', [
    'is_auth'    => $is_auth,
    'user_name'  => $user_name,
    'title'      => 'YetiCave - Добавление лота',
    'submenu'    => $submenu,
    'index_page' => 'non',
    'contents'   => $contents
]);

print($html);
?>