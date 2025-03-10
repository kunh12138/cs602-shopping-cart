<?php
session_start();
require('database.php');

if (isset($_POST['login'])) {
    $username = $_POST['user']; 

    if ($username === 'admin') {
        $_SESSION['user_role'] = 'admin';
        header('Location: ./admin/admin.php');
    } 
    else {
        $query = "SELECT customer_id FROM customers WHERE name = :name";
        $statement = $db->prepare($query);
        $statement->bindValue(':name', $username);
        $statement->execute();
        $user = $statement->fetch();
        $statement->closeCursor();

        if ($user) {
            $_SESSION['user_role'] = 'user';
            $_SESSION['user_id'] = $user['customer_id']; 
            $_SESSION['user_name'] = $username;
            header('Location: index.php');
        } 
    }
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
</head>
<body>
    <h1>choose a user</h1>
    <form action="login.php" method="post">
        <input type="radio" id="lisa" name="user" value="lisa">
        <label for="lisa">lisa</label><br>
        <input type="radio" id="will" name="user" value="will">
        <label for="will">Will</label><br>
        <input type="radio" id="admin" name="user" value="admin">
        <label for="admin">admin</label><br>
        <input type="submit" name="login" value="login">
    </form>
</body>
</html>
