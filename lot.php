<?php
require_once 'init.php';

$submenu = '';
$lots = '';
$lotID = '';
$contents = [];
$cost = '';
$users = '';
$error_cost = 'Введите вашу ставку';

if (isset($_GET['lot_id'])) {
    $lotID = $dbHelper->getEscapeStr(htmlspecialchars($_GET['lot_id']));
} else {
    http_response_code(404);
    print ('Страница не найдена');
    exit();
}

if ($dbHelper->getError()) {
    print $dbHelper->getError();
    exit();
}

$sqlCat = 'SELECT `name` AS name FROM category';
$dbHelper->executeQuery($sqlCat);

if (!$dbHelper->getError()) {
    $submenu = $dbHelper->getResultArray();
} else {
    print $dbHelper->getError();
    exit();
}

$sqlLots = 'SELECT c.name AS CATEGORY_NAME, y.*'
    . ' FROM lot y'
    . ' JOIN category c'
    . ' ON y.category_id = c.id'
    . " WHERE y.id = '$lotID'";
$dbHelper->executeQuery($sqlLots);

if (!$dbHelper->getError()) {
    $lot = $dbHelper->getResultArray();
    $lots = $lot[0];
} else {
    print $dbHelper->getError();
    exit();
}

$sqlUsers = 'SELECT u.name, b.*'
    . ' FROM bets b'
    . ' JOIN users u'
    . ' ON b.autor_id = u.id'
    . " WHERE b.lot_id = '$lotID'"
    . ' ORDER BY b.dt_add DESC';
$dbHelper->executeQuery($sqlUsers);

if (!$dbHelper->getError()) {
    $users = $dbHelper->getResultArray();
} else {
    print $dbHelper->getError();
    exit();
}

if (empty($lots)) {
    http_response_code(404);
    print ('Страница не найдена');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_numeric($_POST['cost'])) {
    $cost = (int)$_POST['cost'];
}

$start_price = $users[0]['sum_price'];
$step = (int)$lots['step'];
$end_sum = ceil($start_price + $step);

if (isset($is_auth) && $cost >= $end_sum) {
    $user_id = $dbHelper->getEscapeStr($_SESSION['user']['id']);
    $cost = $dbHelper->getEscapeStr($cost);
    $lotID = $dbHelper->getEscapeStr($lotID);

    $sql = 'INSERT INTO bets'
        . ' (dt_add, sum_price, autor_id, lot_id)'
        . ' VALUES'
        . ' (NOW(), ?, ?, ?)';

    $dbHelper->executeQuery(
        $sql,
        [
            $cost,
            $user_id,
            $lotID
        ]
    );
    $users = $dbHelper->getResultArray();

    header('Location: /lot.php?lot_id=' . $lotID);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $cost < $end_sum) {
    $error_cost = 'Ваша ставка меньше минимальной';
}

$contents = include_template('lot.php', [
    'is_auth'       => $is_auth ?? null,
    'title'         => $lots['name'],
    'timeLaps'      => time_lot_laps($lots['dt_end']),
    'category_name' => $lots['CATEGORY_NAME'],
    'category_desc' => $lots['description'],
    'start_price'   => $start_price,
    'step'          => $end_sum,
    'lot_img'       => $lots['img'],
    'users'         => $users,
    'error_cost'    => $error_cost,
    'cost'          => $cost
]);

$html = include_template('layout.php', [
    'is_auth'    => $is_auth ?? null,
    'user_name'  => $user_name ?? null,
    'title'      => 'YetiCave - ' . $lots['name'],
    'submenu'    => $submenu,
    'index_page' => 'non',
    'contents'   => $contents
]);

print($html);

