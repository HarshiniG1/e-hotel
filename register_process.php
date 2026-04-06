<?php
session_start();
include 'db.php';

$name = $_POST['name'];
$address = $_POST['address'];
$id_type = $_POST['id_type'];

$query = "
INSERT INTO customer (full_name, address, id_type, registration_date)
VALUES ('$name', '$address', '$id_type', CURRENT_DATE)
RETURNING customer_id
";

$result = pg_query($conn, $query);

if (!$result) {
    die("Registration failed: " . pg_last_error($conn));
}

$row = pg_fetch_assoc($result);
$_SESSION['customer_id'] = $row['customer_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Successful</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('hotel2.jpg');
            background-size: cover;
            background-position: center;
            color: #333;
            padding: 50px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: rgba(255,255,255,0.95);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 25px rgba(0,0,0,0.25);
            text-align: center;
        }

        h1 {
            color: #0d4d63;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            color: #444;
            margin-bottom: 30px;
        }

        button {
            background: linear-gradient(135deg, #1ba6a6, #0d4d63);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: 0.3s;
            text-decoration: none;
        }

        button:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Registration Successful!</h1>
    <p>Welcome, <?php echo htmlspecialchars($name); ?>. Your Customer ID is <?php echo $row['customer_id']; ?>.</p>
    <a href="search.php"><button>Go to Search Rooms</button></a>
    <br><br>
    <a href="index.php"><button>Go to Dashboard</button></a>
</div>
</body>
</html>