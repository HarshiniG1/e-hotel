<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('hotel2.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-box {
            background: rgba(255,255,255,0.9);
            padding: 40px;
            border-radius: 20px;
            width: 400px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            text-align: center;
            backdrop-filter: blur(6px);
        }

        h2 {
            color: #0d4d63;
            margin-bottom: 25px;
        }

        label {
            display: block;
            text-align: left;
            margin-bottom: 8px;
            color: #444;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-sizing: border-box;
            font-size: 14px;
        }

        button {
            background: linear-gradient(135deg, #1ba6a6, #0d4d63);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 10px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
            transition: 0.3s;
        }

        button:hover {
            transform: translateY(-2px);
            opacity: 0.95;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            color: #0d4d63;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Customer Login</h2>

    <form action="customer_login_process.php" method="POST">
        <label>Customer ID</label>
        <input type="number" name="customer_id" required>

        <button type="submit">Login</button>
    </form>

    <a href="index.php">Back to Home</a>
</div>

</body>
</html>