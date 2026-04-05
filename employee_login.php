<?php
session_start();
?>

<!DOCTYPE html>
<html>
<body>

<h2>Employee Login</h2>

<form action="employee_login_process.php" method="POST">
    SIN: <input type="text" name="sin" required><br><br>
    <button type="submit">Login</button>
</form>

</body>
</html>