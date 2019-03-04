<?php

require_once ('functions.php');
$db = require_once ('init.php');

$conn = mysqli_connect($db['SERVER'], $db['DBUSERNAME'], $db['PASS'], $db['DBNAME']);
mysqli_set_charset($conn, "utf8");

$submenu = '';
$adds = '';
$lotID = [];

if (!$conn) {
    $error = mysqli_connect_error();
    print ($error);
    die();
}
else {
    $sqlCat = 'SELECT `name` AS name FROM category';
    $sqlLots = 'SELECT y.name AS NAME, y.start_price AS PRICE, y.img AS PICTURE, c.name AS CATEGORY, y.id AS ID'
        . ' FROM lot y'
        . ' JOIN category c'
        . ' ON y.category_id = c.id'
        . ' WHERE y.data_add < y.date_end'
        . ' ORDER BY y.data_add DESC'
        . ' LIMIT 9';
    $resultCat = mysqli_query($conn, $sqlCat);
    $resultLot = mysqli_query($conn, $sqlLots);

    if ($resultCat && $resultLot) {
        $submenu = mysqli_fetch_all($resultCat, MYSQLI_ASSOC);
        $adds = mysqli_fetch_all($resultLot, MYSQLI_ASSOC);
    }
    else {
        $error = mysqli_error($conn);
        print ($error);
        die();
    }
}

$contents = include_template('index.php', [
    'submenu' => $submenu,
    'adds' => $adds,
    'timeLaps' => $timeLaps,
    'url' => '/lot.php'
]);

$html = include_template('layout.php', [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'title' => 'Главная - YetiCave',
    'submenu' => $submenu,
    'contents' => $contents
]);

print($html);
?>
