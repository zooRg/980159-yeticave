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

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $sign = $_POST;
        $email = filter_var($sign['email'], FILTER_VALIDATE_EMAIL);

        if ($email) {
            $errors = [];

            $required = ['email', 'password'];

            foreach ($required as $key) {
                if (empty($sign[$key])) {
                    $errors[$key] = 'Поле обязательно для заполнения';
                }
            }

            $email = mysqli_real_escape_string($conn, $sign['email']);
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $res = mysqli_query($conn, $sql);

            $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

            if (!count($errors) && $user) {
                if (password_verify($sign['password'], $user['password'])) {
                    $_SESSION['user'] = $user;
                    header("Location: /index.php");
                    exit();
                } else {
                    $errors['password'] = 'Неверный пароль';
                }
            } else {
                $errors['email'] = 'Email не найден';
            }

            if (count($errors)) {
                $data_form['sign'] = $sign;
                $data_form['errors'] = $errors;
            }
        }
        else {
            $data_form['sign'] = $sign;
            $data_form['errors']['email'] = 'Email неправильного формата';
        }
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