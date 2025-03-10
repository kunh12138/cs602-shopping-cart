<?php
session_start();
require('C:/xampp/htdocs/cart/database.php');

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "<p>cant access, login as admin。</p>";
    exit;
}

$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : $_POST['order_id'];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_delete'])) {
    try {
        $db->beginTransaction();
        $deleteDetailsQuery = "DELETE FROM order_details WHERE order_id = :order_id";
        $statement = $db->prepare($deleteDetailsQuery);
        $statement->bindValue(':order_id', $order_id);
        $statement->execute();
        $deleteOrderQuery = "DELETE FROM orders WHERE order_id = :order_id";
        $statement = $db->prepare($deleteOrderQuery);
        $statement->bindValue(':order_id', $order_id);
        $statement->execute();
        $db->commit();

        echo "<p>delete success</p>";
    } 
    catch (Exception $e) {
        $db->rollBack();
        echo "<p>something wrong when delete:" . $e->getMessage() . "</p>";
    }

    echo '<a href="customer_list.php">back to customer list</a>';
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
</head>
<body>
    <h1>delete order</h1>
    <p>are you sure to delete the order?</p>
    <form action="delete_order.php" method="post">
        <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">
        <input type="hidden" name="confirm_delete" value="true">
        <input type="submit" value="confirm delete">
    </form>
    <a href="customer_list.php">cancel</a>
</body>
</html>
