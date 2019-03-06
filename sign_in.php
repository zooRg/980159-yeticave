<?php

require_once('functions.php');
require_once('init.php');

$submenu = '';
$errors = [];
$data_form = [];

if (!$conn) {
    $error = mysqli_connect_error();
    print ($error);
    exit();
} else {
    $sqlCat = 'SELECT id, name AS name FROM category';

    $resultCat = mysqli_query($conn, $sqlCat);

    if ($resultCat) {
        $submenu = mysqli_fetch_all($resultCat, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($conn);
        print ($error);
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $sign = $_POST;

        $email = mysqli_real_escape_string($conn, $sign['email']);
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $res = mysqli_query($conn, $sql);

        $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;
        $email_formated = filter_var($sign['email'], FILTER_VALIDATE_EMAIL);

        if (!$user) {
            $errors['email'] = 'Email не найден';
        }
        if ($email_formated === false) {
            $errors['email'] = 'Email неправильного формата';
        }

        if (password_verify($sign['password'], $user['password'])) {
            $_SESSION['user'] = $user;
            header("Location: /index.php");
            exit();
        }
        else {
            $errors['password'] = 'Неверный пароль';
        }

        $data_form['sign'] = $sign;
        $data_form['errors'] = $errors;
    }
}

$contents = include_template('sign_in.php', $data_form);

$html = include_template('layout.php', [
    'is_auth'    => $is_auth,
    'user_name'  => $user_name,
    'title'      => 'YetiCave - вход на сайт',
    'submenu'    => $submenu,
    'index_page' => 'non',
    'contents'   => $contents
]);

print($html);
?>