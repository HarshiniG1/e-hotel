<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>e-Hotels Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        button {
            padding: 15px 30px;
            margin: 20px;
            font-size: 16px;
            cursor: pointer;
        }
        h1 {
            color: #2F4F4F;
        }
    </style>
</head>
<body>

<h1>Welcome to e-Hotels</h1>

<p>Select an option:</p>

<!-- Customer Registration -->
<a href="register.php">
    <button>Customer Registration</button>
</a>

<!-- Employee Registration -->
<a href="employee_register.php">
    <button>Employee Registration</button>
</a>

<!-- Employee Login -->
<a href="employee_login.php">
    <button>Employee Login</button>
</a>

</body>
</html>