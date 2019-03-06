<?php

require_once('functions.php');
require_once('init.php');

$submenu = '';
$lots = [];

if (!$conn) {
    $error = mysqli_connect_error();
    print ($error);
    exit();
} else {
    $sqlCat = 'SELECT id, name AS name FROM category';

    $resultCat = mysqli_query($conn, $sqlCat);

    if ($resultCat) {
        $submenu = mysqli_fetch_all($resultCat, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($conn);
        print ($error);
        exit();
    }

    $search = $_GET['search'] ?? '';
    $search = htmlspecialchars($search);

    if ($search) {
        $sql = "SELECT lot.name, lot.img, lot.start_price, lot.dt_end, category.name AS category_name"
            . " FROM lot"
            . " JOIN category"
            . " ON lot.category_id = category.id"
            . " WHERE MATCH(lot.name, description) AGAINST(?)";

        $stmt = db_get_prepare_stmt($conn, $sql, [$conn->real_escape_string($search)]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    $contents = include_template('search_tpl.php', [
        'lots' => $lots,
        'search' => $search
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