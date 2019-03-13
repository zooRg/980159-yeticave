<?php
require_once 'init.php';

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

if (!isset($is_auth)) {
    http_response_code(403);
    $contents = 'У вас нет прав для добавления лота. Пожалуйста авторизуйтесь!';
}

if ($dbHelper->getError()) {
    print $dbHelper->getError();
    exit();
}

$dbHelper->executeQuery('SELECT id, name AS name FROM category');

if (!$dbHelper->getError()) {
    $submenu = $dbHelper->getResultArray();
} else {
    print $dbHelper->getError();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($is_auth)) {
    $add_lot['category'] = (int)htmlspecialchars($_POST['lot']['category']);
    $add_lot['name'] = htmlspecialchars($_POST['lot']['name']);
    $add_lot['message'] = htmlspecialchars($_POST['lot']['message']);

    $add_lot['startPrice'] = $_POST['lot']['startPrice'] > 0
        ? (int)htmlspecialchars($_POST['lot']['startPrice'])
        : $data_html['errors']['startPrice'] = $errors['startPrice'];

    $add_lot['step'] = $_POST['lot']['step'] > 0
        ? (int)htmlspecialchars($_POST['lot']['step'])
        : $data_html['errors']['step'] = $errors['step'];

    $add_lot['dateEnd'] = $_POST['lot']['dateEnd'] > 0
        ? (int)htmlspecialchars($_POST['lot']['dateEnd'])
        : $data_html['errors']['dateEnd'] = $errors['dateEnd'];

    if (!empty($_FILES['lot']['name']['photo'])) {
        $photoExt = explode('.', htmlspecialchars($_FILES['lot']['name']['photo']))[1];
        $filename = uniqid() . '.' . $photoExt;
        $add_lot['path'] = 'img/' . $filename;
    }

    $sql = 'INSERT INTO lot'
        . ' (dt_add, category_id, autor_id, name, description, img, start_price, step, dt_end)'
        . ' VALUES'
        . ' (NOW(), ?, 1, ?, ?, ?, ?, ?, ?)';

    $date_end = date('Y-m-d H:i:s', strtotime(htmlspecialchars($add_lot['dateEnd'])));
    $date_now = date('Y-m-d H:i:s');

    if ($date_end < $date_now && $date_end - $date_now === 0) {
        $data_html['errors']['dateEnd'] = 'Дата должна быть не меньше текущей и не позднеее текущего года';
    }

    if (!isset($add_lot['category'])) {
        $data_html['errors']['category'] = $errors['category'];
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
    $res = $dbHelper->getID();

    if ($res) {
        $result = move_uploaded_file($_FILES['lot']['tmp_name']['photo'], 'img/' . $filename);
        header('Location: /lot.php?lot_id=' . $res);
        exit();
    }

    foreach ($errors as $key => $err) {
        if (!array_key_exists($key, $add_lot)) {
            $data_html['errors'][$key] = $errors[$key];
        }
    }

    if (stripos((string)$dbHelper->getError(), 'name')) {
        $data_html['errors']['name'] = 'Лот с таким наименованием уже существует (имя может содержать только буквы и цифры)';
    }

    $add_lot['category'] === $errors['category'] ? $data_html['errors']['category'] = $errors['category'] : null;

    $data_html['lot'] = $add_lot;
}

if (isset($is_auth)) {
    $contents = include_template('add-lot.php', [
        'submenu' => $submenu,
        'errors'  => $data_html['errors'],
        'values'  => $data_html['lot']
    ]);
}

$html = include_template('layout.php', [
    'is_auth'    => $is_auth ?? null,
    'user_name'  => $user_name ?? null,
    'title'      => 'YetiCave - Добавление лота',
    'submenu'    => $submenu,
    'index_page' => 'non',
    'contents'   => $contents
]);

print($html);