<?php
require_once('helpers.php');
$con = mysqli_connect ("localhost", "root", "root", "yeticave");
mysqli_set_charset($con, "utf8");

$sqlLotsList = "SELECT name, start_price, picture, IF(max(amount) IS NULL, start_price, max(amount)) AS 'current_price', category, end_date
FROM  (SELECT l.*, b.amount, c.name as 'category' from lot l
        LEFT JOIN category c on l.category_id = c.id
        LEFT JOIN bid b on l.id = b.lot_id
        WHERE l.end_date > SYSDATE()
ORDER BY l.create_date DESC, b.date DESC) tbl
GROUP BY id";
$lotsObj = mysqli_query($con, $sqlLotsList);
$lotsArray = mysqli_fetch_all($lotsObj, MYSQLI_ASSOC);

$sqlCategoriesList = "select * from category";
$sqlCategoriesObj = mysqli_query($con, $sqlCategoriesList);
$categoriesArray = mysqli_fetch_all($sqlCategoriesObj, MYSQLI_ASSOC);

function getSafeValue($element) {
    return htmlspecialchars($element);
};
function getSafeArray($array) {
    foreach ($array as $element) {
        array_map("getSafeValue", $element);
    }
    return $array;
};

$title = 'Название страницы';
$user_name = 'Андрей Изюмов';
$params = $_GET;
$categories = ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'];
$products = [
    [
        'name' => '2014 Rossignol District Snowboard',
        'category' => 'Доски и лыжи',
        'price' => 10999,
        'img-url' => 'img/lot-1.jpg',
        'end-date' => '2020-10-23',
    ],
    [
        'name' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => 'Доски и лыжи',
        'price' => 159999,
        'img-url' => 'img/lot-2.jpg',
        'end-date' => '2020-10-24',
    ],
    [
        'name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => 'Крепления',
        'price' => 8000,
        'img-url' => 'img/lot-3.jpg',
        'end-date' => '2020-10-25',
    ],
    [
        'name' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'category' => 'Ботинки',
        'price' => 10999,
        'img-url' => 'img/lot-4.jpg',
        'end-date' => '2020-10-26',
    ],
    [
        'name' => 'Куртка для сноуборда DC Mutiny Charocal',
        'category' => 'Одежда',
        'price' => 7500,
        'img-url' => 'img/lot-5.jpg',
        'end-date' => '2020-10-27',
    ],
    [
        'name' => 'Маска Oakley Canopy',
        'category' => 'Разное',
        'price' => 5400,
        'img-url' => 'img/lot-6.jpg',
        'end-date' => '2020-10-28',
    ],
];
$safeCategories = array_map('getSafeValue', $categories);
$safeProducts = getSafeArray($products);
$main = include_template('main.php', [
    'categories' => $categoriesArray,
    'lots' => $lotsArray,
    'con' => $con,
]);

$layout = include_template('layout.php', [
    'user_name' => $user_name,
    'title' => $title,
    'params' => $params,
    'main' => $main,
    'categories' => $categoriesArray,
]);

print($layout);
