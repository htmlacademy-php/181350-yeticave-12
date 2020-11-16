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
function getExpiryTime($date) {
    $curDate = date_create("now");
    $endDate = date_create($date);
    $diffDate = date_diff($curDate, $endDate);
    $countHours = $diffDate->h + $diffDate->d * 24;
    $countMinutes = $diffDate->i;
    return [$countHours, $countMinutes];
}

$params = $_GET;
$idLot = $params['id'];

$sqlCategoriesList = "select * from category";
$categoriesArray = mysqli_fetch_all(mysqli_query($con, $sqlCategoriesList), MYSQLI_ASSOC);

$sqlCategoryName = "select c.name from category c join lot l on l.category_id = c.id where l.id = $idLot";
$categoryName = mysqli_fetch_assoc(mysqli_query($con, $sqlCategoryName));

$sqlCurrentPrice = "select IF(max(amount) IS NULL, start_price, max(amount)) AS 'current_price'
FROM  (SELECT l.*, b.amount from lot l
        LEFT JOIN bid b on l.id = b.lot_id
        WHERE l.end_date > SYSDATE()
        and l.id = $idLot
ORDER BY l.create_date DESC, b.date DESC) tbl
GROUP BY id";
$currentPrice = mysqli_fetch_assoc(mysqli_query($con, $sqlCurrentPrice));

$sqlLotInfo = "select * from lot where id = $idLot";
$lotInfo = mysqli_fetch_assoc(mysqli_query($con, $sqlLotInfo));

$title = 'Название страницы';
$user_name = 'Андрей Изюмов';

$main = include_template('lot.php', [
    'idLot' => $idLot,
    'categoryName' => $categoryName['name'],
    'dateDiff' => getExpiryTime($lotInfo['end_date']),
    'currentPrice' => $currentPrice['current_price'],
    'minBid' => $currentPrice['current_price'] + $lotInfo['bid_step'],
    'lotName' => $lotInfo['name'],
]);

$layout = include_template('layout.php', [
'user_name' => $user_name,
'title' => $title,
'main' => $main,
'categories' => $categoriesArray,
]);

print($layout);
