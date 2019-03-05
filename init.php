<?php

date_default_timezone_set('Etc/GMT-3');
$nowTime = new DateTime('now');
$tomorTime = new DateTime('tomorrow');
$timeLaps = $nowTime->diff($tomorTime)->format('%H:%i');

session_start();

if (isset($_SESSION['user'])) {
    $is_auth = true;
    $user_name = $_SESSION['user']['name']; // укажите здесь ваше имя
}
else {
    $is_auth = false;
}

$db['SERVER'] = 'localhost';
$db['DBUSERNAME'] = 'root';
$db['DBNAME'] = 'yeticave';
$db['PASS'] = '';

$conn = mysqli_connect($db['SERVER'], $db['DBUSERNAME'], $db['PASS'], $db['DBNAME']);
mysqli_set_charset($conn, "utf8");

?>