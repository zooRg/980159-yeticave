<?php
require_once 'init.php';

$submenu = '';
$items_count = 0;
$pages = 0;
$pages_count = 0;
$lots = [];

if ($dbHelper->getError()) {
    print $dbHelper->getError();
    exit();
}

$dbHelper->executeQuery('SELECT name, id FROM category');

if (!$dbHelper->getError()) {
    $submenu = $dbHelper->getResultArray();
} else {
    print $dbHelper->getError();
    exit();
}

$search = $dbHelper->getEscapeStr($_GET['search'] ?? '');
$cur_page = $dbHelper->getEscapeStr($_GET['page'] ?? 1);
$search = htmlspecialchars($search);
$page_items = 3;

if (isset($search)) {
    $sql = 'SELECT COUNT(*) as cnt'
        . ' FROM lot'
        . ' WHERE MATCH(name, description) AGAINST(?)';
    $dbHelper->executeQuery($sql, [$search]);
    $items_count = $dbHelper->getResultArray()[0]['cnt'] ?? '';

    $pages_count = ceil($items_count / $page_items);
    $offset = ($cur_page - 1) * $page_items;

    $pages = range(1, $pages_count);

    $sql = 'SELECT lot.*, category.name AS category_name'
        . ' FROM lot'
        . ' JOIN category'
        . ' ON lot.category_id = category.id'
        . ' WHERE MATCH(lot.name, description) AGAINST(?)'
        . ' GROUP BY lot.name'
        . ' LIMIT ' . $page_items . ' OFFSET ' . $offset;

    $dbHelper->executeQuery($sql, [$search]);
    $lots = $dbHelper->getResultArray();
}

$contents = include_template('search_tpl.php', [
    'lots'        => $lots ?? null,
    'search'      => $search ?? null,
    'count'       => $items_count ?? null,
    'pages'       => $pages ?? null,
    'pages_count' => $pages_count ?? null,
    'cur_page'    => $cur_page ?? null
]);

$html = include_template('layout.php', [
    'is_auth'    => $is_auth ?? null,
    'user_name'  => $user_name ?? null,
    'title'      => 'YetiCave - поиск по сайту',
    'submenu'    => $submenu ?? '',
    'index_page' => 'non',
    'contents'   => $contents ?? ''
]);

print($html);
