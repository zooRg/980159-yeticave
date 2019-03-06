<?php

require_once ('functions.php');
require_once ('init.php');

$submenu = '';
$contents = '';

if (!$conn) {
    $error = mysqli_connect_error();
    print ($error);
    exit();
}
else {
    $sqlCat = 'SELECT `name` AS name FROM category';
    $sqlLots = 'SELECT y.name AS NAME, y.start_price AS PRICE, y.img AS PICTURE, c.name AS CATEGORY, y.id AS ID'
        . ' FROM lot y'
        . ' JOIN category c'
        . ' ON y.category_id = c.id'
        . ' WHERE y.dt_add < y.dt_end'
        . ' ORDER BY y.dt_add DESC'
        . ' LIMIT 9';
    $resultCat = mysqli_query($conn, $sqlCat);
    $resultLot = mysqli_query($conn, $sqlLots);

    if ($resultCat && $resultLot) {
        $submenu = mysqli_fetch_all($resultCat, MYSQLI_ASSOC);
        $adds = mysqli_fetch_all($resultLot, MYSQLI_ASSOC);

        $contents = include_template('index.php', [
            'submenu'  => $submenu,
            'adds'     => $adds,
            'timeLaps' => $timeLaps,
            'url'      => '/lot.php'
        ]);
    }
    else {
        $error = mysqli_error($conn);
        print ($error);
        exit();
    }
}

$html = include_template('layout.php', [
    'is_auth'   => $is_auth,
    'user_name' => $user_name,
    'title'     => 'Главная - YetiCave',
    'submenu'   => $submenu,
    'contents'  => $contents
]);

print($html);
?>
