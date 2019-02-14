<?php

date_default_timezone_set('Etc/GMT-3');
$nowTime = new DateTime('now');
$tomorTime = new DateTime('tomorrow');
$timeLaps = $nowTime->diff($tomorTime)->format('%H:%i');

$is_auth = rand(0, 1);
$user_name = 'Никита'; // укажите здесь ваше имя


$db['SERVER'] = 'localhost';
$db['DBUSERNAME'] = 'root';
$db['DBNAME'] = 'yeticave';
$db['PASS'] = '';

return $db;
?>