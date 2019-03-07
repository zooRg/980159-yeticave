<?php
require_once('functions.php');
require_once('init.php');

$submenu = '';
$contents = '';
$data_html = [];
$errors = [];
$errors['category'] = 'Выберите категорию';
$errors['name'] = 'Введите наименование лота';
$errors['message'] = 'Напишите описание лота';
$errors['path'] = 'Добавьте фотографию';
$errors['startPrice'] = 'Введите начальную цену';
$errors['step'] = 'Введите шаг ставки';
$errors['dateEnd'] = 'Введите дату завершения торгов';

if (!$is_auth) {
    http_response_code(403);
    exit();
}

if ($dbHelper->getError()) {
    print $dbHelper->getError();
    exit();
} else {
    $dbHelper->executeQuery('SELECT id, name AS name FROM category');

    if (!$dbHelper->getError()) {
        $submenu = $dbHelper->getResultArray();
    } else {
        print $dbHelper->getError();
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $add_lot = $_POST['lot'];

        if (!empty($_FILES['lot']['name']['photo'])) {
            $photoExt = explode('.', htmlspecialchars($_FILES['lot']['name']['photo']))[1];
            $filename = uniqid() . '.' . $photoExt;
            $add_lot['path'] = 'img/' . $filename;
        }

        $sql = 'INSERT INTO lot'
            . ' (dt_add, category_id, autor_id, name, description, img, start_price, step, dt_end)'
            . ' VALUES'
            . ' (NOW(), ?, 1, ?, ?, ?, ?, ?, ?)';

        $date_end = date('Y-m-d H:i:s', strtotime($dbHelper->getEscapeStr($add_lot['dateEnd'])));
        $date_now = date('Y-m-d H:i:s');

        if ($date_end < $date_now) {
            $data_html['errors']['dateEnd'] = 'Дата должна быть больше текущей';
        }

        $dbHelper->executeQuery(
            $sql,
            [
                $dbHelper->getEscapeStr($add_lot['category']),
                $dbHelper->getEscapeStr($add_lot['name']),
                $dbHelper->getEscapeStr($add_lot['message']),
                $dbHelper->getEscapeStr($add_lot['path']),
                $dbHelper->getEscapeStr($add_lot['startPrice']),
                $dbHelper->getEscapeStr($add_lot['step']),
                $date_end
            ]
        );
        $res = $dbHelper->getResultArray();

        if ($res) {
            $result = move_uploaded_file($_FILES['lot']['tmp_name']['photo'], 'img/' . $filename);
            $lot_id = $dbHelper->getID();
            header("Location: /lot.php?lot_id=" . $lot_id);
            exit();
        }

        foreach ($errors as $key => $err) {
            if (!array_key_exists($key, $add_lot)) {
                $data_html['errors'][$key] = $errors[$key];
            }
        }

        if (stripos(strval($dbHelper->getError()), 'name')) {
            $data_html['errors']['name'] = 'Лот с таким наименованием уже существует';
        }

        $add_lot['category'] === $errors['category'] ? $data_html['errors']['category'] = $errors['category'] : null;

        $data_html['lot'] = $add_lot;
    }

    $contents = include_template('add-lot.php', [
        'submenu' => $submenu,
        'errors'  => $data_html['errors'],
        'values'  => $data_html['lot']
    ]);
}

$html = include_template('layout.php', [
    'is_auth'    => $is_auth,
    'user_name'  => $user_name,
    'title'      => 'YetiCave - Добавление лота',
    'submenu'    => $submenu,
    'index_page' => 'non',
    'contents'   => $contents
]);

print($html);
?>