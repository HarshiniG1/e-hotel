<?php
session_start();
include 'db.php';

if (!isset($_POST['sin'])) {
    die("SIN is required.");
}

$sin = trim($_POST['sin']); // remove spaces

// Fetch employee by SIN (case-insensitive, trim spaces)
$query = "SELECT * FROM employee WHERE TRIM(sin) ILIKE TRIM('$sin') LIMIT 1";
$result = pg_query($conn, $query);

if (!$result) {
    die("Query failed: " . pg_last_error($conn));
}

if (pg_num_rows($result) == 0) {
    die("Employee not found. <a href='employee_login.php'>Try again</a>");
}

$employee = pg_fetch_assoc($result);

// Store employee info in session
$_SESSION['employee_id'] = $employee['employee_id'];
$_SESSION['employee_name'] = $employee['full_name'];
$_SESSION['hotel_id'] = $employee['hotel_id'];

// Check if the employee is the manager
$hotel_id = $employee['hotel_id'];
$employee_id = $employee['employee_id'];

$check_manager = "SELECT * FROM manages WHERE employee_id = $employee_id AND hotel_id = $hotel_id";
$res_manager = pg_query($conn, $check_manager);

if (!$res_manager) {
    die("Query failed: " . pg_last_error($conn));
}

$_SESSION['is_manager'] = (pg_num_rows($res_manager) > 0); // true if manager

// Redirect to employee dashboard
header("Location: employee_dashboard.php");
exit;
?>