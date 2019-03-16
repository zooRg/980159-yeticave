<?php
require_once 'init.php';
require_once 'getwinner.php';

$submenu = '';
$contents = '';
$bets = [];

if (!isset($_SESSION['user'])) {
    $contents = 'Вы не авторизованы, пожалуйста авторизуйтесь!';
}

if ($dbHelper->getError()) {
    print $dbHelper->getError();
    exit();
}

$dbHelper->executeQuery('SELECT name, id FROM category');

if ($dbHelper->getError()) {
    print $dbHelper->getError();
    exit();
}

$submenu = $dbHelper->getResultArray();
$user_id = (int)$dbHelper->getEscapeStr($_SESSION['user']['id']);

if (isset($_SESSION['user'])) {
    $dbHelper->executeQuery('SELECT b.*, u.contacts, l.id, l.img, l.dt_end, l.vinner_id, l.name AS lot_name, c.name AS category_name'
        . ' FROM bets b'
        . ' JOIN lot l'
        . ' ON b.lot_id = l.id'
        . ' JOIN category c'
        . ' ON l.category_id = c.id'
        . ' JOIN users u'
        . ' ON l.autor_id = u.id'
        . ' WHERE b.autor_id = ' . $user_id
        . ' ORDER BY b.dt_add ASC'
    );

    if ($dbHelper->getError()) {
        print $dbHelper->getError();
        exit();
    }
    $bets = $dbHelper->getResultArray();

    foreach ($bets as $bet) {
        $uniq_bets[$bet['id']] = $bet;
        if (isset($uniq_bets[$bet['id']]['vinner_id']) && $uniq_bets[$bet['id']]['vinner_id'] !== $user_id) {
            $uniq_bets[$bet['id']]['other_user'] = $bet['vinner_id'];
        }
    }
    $bets = $uniq_bets ?? '';

    $contents = include_template('my-lots.php', [
        'category_name' => $category_name ?? null,
        'category_id'   => $categoryID ?? null,
        'bets'          => $bets ?? null,
    ]);
}

$html = include_template('layout.php', [
    'is_auth'     => $is_auth ?? null,
    'user_name'   => $user_name ?? null,
    'title'       => 'YetiCave - Мои ставки',
    'index_page'  => 'non',
    'submenu'     => $submenu ?? null,
    'category_id' => $categoryID ?? null,
    'contents'    => $contents ?? null
]);

print($html);