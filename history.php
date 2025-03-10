<?php
session_start();
require('database.php');

if (!isset($_SESSION['user_id'])) {
    echo "<p>please login</p>";
    exit;
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM orders WHERE customer_id = :customer_id ORDER BY order_date DESC";
$statement = $db->prepare($query);
$statement->bindValue(':customer_id', $user_id);
$statement->execute();
$orders = $statement->fetchAll();
$statement->closeCursor();
?>

<!DOCTYPE html>
<html>
<head>
    <p><a href="index.php">back to main page</a></p>
    <meta charset="UTF-8">
</head>
<body>
    <h1>order history</h1>
    <?php if (empty($orders)): ?>
        <p>no order history</p>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <p>order ID:<?php echo $order['order_id']; ?></p>
            <p>order date：<?php echo $order['order_date']; ?></p>
            <p>total price:$<?php echo $order['total_price']; ?></p>
            <h3>order detail</h3>
            <ul>
                <?php
                $detailQuery = "SELECT od.quantity, od.price, p.name FROM order_details od JOIN products p ON od.product_id = p.product_id WHERE od.order_id = :order_id";
                $detailStatement = $db->prepare($detailQuery);
                $detailStatement->bindValue(':order_id', $order['order_id']);
                $detailStatement->execute();
                $orderDetails = $detailStatement->fetchAll();
                $detailStatement->closeCursor();
                
                foreach ($orderDetails as $detail) {
                    echo "<li>" . htmlspecialchars($detail['name']) . ", number: " . $detail['quantity'] . ", price: $" . $detail['price'] . "</li>";
                }
                ?>
            </ul>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
