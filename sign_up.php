<?php

require_once('functions.php');
require_once('init.php');

$submenu = '';
$data_form = [];
$errors = [];

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

        $sign = $_POST['registr'];
        $email_formated = filter_var($sign['email'], FILTER_VALIDATE_EMAIL);

        $email = mysqli_real_escape_string($conn, $sign['email']);
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $res = mysqli_query($conn, $sql);

        if($email_formated === false) {
            $errors['users'] = 'Email неправильного формата';
        }

        if (mysqli_num_rows($res) > 0) {
            $errors['users'] = 'Пользователь с этим email уже зарегистрирован';
        }
        elseif ($email_formated) {
            $sign['path'] = '';
            $password = password_hash($sign['password'], PASSWORD_DEFAULT);

            if(!empty($_FILES['registr']['name']['photo'])) {
                $photoExt = explode('.', $_FILES['registr']['name']['photo'])[1];

                $filename = uniqid() . '.' . $photoExt;
                $sign['path'] = 'img/' . $filename;
                $result = move_uploaded_file($_FILES['registr']['tmp_name']['photo'], 'img/' . $filename);
            }

            $sql = 'INSERT INTO users (dt_registr, email, name, password, avatar) VALUES (NOW(), ?, ?, ?, ?)';
            $stmt = db_get_prepare_stmt($conn, $sql, [$sign['email'], $sign['name'], $password, $sign['path']]);
            $res = mysqli_stmt_execute($stmt);

            if ($res) {
                header("Location: /sign_in.php");
                exit();
            }
        }

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