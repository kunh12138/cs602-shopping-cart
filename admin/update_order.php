<?php
session_start();
require('C:/xampp/htdocs/cart/database.php');

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "<p>cant access, login as admin</p>";
    exit;
}
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : $_POST['order_id'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $total_price = $_POST['total_price'];
    $query = "UPDATE orders SET total_price = :total_price WHERE order_id = :order_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':total_price', $total_price);
    $statement->bindValue(':order_id', $order_id);
    $result = $statement->execute();
    $statement->closeCursor();
    if ($result) {
        echo "<p>successful</p>";
    } 
    echo '<a href="customer_list.php">back to customer list</a>';
    exit;
} 
else {
    $query = "SELECT total_price FROM orders WHERE order_id = :order_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':order_id', $order_id);
    $statement->execute();
    $order = $statement->fetch();
    $statement->closeCursor();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
</head>
<body>
    <h1>update order</h1>
    <form action="update_order.php" method="post">
        <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">
        new total price for discount: <input type="number" name="total_price" value="<?php echo htmlspecialchars($order['total_price']); ?>" step="0.01" required><br>
        <input type="submit" value="update order">
    </form>
    <a href="customer_list.php">cancel</a>
</body>
</html>
