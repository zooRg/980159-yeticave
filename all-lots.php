<?php
require_once 'init.php';
require_once 'getwinner.php';

$submenu = '';
$contents = '';
$categoryID = 1;
$category_name = '';
$pages = 0;
$pages_count = 0;
$cur_page = 1;
$page_items = 9;
$lots = [];


if (isset($_GET['category_id'])) {
    $categoryID = (int) $dbHelper->getEscapeStr($_GET['category_id']);
} else {
    http_response_code(404);
    print 'Страница не найдена';
    exit();
}

if (isset($_GET['page'])) {
    $cur_page = $dbHelper->getEscapeStr($_GET['page']);
}

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

$dbHelper->executeQuery('SELECT COUNT(*) as cnt, c.name'
    . ' FROM lot y'
    . ' JOIN category c'
    . ' ON y.category_id = c.id'
    . ' WHERE c.id = ' . $categoryID
);
$category = $dbHelper->getResultArray() ?? '';
$items_count = $category[0]['cnt'] ?? 0;
$category_name = $category[0]['name'] ?? '';

$pages_count = ceil($items_count / $page_items);
$offset = ($cur_page - 1) * $page_items;

$pages = range(1, $pages_count);

$dbHelper->executeQuery('SELECT y.name AS NAME, y.dt_end, y.start_price AS PRICE, y.img AS PICTURE, c.name AS CATEGORY, y.id AS ID'
    . ' FROM lot y'
    . ' JOIN category c'
    . ' ON y.category_id = c.id'
    . ' WHERE y.dt_add < y.dt_end AND c.id = ' . $categoryID
    . ' ORDER BY y.dt_add DESC'
    . ' LIMIT ' . $page_items . ' OFFSET ' . $offset
);

if (!$dbHelper->getError()) {
    $lots = $dbHelper->getResultArray();

    $contents = include_template('all-lots.php', [
        'category_name' => $category_name ?? null,
        'category_id'   => $categoryID ?? null,
        'lots'          => $lots ?? null,
        'cur_page'      => $cur_page ?? null,
        'pages'         => $pages ?? null,
        'pages_count'   => $pages_count ?? null
    ]);
} else {
    print $dbHelper->getError();
    exit();
}

$html = include_template('layout.php', [
    'is_auth'     => $is_auth ?? null,
    'user_name'   => $user_name ?? null,
    'title'       => 'YetiCave - ' . $category_name ?? null,
    'index_page'  => 'non',
    'submenu'     => $submenu ?? null,
    'category_id' => $categoryID ?? null,
    'contents'    => $contents ?? null
]);

print($html);

