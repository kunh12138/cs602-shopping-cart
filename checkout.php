<?php
session_start();

require('database.php');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    echo "<p>didnt login or cart is empty</p>";
    exit;
}

$user_id = $_SESSION['user_id'];
$totalPrice = 0;
$db->beginTransaction();
try {
    $orderQuery = "INSERT INTO orders (customer_id, total_price) VALUES (:customer_id, :total_price)";
    $orderStmt = $db->prepare($orderQuery);
    $orderStmt->execute([':customer_id' => $user_id, ':total_price' => 0]);
    $orderId = $db->lastInsertId();

    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $productQuery = 'SELECT name, price, stock_quantity FROM products WHERE product_id = :product_id';
        $productStmt = $db->prepare($productQuery);
        $productStmt->bindValue(':product_id', $product_id);
        $productStmt->execute();
        $product = $productStmt->fetch();
        $productStmt->closeCursor();
        
        if ($product && $quantity <= $product['stock_quantity']) {
            $price = $product['price'];
            $subtotal = $quantity * $price;
            $totalPrice += $subtotal;
            $newStock = $product['stock_quantity'] - $quantity;
            $updateQuery = 'UPDATE products SET stock_quantity = :new_stock WHERE product_id = :product_id';
            $updateStmt = $db->prepare($updateQuery);
            $updateStmt->execute([':new_stock' => $newStock, ':product_id' => $product_id]);
            $detailQuery = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)";
            $detailStmt = $db->prepare($detailQuery);
            $detailStmt->execute([':order_id' => $orderId, ':product_id' => $product_id, ':quantity' => $quantity, ':price' => $price]);
        } 
        else {
            throw new Exception("not enough product");
        }
    }
    $updateOrderQuery = "UPDATE orders SET total_price = :total_price WHERE order_id = :order_id";
    $updateOrderStmt = $db->prepare($updateOrderQuery);
    $updateOrderStmt->execute([':total_price' => $totalPrice, ':order_id' => $orderId]);

    $db->commit();

    $_SESSION['cart'] = [];
    echo "<p>success,the order id is : $orderId</p>";
    echo "<a href='index.php'>back to main page</a>";
} 
catch (Exception $e) {
    $db->rollBack();
    echo "<p>wrong when creat order" . $e->getMessage() . "</p>";
}
?>
