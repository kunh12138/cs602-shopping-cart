<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "<p>cant access, login as admin</p>";
    exit;
}
require('C:/xampp/htdocs/cart/database.php');
$customer_id = $_GET['customer_id'];
$query = "SELECT orders.order_id, orders.order_date, orders.total_price FROM orders WHERE orders.customer_id = :customer_id ORDER BY orders.order_date DESC";
$statement = $db->prepare($query);
$statement->bindValue(':customer_id', $customer_id);
$statement->execute();
$orders = $statement->fetchAll();
$statement->closeCursor();
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        table, th, td {
            border-collapse: collapse;
            border: 1px solid black;
            text-align: left;
        }
        .order-details {
            margin-top: 10px;
            margin-bottom: 20px;
        }
    </style>
     <meta charset="UTF-8">
</head>
<body>
    <h1>customer order history</h1>
    <?php foreach ($orders as $order): ?>
        <table>
            <tr>
                <th>order ID</th>
                <th>order date</th>
                <th>order detail</th>
                <th>total price</th>
                <th>action</th>
                
            </tr>
            <tr>
                <td><?php echo $order['order_id']; ?></td>
                <td><?php echo $order['order_date']; ?></td>
                <td>
                <ul>
                <?php
                $detailQuery = "SELECT od.quantity, od.price, p.name FROM order_details od JOIN products p ON od.product_id = p.product_id WHERE od.order_id = :order_id";
                $detailStatement = $db->prepare($detailQuery);
                $detailStatement->bindValue(':order_id', $order['order_id']);
                $detailStatement->execute();
                $orderDetails = $detailStatement->fetchAll();
                $detailStatement->closeCursor();
                foreach ($orderDetails as $detail) {
                    echo "<li>" . htmlspecialchars($detail['name']) . " - number: " . $detail['quantity'] . ", price: $" . $detail['price'] . "</li>";
                }
                ?>
            </ul>
                </td>
                <td><?php echo $order['total_price']; ?></td>
                <td>
                    <a href="update_order.php?order_id=<?php echo $order['order_id']; ?>">update</a> |
                    <a href="delete_order.php?order_id=<?php echo $order['order_id']; ?>">delete</a>
                </td>
            </tr>
        </table>
    <?php endforeach; ?>
    <a href="customer_list.php">back to customer list</a>
</body>
</html>
