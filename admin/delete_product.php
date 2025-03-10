<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "<p>cant access, login as admin</p>";
    exit;
}
require('C:/xampp/htdocs/cart/database.php');
if (isset($_GET['id'])) {
    $productId = $_GET['id'];


    
    if (isset($_POST['confirm_delete'])) {

        $deleteQuery = "DELETE FROM products WHERE product_id = :product_id";
        $deleteStmt = $db->prepare($deleteQuery);
        $deleteStmt->bindValue(':product_id', $productId);
        $result = $deleteStmt->execute();
        $deleteStmt->closeCursor();

        if ($result) {
            echo "<p>success to delete</p>";
        } 
        echo '<a href="admin.php">back to main page</a>';
        exit;
    }
} 
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
</head>
<body>
    <h1>delete product</h1>
    <p>are you sure to delete the product?</p>
    <form method="post">
        <input type="hidden" name="confirm_delete" value="true">
        <input type="submit" value="yes">
    </form>
    <a href="admin.php">no</a>
</body>
</html>
