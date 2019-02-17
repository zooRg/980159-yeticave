<?php

require_once('functions.php');
$db = require_once('init.php');

$conn = mysqli_connect($db['SERVER'], $db['DBUSERNAME'], $db['PASS'], $db['DBNAME']);
mysqli_set_charset($conn, "utf8");

$submenu = '';
$lots = '';
$lotID = '';


if (isset($_GET['lot_id'])) {
    $lotID = $_GET['lot_id'];
} else {
    http_response_code(404);
    print ('Страница не найдена');
    die();
}


if (!$conn) {
    $error = mysqli_connect_error();
    print ($error);
    die();
} else {
    $sqlCat = 'SELECT `name` AS name FROM category';
    $sqlLots = 'SELECT c.name AS CATEGORY_NAME, y.*'
        . ' FROM lot y'
        . ' JOIN category c'
        . ' ON y.category_id = c.id'
        . ' WHERE y.id = ' . $lotID;
    $sqlUsers = 'SELECT u.name, b.*'
        . ' FROM bets b'
        . ' JOIN users u'
        . ' ON b.autor_id = u.id'
        . ' WHERE b.lot_id = ' . $lotID
        . ' ORDER BY b.data_add DESC';

    $resultCat = mysqli_query($conn, $sqlCat);
    $resultLot = mysqli_query($conn, $sqlLots);
    $resultUser = mysqli_query($conn, $sqlUsers);

    if ($resultCat && $resultLot) {
        $submenu = mysqli_fetch_all($resultCat, MYSQLI_ASSOC);
        $lots = mysqli_fetch_all($resultLot, MYSQLI_ASSOC);
        $users = mysqli_fetch_all($resultUser, MYSQLI_ASSOC);
        $lots = $lots[0];
    } else {
        $error = mysqli_error($conn);
        print ($error);
        die();
    }
}

if (empty($lots)) {
    http_response_code(404);
    print ('Страница не найдена');
    die();
}


$html = include_template('lot.php', [
    'is_auth'       => $is_auth,
    'user_name'     => $user_name,
    'title'         => $lots['name'],
    'submenu'       => $submenu,
    'timeLaps'      => $timeLaps,
    'category_name' => $lots['CATEGORY_NAME'],
    'category_desc' => $lots['description'],
    'start_price'   => $lots['start_price'],
    'step'          => $lots['step'],
    'lot_img'       => $lots['img'],
    'users'         => $users
]);

print($html);

?>