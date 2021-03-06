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
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = null;

            if (is_int($value)) {
                $type = 'i';
            } elseif (is_string($value)) {
                $type = 's';
            } elseif (is_float($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);
    }

    return $stmt;
}

/**
 * @param $price - входная цена для форматирования
 * @return string
 */
function formatPrice($price)
{
    $price = number_format(ceil($price), 0, '.', ' ');

    $price .= ' ₽';

    return $price;
}

/**
 * @param $time - unix_time
 * @return string
 */
function time_format_laps($time)
{
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
    $date = $day . ' ' . $month . ' ' . $year . ' г. в ' . $hour . ':' . $min;
    $diff = time() - $time;
    $time_laps = $date;

    if (59 > $diff) {
        $time_laps = $diff . ' сек. назад';
    } elseif ( 1 < $diff / 60 && 59 > $diff / 60) {
        $time_laps = round($diff / 60) . ' мин. назад';
    } elseif (1 < $diff / 3600 && 23 > $diff / 3600) {
        $time_laps = round($diff / 3600) . ' час. назад';
    }

    return $time_laps;
}

/**
 * @param $time - входящая дата в формате в 2019-03-06 17:12:05
 * @return string
 */
function time_lot_laps($time, $only_time = null)
{
    date_default_timezone_set('Etc/GMT-3');
    $nowTime = new DateTime('now');
    $tomorTime = new DateTime($time);
    $date = 'Закончен';
    if ($nowTime->getTimestamp() < $tomorTime->getTimestamp()) {
        $date = $nowTime->diff($tomorTime)->format('%mмес. %dдн. %H:%I:%s');
        if (isset($only_time)) {
            $date = $nowTime->diff($tomorTime)->format('%H:%I:%s');
        }
    }
    return $date;
}
