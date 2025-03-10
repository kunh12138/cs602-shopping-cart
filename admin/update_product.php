<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "<p>cant access, login as admin。</p>";
    exit;
}
require('C:/xampp/htdocs/cart/database.php');
if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    $query = 'SELECT * FROM products WHERE product_id = :product_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':product_id', $productId);
    $statement->execute();
    $product = $statement->fetch();
    $statement->closeCursor();
   
} 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $updateQuery = "UPDATE products SET name = :name, description = :description, 
    price = :price, stock_quantity = :stock_quantity WHERE product_id = :product_id";
    $updateStmt = $db->prepare($updateQuery);
    $updateStmt->bindValue(':name', $name);
    $updateStmt->bindValue(':description', $description);
    $updateStmt->bindValue(':price', $price);
    $updateStmt->bindValue(':stock_quantity', $stock_quantity);
    $updateStmt->bindValue(':product_id', $productId);
    $updateStmt->execute();
    $updateStmt->closeCursor();
    echo "<p>update success</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
</head>
<body>
    <h1>update</h1>
    <p><a href="admin.php">back to main page</a></p>
    <form method="post">
        name: <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required><br>
        description: <textarea name="description"><?php echo htmlspecialchars($product['description']); ?></textarea><br>
        price: <input type="number" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" step="0.01" required><br>
        stock: <input type="number" name="stock_quantity" value="<?php echo htmlspecialchars($product['stock_quantity']); ?>" required><br>
        <input type="submit" value="update">
    </form>
</body>
</html>
