<?php
require_once('functions.php');
require_once('Database.php');

session_start();
$is_auth = false;

if (isset($_SESSION['user'])) {
    $is_auth = true;
    $user_name = $_SESSION['user']['name']; // укажите здесь ваше имя
}

$db = ['localhost', 'root', '', 'yeticave'];

$dbHelper = new Database(...$db);
?>