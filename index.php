<?php
session_start();

if (isset($_POST['login'])) {
    $user = $_POST['user'];
    $_SESSION['user'] = $user;
    header('Location: index.php');
    exit();
}

if (isset($_SESSION['user_name'])) {
    echo "<p>now you login as: " . htmlspecialchars($_SESSION['user_name']) . "</p>";
    echo "<a href='history.php'>look at the order history</a>";
}

require('database.php');
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

    <p><a href="cart.php">the shopping cart</a></p>
    <p><a href="login.php">log out</a></p>
    <form action="search.php" method="get">
        <input type="text" name="search" placeholder="search the product by name or description">
        <input type="submit" value="search">
    </form>

    <table>
        <tr>
            <th>name</th>
            <th>description</th>
            <th>price</th>
            <th>stock</th>
            <th>action</th>
        </tr>
        <?php foreach ($products as $product) : ?>
            <tr>
                <td><?php echo htmlspecialchars($product['name']); ?></td>
                <td><?php echo htmlspecialchars($product['description']); ?></td>
                <td><?php echo htmlspecialchars($product['price']); ?></td>
                <td><?php echo htmlspecialchars($product['stock_quantity']); ?></td>
                <td>
                    <form action="add_to_cart.php" method="post">
                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                        <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>">
                        <input type="submit" value="add to cart">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
