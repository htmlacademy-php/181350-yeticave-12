<?php
$con = mysqli_connect ("localhost", "root", "root", "yeticave");
mysqli_set_charset($con, "utf8");

function include_template($name, array $data = []) {
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

$sqlCategoriesList = "select * from category";
$sqlCategoriesObj = mysqli_query($con, $sqlCategoriesList);
$categoriesArray = mysqli_fetch_all($sqlCategoriesObj, MYSQLI_ASSOC);

$title = 'Название страницы';
$user_name = 'Андрей Изюмов';

$main = include_template('add.php', [
    'categories' => $categoriesArray
]);

$layout = include_template('layout.php', [
    'user_name' => $user_name,
    'title' => $title,
    'main' => $main,
    'categories' => $categoriesArray,
    'con' => $con
]);

print($layout);
