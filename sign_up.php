<?php

require_once('functions.php');
$db = require_once('init.php');

$conn = mysqli_connect($db['SERVER'], $db['DBUSERNAME'], $db['PASS'], $db['DBNAME']);
mysqli_set_charset($conn, "utf8");

$submenu = '';
$data_form = [];

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

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !count($errors)) {

        $sign = $_POST['registr'];
        $errors = [];

        $required = ['email', 'password', 'name', 'message'];

        foreach ($required as $key) {
            if (empty($sign[$key])) {
                $errors[$key] = 'Поле обязательно для заполнения';
            }
        }

        if(empty($errors)) {
            $email = mysqli_real_escape_string($conn, $sign['email']);
            $sql = "SELECT id FROM users WHERE email = '$email'";
            $res = mysqli_query($conn, $sql);

            if (mysqli_num_rows($res) > 0) {
                $errors['users'] = 'Пользователь с этим email уже зарегистрирован';
            }
            else {
                $sign['path'] = '';
                $password = password_hash($sign['password'], PASSWORD_DEFAULT);

                if(!empty($_FILES['registr']['name']['photo'])) {
                    $photoExt = explode('.', $_FILES['registr']['name']['photo'])[1];

                    $filename = uniqid() . '.' . $photoExt;
                    $sign['path'] = 'img/' . $filename;
                    $result = move_uploaded_file($_FILES['registr']['tmp_name']['photo'], 'img/' . $filename);
                }

                $sql = 'INSERT INTO users (data_registr, email, name, password, avatar) VALUES (NOW(), ?, ?, ?, ?)';
                $stmt = db_get_prepare_stmt($conn, $sql, [$sign['email'], $sign['name'], $password, $sign['path']]);
                $res = mysqli_stmt_execute($stmt);

                if ($res) {
                    header("Location: /sign_in.php");
                    exit();
                }
            }
        }
        var_dump($sign['path']);
        $data_form['values'] = $sign;
        $data_form['errors'] = $errors;
    }
}

$contents = include_template('registr.php', $data_form);

$html = include_template('layout.php', [
    'is_auth'    => $is_auth,
    'user_name'  => $user_name,
    'title'      => 'YetiCave - Регистрация',
    'submenu'    => $submenu,
    'index_page' => 'non',
    'contents'   => $contents
]);

print($html);
?>