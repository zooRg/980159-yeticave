<?php

require_once ('functions.php');
$db = require_once ('init.php');

$conn = mysqli_connect($db['SERVER'], $db['DBUSERNAME'], $db['PASS'], $db['DBNAME']);
mysqli_set_charset($conn, "utf8");

$submenu = '';
$adds = '';

if (!$conn) {
    $error = mysqli_connect_error();
    print ($error);
    die();
}
else {
    $sqlCat = 'SELECT `name` AS name FROM category';
    $sqlLots = 'SELECT y.name AS NAME, y.start_price AS PRICE, y.img AS PICTURE, c.name AS CATEGORY'
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

/**
 * @param $price входная цена для форматирования
 * @return string
 */
function formatPrice($price)
{
    $price = number_format(ceil($price), 0, '.', ' ');

    $price = $price . ' ₽';

    return $price;
}

$contents = include_template('index.php', [
    'submenu' => $submenu,
    'adds' => $adds,
    'timeLaps' => $timeLaps
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
