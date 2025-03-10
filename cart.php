<?php
session_start();

if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    echo "<p>empty cart</p>";
    exit;
}
require('database.php'); 
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
</head>
<body>
    <h1>shopping cart</h1>
    <table>
        <tr>
            <th>name</th>
            <th>stock</th>
        </tr>
        <?php
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $query = 'SELECT name FROM products WHERE product_id = :product_id';
            $statement = $db->prepare($query);
            $statement->bindValue(':product_id', $product_id);
            $statement->execute();
            $product = $statement->fetch();
            $statement->closeCursor();
            
            if ($product) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($product['name']) . "</td>";
                echo "<td>" . htmlspecialchars($quantity) . "</td>";
                echo "</tr>";
            }
        }
        ?>
    </table>
    <form action="checkout.php" method="post">
        <input type="submit" value="check out">
    </form>
</body>
</html>
