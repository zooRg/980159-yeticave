<?php

/**
 * @param $name - название шаблона
 * @param $data - входной массив
 * @return string
 */
function include_template($name, $data)
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * @param $link - ресурс соеденения
 * @param $sql - запрос к базе данных с плейсхолдерами
 * @param array $data - массив с данными подстановки плейсхолдера
 * @return array|null
 */
function db_fetch ($link, $sql, $data = [])
{
    $result = [];
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if ($res) {
        $result = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }

    return $result;
}

/**
 * @param $link - ресурс соеденения
 * @param $sql - запрос к базе данных с плейсхолдерами
 * @param array $data - массив с данными подстановки плейсхолдера
 * @return bool|int|string
 */
function db_insert ($link, $sql, $data = [])
{
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $result = mysqli_insert_id($link);
    }

    return $result;
}

?>