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

$dbHelper->executeQuery('SELECT name, id FROM category');

if (!$dbHelper->getError()) {
    $submenu = $dbHelper->getResultArray();
} else {
    print $dbHelper->getError();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($is_auth)) {

    $add_lot['category'] = (int)htmlspecialchars($_POST['lot']['category']
        ?? $data_html['errors']['category'] = $errors['category']);

    $add_lot['name'] = htmlspecialchars($_POST['lot']['name']
        ? htmlspecialchars($_POST['lot']['name'])
        : $data_html['errors']['name'] = $errors['name']);

    $add_lot['message'] = htmlspecialchars($_POST['lot']['message']
        ?? $data_html['errors']['message'] = $errors['message']);

    $add_lot['startPrice'] = $_POST['lot']['startPrice'] > 0
        ? (int)htmlspecialchars($_POST['lot']['startPrice'])
        : $data_html['errors']['startPrice'] = $errors['startPrice'];

    $add_lot['step'] = $_POST['lot']['step'] > 0
        ? (int)htmlspecialchars($_POST['lot']['step'])
        : $data_html['errors']['step'] = $errors['step'];

    $add_lot['dateEnd'] = $_POST['lot']['dateEnd']
        ? htmlspecialchars($_POST['lot']['dateEnd'])
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

    date_default_timezone_set('Etc/GMT-3');
    $date_now = new DateTime('now');
    $date_end = new DateTime(intval($add_lot['dateEnd']) ? $add_lot['dateEnd'] : 'now');
    $date = $date_end->diff($date_now)->days;
    $date_summ = $date_end->getTimestamp() - $date_now->getTimestamp();
    $date_summ = round($date_summ / 3600 / 24);

    if ($date_summ <= 0 || $date > 60) {
        $data_html['errors']['dateEnd'] = 'Дата должна быть не меньше текущей и не позднеее 2 месяцев';
    }

    if ($date < 60 && $date_summ > 0
        && isset(
            $_POST['lot']['dateEnd'],
            $_POST['lot']['category'],
            $_POST['lot']['name'],
            $_POST['lot']['message'],
            $_POST['lot']['startPrice'],
            $_POST['lot']['step'])
    ) {
        $dbHelper->executeQuery(
            $sql,
            [
                $dbHelper->getEscapeStr($add_lot['category']),
                $dbHelper->getEscapeStr($add_lot['name']),
                $dbHelper->getEscapeStr($add_lot['message']),
                $dbHelper->getEscapeStr($add_lot['path']),
                $dbHelper->getEscapeStr($add_lot['startPrice']),
                $dbHelper->getEscapeStr($add_lot['step']),
                $date_end->format('Y-m-d H:i:s')
            ]
        );
        $res = $dbHelper->getID() ?? null;

        if ($res) {
            $result = move_uploaded_file($_FILES['lot']['tmp_name']['photo'], 'img/' . $filename);
            header('Location: /lot.php?lot_id=' . $res);
            exit();
        }
    }

    foreach ($errors as $key => $err) {
        if (!array_key_exists($key, $add_lot)) {
            $data_html['errors'][$key] = $errors[$key];
        }
    }

    if (stripos((string)$dbHelper->getError(), 'name')) {
        $data_html['errors']['name'] = 'Лот с таким наименованием уже существует (имя может содержать только буквы и цифры)';
    }

    if ($add_lot['category'] === $errors['category']) {
        $data_html['errors']['category'] = $errors['category'];
    }

    $data_html['lot'] = $add_lot;
}

if (isset($is_auth)) {
    $contents = include_template('add-lot.php', [
        'submenu' => $submenu,
        'errors'  => $data_html['errors'] ?? '',
        'values'  => $data_html['lot'] ?? ''
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