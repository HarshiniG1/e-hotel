<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('hotel2.jpg'); /* optional background */
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: rgba(255,255,255,0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            text-align: center;
            width: 350px;
        }

        h2 {
            color: #222;
            margin-bottom: 20px;
        }

        input {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #4CAF50; /* green */
            color: white;
            padding: 10px 25px;
            margin-top: 15px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }

        a {
            display: block;
            margin-top: 15px;
            color: #008CBA;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="login-container">
    <h2>Employee Login</h2>
    <form action="employee_login_process.php" method="POST">
        SIN: <br>
        <input type="text" name="sin" required><br>
        <button type="submit">Login</button>
    </form>
    <a href="index.php">Back to Home</a>
</div>
</body>
</html>