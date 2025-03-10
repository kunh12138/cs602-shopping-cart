<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "<p>cant access, login as admin</p>";
    exit;
}

require('C:/xampp/htdocs/cart/database.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $query = "INSERT INTO products (name, description, price, stock_quantity) VALUES (:name, :description, :price, :stock_quantity)";
    $statement = $db->prepare($query);
    $statement->bindValue(':name', $name);
    $statement->bindValue(':description', $description);
    $statement->bindValue(':price', $price);
    $statement->bindValue(':stock_quantity', $stock_quantity);
    $statement->execute();
    $statement->closeCursor();
    $message = "success to add";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
    <h1>add new product</h1>
    <p><a href="admin.php">back to main page</a></p>
    <?php if (!empty($message)): ?>
    <p><?php echo $message; ?></p>
    <?php endif; ?>
    
    <form action="add_product.php" method="post">
        name: <input type="text" name="name" required><br>
        description: <textarea name="description"></textarea><br>
        price: <input type="number" name="price" step="0.01" required><br>
        stock: <input type="number" name="stock_quantity" required><br>
        <input type="submit" value="add product">
    </form>
</body>
</html>
