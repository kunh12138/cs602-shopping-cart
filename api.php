<?php
header("Content-Type: application/json"); 
require('C:/xampp/htdocs/cart/database.php'); 

$requestType = isset($_GET['type']) ? $_GET['type'] : '';
$searchName = isset($_GET['name']) ? $_GET['name'] : '';
$minPrice = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
$maxPrice = isset($_GET['max_price']) ? floatval($_GET['max_price']) : PHP_INT_MAX;
$sql = "SELECT * FROM products";
$params = [];

if ($requestType == 'search_name' && !empty($searchName)) {
    $sql .= " WHERE name LIKE :name";
    $params[':name'] = "%$searchName%";
} elseif ($requestType == 'price_range') {
    $sql .= " WHERE price BETWEEN :minPrice AND :maxPrice";
    $params = [':minPrice' => $minPrice, ':maxPrice' => $maxPrice];
}

$stmt = $db->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
$acceptHeader = $_SERVER['HTTP_ACCEPT'];
if (strpos($acceptHeader, 'application/xml') !== false) {
    header("Content-Type: application/xml");
    $xml = new SimpleXMLElement('<products/>');
    foreach ($products as $product) {
        $productNode = $xml->addChild('product');
        foreach ($product as $key => $value) {
            $productNode->addChild($key, $value);
        }
    }
    echo $xml->asXML();
} else {
    header("Content-Type: application/json");
    echo json_encode($products);
}
?>
