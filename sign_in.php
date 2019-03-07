<?php

require_once('functions.php');
require_once('init.php');

$submenu = '';
$errors = [];
$data_form = [];

if ($dbHelper->getError()) {
    print $dbHelper->getError();
    exit();
} else {
    $dbHelper->executeQuery('SELECT id, name AS name FROM category');

    if (!$dbHelper->getError()) {
        $submenu = $dbHelper->getResultArray();
    } else {
        print $dbHelper->getError();
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $sign = $_POST;

        $email = $dbHelper->getEscapeStr($sign['email']);
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $dbHelper->executeQuery($sql);
        $user = $dbHelper->getResultArray()[0] ?? null;

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
        } else {
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