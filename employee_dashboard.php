<?php
session_start();
include 'db.php';

if (!isset($_SESSION['employee_id'])) {
    die("Access denied. <a href='employee_login.php'>Login</a>");
}

$employee_name = $_SESSION['employee_name'];
$hotel_id = $_SESSION['hotel_id'];
$is_manager = $_SESSION['is_manager'];
?>

<!DOCTYPE html>
<html>
<body>

<h2>Welcome, <?php echo htmlspecialchars($employee_name); ?>!</h2>

<?php if ($is_manager): ?>
    <h3>Manager Options</h3>
    <p>You can insert/update/delete info about:</p>
    <ul>
        <li>Customers</li>
        <li>Employees</li>
        <li>Hotels</li>
        <li>Rooms</li>
    </ul>
    <a href="manager_panel.php"><button>Go to Manager Panel</button></a>
<?php else: ?>
    <h3>Employee Options</h3>
    <p>You can check-in customers or directly rent rooms.</p>
<?php endif; ?>

<h3>Check-in / Rent Room</h3>
<form action="check_in_process.php" method="POST">
    Customer ID (leave blank for new customer): <input type="number" name="customer_id"><br><br>
    Room ID: <input type="number" name="room_id" required><br><br>
    Start Date: <input type="date" name="start" required><br><br>
    End Date: <input type="date" name="end" required><br><br>
    <button type="submit">Check-in / Rent Room</button>
</form>

<br>
<a href="employee_logout.php">Logout</a>

</body>
</html>