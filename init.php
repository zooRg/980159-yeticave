<?php

session_start();
$is_auth = false;

if (isset($_SESSION['user'])) {
    $is_auth = true;
    $user_name = $_SESSION['user']['name']; // укажите здесь ваше имя
}

$db['SERVER'] = 'localhost';
$db['DBUSERNAME'] = 'root';
$db['DBNAME'] = 'yeticave';
$db['PASS'] = '';

$conn = mysqli_connect($db['SERVER'], $db['DBUSERNAME'], $db['PASS'], $db['DBNAME']);
mysqli_set_charset($conn, "utf8");

?>