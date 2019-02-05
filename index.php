<?php

date_default_timezone_set('Etc/GMT-3');

$nowTime = new DateTime('now');
$tomorTime = new DateTime('tomorrow');
$timeLaps = $nowTime->diff($tomorTime)->format('%H:%i');

require ('functions.php');

$is_auth = rand(0, 1);

$user_name = 'Никита'; // укажите здесь ваше имя

$submenu = ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'];

$adds = [
    [
        'NAME'     => '2014 Rossignol District Snowboard',
        'CATEGORY' => 'Доски и лыжи',
        'PRICE'    => '10999',
        'PICTURE'  => 'img/lot-1.jpg'
    ],
    [
        'NAME'     => 'DC Ply Mens 2016/2017 Snowboard',
        'CATEGORY' => 'Доски и лыжи',
        'PRICE'    => '159999',
        'PICTURE'  => 'img/lot-2.jpg'
    ],
    [
        'NAME'     => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'CATEGORY' => 'Крепления',
        'PRICE'    => '8000',
        'PICTURE'  => 'img/lot-3.jpg'
    ],
    [
        'NAME'     => 'Ботинки для сноуборда DC Mutiny Charocal',
        'CATEGORY' => 'Ботинки',
        'PRICE'    => '10999',
        'PICTURE'  => 'img/lot-4.jpg'
    ],
    [
        'NAME'     => 'Куртка для сноуборда DC Mutiny Charocal',
        'CATEGORY' => 'Одежда',
        'PRICE'    => '7500',
        'PICTURE'  => 'img/lot-5.jpg'
    ],
    [
        'NAME'     => 'Маска Oakley Canopy',
        'CATEGORY' => 'Разное',
        'PRICE'    => '5400',
        'PICTURE'  => 'img/lot-6.jpg'
    ]
];

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

$contents = include_template('index.php', [
    'submenu' => $submenu,
    'adds' => $adds,
    'timeLaps' => $timeLaps
]);

$html = include_template('layout.php', [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'title' => 'Главная - YetiCave',
    'submenu' => $submenu,
    'contents' => $contents
]);

print($html);
?>
