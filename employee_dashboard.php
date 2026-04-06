<?php
session_start();
include 'db.php';

if (!isset($_SESSION['employee_id'])) {
    die("Access denied. <a href='employee_login.php'>Login</a>");
}

$employee_id = $_SESSION['employee_id'];
$employee_name = $_SESSION['employee_name'];
$hotel_id = $_SESSION['hotel_id'];
$is_manager = $_SESSION['is_manager'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('hotel2.jpg');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            color: #333;
        }

        table {
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: rgba(255,255,255,0.9);
            width: 100%;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        table, th, td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
            text-align: left;
        }

        .dashboard-container {
            background-color: rgba(255,255,255,0.85);
            padding: 25px;
            border-radius: 15px;
            max-width: 1200px;
            margin: 30px auto;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }

        input, select {
            padding: 8px;
            margin: 5px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        h2, h3 {
            color: #222;
        }

        /* Pretty buttons */
        button, .button-link {
            background-color: #4CAF50; /* Green */
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 8px;
            transition: 0.3s;
            cursor: pointer;
        }

        button:hover, .button-link:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }

        .button-red {
            background-color: #f44336; /* Red */
        }

        .button-red:hover {
            background-color: #da190b;
        }

        .button-blue {
            background-color: #008CBA; /* Blue */
        }

        .button-blue:hover {
            background-color: #007bb5;
        }

        form {
            display: inline-block;
        }
    </style>
</head>
<body>
<div class="dashboard-container">

    <h2>Welcome, <?php echo htmlspecialchars($employee_name); ?>!</h2>

    <?php if ($is_manager): ?>
        <h3>Manager Panel</h3>
        <p>You can insert, update, and delete information about:</p>
        <ul>
            <li>Customers</li>
            <li>Employees</li>
            <li>Hotels</li>
            <li>Rooms</li>
        </ul>
        <a href="manage_customers.php" class="button-link">Manage Customers</a>
        <a href="manage_employees.php" class="button-link">Manage Employees</a>
        <a href="manage_hotels.php" class="button-link">Manage Hotels</a>
        <a href="manage_rooms.php" class="button-link">Manage Rooms</a>
    <?php else: ?>
        <h3>Employee Options</h3>
        <p>You can convert bookings to rentings, create direct rentings for walk-in customers, and update payment status.</p>
    <?php endif; ?>

    <h3>Existing Bookings</h3>
    <?php
    $query_bookings = "
        SELECT b.booking_id, c.customer_id, c.full_name AS customer_name, r.room_id, b.start_date, b.end_date, b.status
        FROM booking b
        JOIN customer c ON c.customer_id = b.customer_id
        JOIN room r ON r.room_id = b.room_id
        WHERE b.hotel_id = $1
        ORDER BY b.start_date ASC
    ";
    $result_bookings = pg_query_params($conn, $query_bookings, array($hotel_id));

    if ($result_bookings && pg_num_rows($result_bookings) > 0) {
        echo "<table>";
        echo "<tr>
                <th>Booking ID</th>
                <th>Customer Name</th>
                <th>Room ID</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
                <th>Action</th>
              </tr>";
        while ($row = pg_fetch_assoc($result_bookings)) {
            echo "<tr>";
            echo "<td>{$row['booking_id']}</td>";
            echo "<td>" . htmlspecialchars($row['customer_name']) . "</td>";
            echo "<td>{$row['room_id']}</td>";
            echo "<td>{$row['start_date']}</td>";
            echo "<td>{$row['end_date']}</td>";
            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
            echo "<td>
                    <form action='check_in_process.php' method='POST'>
                        <input type='hidden' name='booking_id' value='{$row['booking_id']}'>
                        <input type='hidden' name='customer_id' value='{$row['customer_id']}'>
                        <input type='hidden' name='room_id' value='{$row['room_id']}'>
                        <button type='submit' class='button-blue'>Convert to Renting</button>
                    </form>
                  </td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No bookings available for this hotel.</p>";
    }
    ?>

    <h3>Existing Rentings (Update Payment Status)</h3>
    <?php
    $query_rentings = "
        SELECT re.renting_id, c.full_name AS customer_name, r.room_id, re.start_date, re.end_date, re.payment_status
        FROM renting re
        JOIN customer c ON c.customer_id = re.customer_id
        JOIN room r ON r.room_id = re.room_id
        WHERE re.hotel_id = $1
        ORDER BY re.start_date ASC
    ";
    $result_rentings = pg_query_params($conn, $query_rentings, array($hotel_id));

    if ($result_rentings && pg_num_rows($result_rentings) > 0) {
        echo "<table>";
        echo "<tr>
                <th>Renting ID</th>
                <th>Customer Name</th>
                <th>Room ID</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Payment Status</th>
                <th>Action</th>
              </tr>";
        while ($row = pg_fetch_assoc($result_rentings)) {
            echo "<tr>";
            echo "<td>{$row['renting_id']}</td>";
            echo "<td>" . htmlspecialchars($row['customer_name']) . "</td>";
            echo "<td>{$row['room_id']}</td>";
            echo "<td>{$row['start_date']}</td>";
            echo "<td>{$row['end_date']}</td>";
            echo "<td>" . ($row['payment_status'] == 't' ? 'Paid' : 'Not Paid') . "</td>";
            echo "<td>
                    <form action='update_payment.php' method='POST'>
                        <input type='hidden' name='renting_id' value='{$row['renting_id']}'>
                        <select name='payment_status'>
                            <option value='0' " . ($row['payment_status'] != 't' ? 'selected' : '') . ">Not Paid</option>
                            <option value='1' " . ($row['payment_status'] == 't' ? 'selected' : '') . ">Paid</option>
                        </select>
                        <button type='submit' class='button-blue'>Update</button>
                    </form>
                  </td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No rentings available for this hotel.</p>";
    }
    ?>

    <h3>Direct Renting for Walk-in Customers</h3>
    <form action="check_in_process.php" method="POST">
        <label>Customer ID:</label><br>
        <input type="number" name="customer_id" required><br><br>

        <label>Room ID:</label><br>
        <input type="number" name="room_id" required><br><br>

        <label>Start Date:</label><br>
        <input type="date" name="start" required><br><br>

        <label>End Date:</label><br>
        <input type="date" name="end" required><br><br>

        <label>Payment Status:</label><br>
        <select name="payment_status">
            <option value="0" selected>Not Paid</option>
            <option value="1">Paid</option>
        </select><br><br>

        <button type="submit" class="button-green">Create Renting</button>
    </form>

    <br><br>
    <a href="employee_logout.php" class="button-red">Logout</a>

</div>
</body>
</html>