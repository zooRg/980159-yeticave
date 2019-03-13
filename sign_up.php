<?php
require_once 'init.php';

$submenu = '';
$resID = null;
$data_form = [];
$errors = [];

if ($dbHelper->getError()) {
    print $dbHelper->getError();
    exit();
}

$dbHelper->executeQuery('SELECT id, name AS name FROM category');

if (!$dbHelper->getError()) {
    $submenu = $dbHelper->getResultArray();
} else {
    print $dbHelper->getError();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $sign['name'] = htmlspecialchars($_POST['registr']['name']);
    $sign['email'] = htmlspecialchars($_POST['registr']['email']);
    $sign['password'] = htmlspecialchars($_POST['registr']['password']);
    $sign['message'] = htmlspecialchars($_POST['registr']['message']);
    $email_formated = filter_var($sign['email'], FILTER_VALIDATE_EMAIL);

    $email = $dbHelper->getEscapeStr($sign['email']);

    $sql = "SELECT id FROM users WHERE email = '$email'";
    $dbHelper->executeQuery($sql);
    $res = $dbHelper->getResultArray();

    if ($email_formated === false) {
        $errors['users'] = 'Email неправильного формата';
    }

    if (0 < $dbHelper->getNumRows()) {
        $errors['users'] = 'Пользователь с этим email уже зарегистрирован';
    }

    if ($email_formated) {
        $sign['path'] = '';
        $password = password_hash($sign['password'], PASSWORD_DEFAULT);

        if (!empty($_FILES['registr']['name']['photo'])) {
            $photoExt = explode('.', $_FILES['registr']['name']['photo'])[1];

            $filename = uniqid() . '.' . $photoExt;
            $sign['path'] = 'img/' . $filename;
            $result = move_uploaded_file($_FILES['registr']['tmp_name']['photo'], 'img/' . $filename);
        }

        $sql = 'INSERT INTO users (dt_registr, email, name, password, avatar, contacts) VALUES (NOW(), ?, ?, ?, ?, ?)';
        $dbHelper->executeQuery($sql, [$sign['email'], $sign['name'], $password, $sign['path'], $sign['message']]);
        $resID = $dbHelper->getID();

        if (0 < $resID) {
            header('Location: /sign_in.php');
            exit();
        }
    }

    $data_form['values'] = $sign;
    $data_form['errors'] = $errors;
}

$contents = include_template('registr.php', [
    'values' => $data_form['values'],
    'errors' => $data_form['errors']
]);

$html = include_template('layout.php', [
    'is_auth'    => $is_auth ?? null,
    'user_name'  => $user_name ?? null,
    'title'      => 'YetiCave - Регистрация',
    'submenu'    => $submenu,
    'index_page' => 'non',
    'contents'   => $contents
]);

print($html);
