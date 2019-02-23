<?php

require_once('functions.php');
$db = require_once('init.php');

$conn = mysqli_connect($db['SERVER'], $db['DBUSERNAME'], $db['PASS'], $db['DBNAME']);
mysqli_set_charset($conn, "utf8");

$submenu = '';

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

    $html = include_template('add-lot.php', [
        'is_auth'       => $is_auth,
        'user_name'     => $user_name,
        'submenu'       => $submenu
    ]);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $photo = $_POST;
        $photoExt = explode('.', $_FILES['lot']['name']['photo'])[1];

        $filename = uniqid() . '.' . $photoExt;
        $photo['lot']['path'] = 'img/' . $filename;
        $result = move_uploaded_file($_FILES['lot']['tmp_name']['photo'], 'img/' . $filename);

        $sql = 'INSERT INTO lot (data_add, category_id, autor_id, name, description, img, start_price, step, date_end) VALUES (NOW(), ?, 1, ?, ?, ?, ?, ?, ?)';

        $date_end = date('Y-m-d H:i:s', strtotime($photo['lot']['dateEnd']));
        $stmt = db_get_prepare_stmt(
            $conn,
            $sql,
            [
                $photo['lot']['category'],
                $photo['lot']['name'],
                $photo['lot']['message'],
                $photo['lot']['path'],
                $photo['lot']['startPrice'],
                $photo['lot']['step'],
                $date_end
            ]
        );
        $res = mysqli_stmt_execute($stmt);


        if ($res) {
            $photo_id = mysqli_insert_id($conn);

            header("Location: lot.php?lot_id=" . $photo_id);
        }
        else {
            print ($stmt->error);
        }

    }
}

print($html);
?>