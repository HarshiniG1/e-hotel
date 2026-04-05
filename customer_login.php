<?php
session_start();
?>

<!DOCTYPE html>
<html>
<body>

<h2>Customer Login</h2>

<form action="customer_login_process.php" method="POST">
    Customer ID: <input type="number" name="customer_id" required><br><br>
    <button type="submit">Login</button>
</form>

<br>
<a href="index.php">Back to Home</a>

</body>
</html>