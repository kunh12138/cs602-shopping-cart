<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "<p>cant access, login as admin</p>";
    exit;
}

require('C:/xampp/htdocs/cart/database.php');
$products = [];
try {
    $query = 'SELECT * FROM products ORDER BY name';
    $statement = $db->prepare($query);
    $statement->execute();
    $products = $statement->fetchAll();
    $statement->closeCursor();
} 
catch (PDOException $e) {
    $error_message = $e->getMessage();
    echo "<p>Error connecting to database: $error_message</p>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
     <meta charset="UTF-8">
</head>
<body>
    <h1>product list</h1>
    <p><a href="add_product.php">add new product</a></p>
    <a href="customer_list.php" class="admin-link">look at the customer list</a>
    <table>
        <tr>
            <th>name</th>
            <th>description</th>
            <th>price</th>
            <th>stock</th>
        </tr>
        <?php foreach ($products as $product) : ?>
            <tr>
                <td><?php echo htmlspecialchars($product['name']); ?></td>
                <td><?php echo htmlspecialchars($product['description']); ?></td>
                <td><?php echo htmlspecialchars($product['price']); ?></td>
                <td><?php echo htmlspecialchars($product['stock_quantity']); ?></td>
                <td>
                    <a href="update_product.php?id=<?php echo $product['product_id']; ?>">update</a> |
                    <a href="delete_product.php?id=<?php echo $product['product_id']; ?>">delete</a>
                </td>
            </tr>
        <?php endforeach; ?>

    </table>
</body>
</html>
