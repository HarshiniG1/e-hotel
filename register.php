<!DOCTYPE html>
<html>
<head>
    <title>Customer Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('hotel2.jpg');
            background-size: cover;
            background-position: center;
            color: #333;
            padding: 30px;
        }

        .container {
            background-color: rgba(255,255,255,0.95);
            padding: 25px;
            border-radius: 12px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #222;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px 10px;
            margin: 8px 0 16px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 10px 0;
            margin-top: 10px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #008CBA;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Customer Registration</h2>

    <form action="register_process.php" method="POST">
        Full Name: <input type="text" name="name" required><br>
        Address: <input type="text" name="address" required><br>
        ID Type: <input type="text" name="id_type" required><br>

        <button type="submit">Register</button>
    </form>

    <a href="index.php">Back to Home</a>
</div>
</body>
</html>