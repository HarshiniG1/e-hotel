<?php
session_start();
include 'db.php';

// VIEW 1: Available rooms per area
$available_query = "SELECT * FROM available_rooms_per_area";
$available_result = pg_query($conn, $available_query);

// VIEW 2: Hotel total capacity
$capacity_query = "SELECT * FROM hotel_total_capacity";
$capacity_result = pg_query($conn, $capacity_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>e-Hotels Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('hotel2.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            margin: 0;
            padding: 40px;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 35px;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.25);
            backdrop-filter: blur(6px);
        }

        h1 {
            text-align: center;
            color: #0d4d63;
            margin-bottom: 10px;
        }

        h2 {
            color: #0d4d63;
            margin-top: 40px;
            margin-bottom: 15px;
            border-bottom: 2px solid #1ba6a6;
            padding-bottom: 8px;
        }

        p {
            font-size: 16px;
            color: #444;
        }

        .button-group {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 30px;
        }

        button {
            background: linear-gradient(135deg, #1ba6a6, #0d4d63);
            color: white;
            border: none;
            padding: 12px 20px;
            margin: 8px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 15px;
            font-weight: bold;
            transition: 0.3s;
        }

        button:hover {
            transform: translateY(-2px);
            opacity: 0.95;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: rgba(255,255,255,0.95);
            border-radius: 15px;
            overflow: hidden;
        }

        th {
            background-color: #0d4d63;
            color: white;
            padding: 14px;
            text-align: center;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #f4f9fb;
        }

        tr:hover {
            background-color: #e2f4f7;
        }

        .logged-in-box {
            background: rgba(27, 166, 166, 0.1);
            border-left: 5px solid #1ba6a6;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 25px;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container">

    <h1>Welcome to e-Hotels</h1>

    <?php if (!isset($_SESSION['customer_id'])): ?>

        <div class="logged-in-box">
            <p>You are not logged in.</p>
        </div>

        <div class="button-group">
            <a href="register.php"><button>Customer Registration</button></a>
            <a href="customer_login.php"><button>Customer Login</button></a>
            <a href="employee_register.php"><button>Employee Registration</button></a>
            <a href="employee_login.php"><button>Employee Login</button></a>
        </div>

    <?php else: ?>

        <div class="logged-in-box">
            <p>Logged in as Customer ID: <?php echo $_SESSION['customer_id']; ?></p>
            <a href="logout_customer.php"><button>Logout</button></a>
        </div>

        <div class="button-group">
            <a href="search.php"><button>Search Rooms</button></a>
        </div>

    <?php endif; ?>

    <h2>Available Rooms Per Area</h2>

    <table>
        <tr>
            <th>Area</th>
            <th>Available Rooms</th>
        </tr>

        <?php while ($row = pg_fetch_assoc($available_result)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['address']); ?></td>
                <td><?php echo htmlspecialchars($row['available_rooms']); ?></td>
            </tr>
        <?php } ?>
    </table>

    <h2>Hotel Total Rooms</h2>

    <table>
        <tr>
            <th>Hotel Name</th>
            <th>Total Rooms</th>
        </tr>

        <?php while ($row = pg_fetch_assoc($capacity_result)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['hotel_name']); ?></td>
                <td><?php echo htmlspecialchars($row['total_rooms']); ?></td>
            </tr>
        <?php } ?>
    </table>

</div>

</body>
</html>