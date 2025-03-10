<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "<p>cant access, login as admin</p>";
    exit;
}

require('C:/xampp/htdocs/cart/database.php');
$query = "SELECT * FROM customers ORDER BY name";
$statement = $db->prepare($query);
$statement->execute();
$customers = $statement->fetchAll();
$statement->closeCursor();
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
    <meta charset="UTF-8">
</head>
<body>
    <h1>customer list</h1>
    <p><a href="admin.php">back to main page</a></p>
    <table>
        <tr>
            <th>customer ID</th>
            <th>name</th>
            <th>action</th>
        </tr>
        <?php foreach ($customers as $customer): ?>
        <tr>
            <td><?php echo $customer['customer_id']; ?></td>
            <td><?php echo htmlspecialchars($customer['name']); ?></td>
            <td>
                <a href="customer_orders.php?customer_id=<?php echo $customer['customer_id']; ?>">order history</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
