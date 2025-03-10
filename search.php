<?php
require('database.php');
$searchTerm = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);
try {
    $query = "SELECT * FROM products WHERE name LIKE :searchTerm OR description LIKE :searchTerm ORDER BY name";
    $statement = $db->prepare($query);
    $searchTerm = "%" . $searchTerm . "%";
    $statement->bindValue(':searchTerm', $searchTerm);
    $statement->execute();
    $products = $statement->fetchAll();
    $statement->closeCursor();
} 
catch (PDOException $e) {
    $error_message = $e->getMessage();
    echo "<p>Error executing query: $error_message</p>";
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
    <h1>search results</h1>

    <?php if (empty($products)) : ?>
        <p>cant find"<?php echo htmlspecialchars($searchTerm); ?></p>
    <?php else : ?>
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
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    
    <a href="index.php">back to product list</a>
</body>
</html>
