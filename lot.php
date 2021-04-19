<?php
require_once('helpers.php');
$con = mysqli_connect ("localhost", "root", "root", "yeticave");
mysqli_set_charset($con, "utf8");

function getExpiryTime($date) {
    $curDate = date_create("now");
    $endDate = date_create($date);
    $diffDate = date_diff($curDate, $endDate);
    $countHours = $diffDate->h + $diffDate->d * 24;
    $countMinutes = $diffDate->i;
    return [$countHours, $countMinutes];
}

$params = $_GET;
$idLot = (int)$params['id'];

$paramIdList = "select * from lot where id = $idLot";
$countRowsObj = mysqli_query($con, $paramIdList);
$countRows = mysqli_num_rows ($countRowsObj);

$sqlCategoriesList = "select * from category";
$sqlCategoriesObj = mysqli_query($con, $sqlCategoriesList);
$categoriesArray = mysqli_fetch_all($sqlCategoriesObj, MYSQLI_ASSOC);

$sqlCategoryName = "select c.name from category c join lot l on l.category_id = c.id where l.id = $idLot";
$sqlCategoryNameObj = mysqli_query($con, $sqlCategoryName);
$categoryName = mysqli_fetch_assoc($sqlCategoryNameObj);

$sqlCurrentPrice = "select IF(max(amount) IS NULL, start_price, max(amount)) AS 'current_price'
FROM  (SELECT l.*, b.amount from lot l
        LEFT JOIN bid b on l.id = b.lot_id
        WHERE l.end_date > SYSDATE()
        and l.id = $idLot
ORDER BY l.create_date DESC, b.date DESC) tbl
GROUP BY id";
$sqlCurrentPriceObj = mysqli_query($con, $sqlCurrentPrice);
$currentPrice = mysqli_fetch_assoc($sqlCurrentPriceObj);

$sqlLotInfo = "select * from lot where id = $idLot";
$sqlLotInfoObj = mysqli_query($con, $sqlLotInfo);
$lotInfo = mysqli_fetch_assoc($sqlLotInfoObj);

$title = 'Название страницы';
$user_name = 'Андрей Изюмов';

$main = include_template('lot.php', [
    'idLot' => $idLot,
    'categoryName' => $categoryName['name'],
    'dateDiff' => getExpiryTime($lotInfo['end_date']),
    'currentPrice' => $currentPrice['current_price'],
    'minBid' => $currentPrice['current_price'] + $lotInfo['bid_step'],
    'lotName' => $lotInfo['name'],
    'categories' => $categoriesArray,
]);


$page404 = include_template('404.php');
$layout = include_template('layout.php', [
'user_name' => $user_name,
'title' => $title,
'main' => $main,
'categories' => $categoriesArray,
'con' => $con
]);

if ($countRows > 0) {
    print($layout);
} else {
    var_dump(http_response_code(404));
    var_dump(http_response_code());
    print($page404);
}
