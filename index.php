<?php
require_once('init.php');

$submenu = '';
$contents = '';

if ($dbHelper->getError()) {
    print $dbHelper->getError();
    exit();
} else {
    $dbHelper->executeQuery('SELECT `name` AS name FROM category');

    if (!$dbHelper->getError()) {
        $submenu = $dbHelper->getResultArray();
    } else {
        print $dbHelper->getError();
        exit();
    }

    $dbHelper->executeQuery('SELECT y.name AS NAME, y.dt_end, y.start_price AS PRICE, y.img AS PICTURE, c.name AS CATEGORY, y.id AS ID'
        . ' FROM lot y'
        . ' JOIN category c'
        . ' ON y.category_id = c.id'
        . ' WHERE y.dt_add < y.dt_end'
        . ' ORDER BY y.dt_add DESC'
        . ' LIMIT 9');

    if (!$dbHelper->getError()) {
        $adds = $dbHelper->getResultArray();

        $contents = include_template('index.php', [
            'submenu'  => $submenu,
            'adds'     => $adds,
            'timeLaps' => $timeLaps,
            'url'      => '/lot.php'
        ]);
    } else {
        print $dbHelper->getError();
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
