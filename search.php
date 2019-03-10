<?php
require_once('init.php');

$submenu = '';
$lots = [];

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

    $search = $dbHelper->getEscapeStr($_GET['search']) ?? '';
    $cur_page = $_GET['page'] ?? 1;
    $search = htmlspecialchars($search);
    $page_items = 3;

    if ($search) {
        $sql = "SELECT COUNT(*) as cnt"
            . " FROM lot"
            . " WHERE MATCH(name, description) AGAINST(?)";
        $dbHelper->executeQuery($sql, [$search]);
        $items_count = $dbHelper->getResultArray()[0]['cnt'];

        $pages_count = ceil($items_count / $page_items);
        $offset = ($cur_page - 1) * $page_items;

        $pages = range(1, $pages_count);

        $sql = "SELECT lot.*, category.name AS category_name"
            . " FROM lot"
            . " JOIN category"
            . " ON lot.category_id = category.id"
            . " WHERE MATCH(lot.name, description) AGAINST(?)"
            . " GROUP BY lot.name"
            . " LIMIT " . $page_items . " OFFSET " . $offset . "";

        $dbHelper->executeQuery($sql, [$search]);
        $lots = $dbHelper->getResultArray();
    }

    $contents = include_template('search_tpl.php', [
        'lots'        => $lots,
        'search'      => $search,
        'count'       => $items_count,
        'pages'       => $pages,
        'pages_count' => $pages_count,
        'cur_page'    => $cur_page
    ]);
}

$html = include_template('layout.php', [
    'is_auth'    => $is_auth,
    'user_name'  => $user_name,
    'title'      => 'YetiCave - поиск по сайту',
    'submenu'    => $submenu,
    'index_page' => 'non',
    'contents'   => $contents
]);

print($html);
?>