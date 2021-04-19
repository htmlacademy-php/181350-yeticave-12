<?php
require_once('helpers.php');
$con = mysqli_connect ("localhost", "root", "root", "yeticave");
mysqli_set_charset($con, "utf8");

$sqlCategoriesList = "select * from category";
$sqlCategoriesObj = mysqli_query($con, $sqlCategoriesList);
$categoriesArray = mysqli_fetch_all($sqlCategoriesObj, MYSQLI_ASSOC);

function getPostVal($name) {
    return $_POST[$name] ?? "";
}

function validateText($name, $error_description, $max_val_of_char)
{
    if (empty($_POST[$name])) {
        return $error_description;
    }
    if ($result = isCorrectLength($name, 1, $max_val_of_char)) {
        return $result;
    }
    return null;
}

function isCorrectLength($name, $min, $max)
{
    $len = strlen($_POST[$name]);

    if ($len < $min or $len > $max) {
        return "Значение должно быть от $min до $max символов";
    }
    return null;
}

function validateIssetCategory($categories, $category)
{
    if (!in_array($category, $categories)) {
        return "Выберите категорию";
    }
    return null;
}

function validateImage($name)
{
    if ($_FILES[$name]['size'] == 0) {
        return "Загрузите картинку";
    }
    if (isset($_FILES[$name])) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_name = $_FILES[$name]['tmp_name'];
        $file_size = $_FILES[$name]['size'];
        $file_type = finfo_file($finfo, $file_name);

        if ($file_type !== 'image/jpg' && $file_type !== 'image/jpeg') {
            return "Загрузите картинку в формате JPG/JPEG";
        }
        if ($file_size > 200000) {
            return "Максимальный размер файла: 200Кб";
        }
    }

    return null;
}

function validatePrice()
{

    if (intval($_POST['start_price']) <= 0) {
        return "Введите начальную цену";
    }
    return null;
}

function validateStep()
{
    if (intval($_POST['bid_step']) <= 0) {
        return "Введите шаг ставки";
    }
    return null;
}

function convertDataToTimestamp()
{
    $post_date = $_POST['end_date'];
    return $timestamp_post_date = strtotime($post_date);
}

function validateDate()
{
    $set_date = convertDataToTimestamp();
    $current_date = date('Y-m-d');
    $current_data_timestamp = strtotime($current_date);
    if (empty($_POST['end_date'])) {
        return "Введите дату";
    }
    if ($set_date <= $current_data_timestamp) {
        return "Дата окончания должна быть больше текущей";
    }

    return null;
}

$warning_about_errors = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $required_fields = ['name', 'category', 'description', 'picture', 'start_price', 'bid_step', 'end_date'];

    $errors = [];

    $rules = [
        'name' => function () {
            return validateText('name', "Введите наименование лота", 50);
        },
        'category' => function () use ($categoriesArray) {
            return validateIssetCategory($categoriesArray, $_POST['category']);
        },
        'description' => function () {
            return validateText("description", "Напишите описание лота", 200);
        },
        'picture' => function () {
            return validateImage('picture');
        },
        'start_price' => function () {
            return validatePrice();
        },
        'bid_step' => function () {
            return validateStep();
        },
        'end_date' => function () {
            return validateDate();
        }
    ];
}

foreach ($_POST as $key => $value) {
    if (isset($rules[$key])) {
        $rule = $rules[$key];
        $errors[$key] = $rule();
    }
}

if (isset($rules['picture'])) {
    $rule = $rules['picture'];
    $errors['picture'] = $rule();
}
$errors = array_filter($errors);

$sql_selected_category = "SELECT id FROM category WHERE name ='" . $_POST['category'] . '\'';
$selected_category_query = mysqli_query($con, $sql_selected_category);
$selected_category = mysqli_fetch_array($selected_category_query, MYSQLI_ASSOC);
$category = $selected_category['id'];

if (empty($errors)) {
    $safe_name = mysqli_real_escape_string($con, $_POST['name']);
    $safe_category = intval($category);
    $safe_description = mysqli_real_escape_string($con, $_POST['description']);
    $safe_start_price = mysqli_real_escape_string($con, $_POST['start_price']);
    $safe_bid_step = mysqli_real_escape_string($con, $_POST['bid_step']);
    $safe_end_date = mysqli_real_escape_string($con, $_POST['end_date']);

    $sql_lot_insert = "INSERT INTO lot (name, category, description, start_price, bid_step, end_date)
        VALUES ('" . $safe_name . "', " . "'" . $safe_category . "', '" . $safe_description . "', '" .
        $safe_start_price . "', '" . $safe_bid_step . "', '" . $safe_end_date . "');";

    $sql_lot_insert_query = mysqli_query($con, $sql_lot_insert);
    $last_id = mysqli_insert_id($con);
    if (isset($_FILES['picture'])) {
        $file_name = $_FILES['picture']['name'];
        $file_path = __DIR__ . '/uploads/';
        $file_url = '/uploads/' . $file_name;

        move_uploaded_file($_FILES['picture']['tmp_name'], $file_path . $file_name);

        $sql_insert_lot_picture = "INSERT INTO lot_img (image_url, lot_id) VALUES ('" . $file_url . "' , '" . $last_id . "');";
        $sql_image_insert_query = mysqli_query($con, $sql_insert_lot_picture);
    }


    if (!empty($errors)) {
        $warning_about_errors = "Пожалуйста, исправьте ошибки в форме";
    }

    header('Location:/lot.php?id=3' . $last_id);
    die();
}

$title = 'Название страницы';
$user_name = 'Андрей Изюмов';

$main = include_template('add.php', [
    'categories' => $categoriesArray,
    'errors' => $errors,
    'warning_about_errors' => $warning_about_errors
]);

$layout = include_template('layout.php', [
    'user_name' => $user_name,
    'title' => $title,
    'main' => $main,
    'categories' => $categoriesArray,
    'con' => $con
]);

print($layout);
