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

/**
 * @param $price входная цена для форматирования
 * @return string
 */
function formatPrice($price)
{
    $price = number_format(ceil($price), 0, '.', ' ');

    $price = $price . ' ₽';

    return $price;
}

/**
 * @param $time - unix_time
 * @return string
 */
function time_format_laps($time) {
    $month_name = [
        1  => 'января',
        2  => 'февраля',
        3  => 'марта',
        4  => 'апреля',
        5  => 'мая',
        6  => 'июня',
        7  => 'июля',
        8  => 'августа',
        9  => 'сентября',
        10 => 'октября',
        11 => 'ноября',
        12 => 'декабря'
    ];

    $month = $month_name[date('n', $time)];
    $day = date('j', $time);
    $year = date('Y', $time);
    $hour = date('G', $time);
    $min = date('i', $time);
    $date = $day. ' '.$month. ' '.$year. ' г. в '.$hour. ':'.$min;
    $dif = time() - $time;

    if ($dif < 59) {
        return $dif. " сек. назад";
    } elseif($dif / 60 > 1and $dif / 60 < 59) {
        return round($dif / 60). " мин. назад";
    } elseif($dif / 3600 > 1and $dif / 3600 < 23) {
        return round($dif / 3600). " час. назад";
    }else{
        return $date;
    }
}

?>