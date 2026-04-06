<?php
session_start();
include 'db.php';

if (!isset($_POST['sin'])) {
    die("SIN is required.");
}

$sin = trim($_POST['sin']);

// Fetch employee by SIN (case-insensitive)
$query = "SELECT * FROM employee WHERE TRIM(sin) ILIKE TRIM($1) LIMIT 1";
$result = pg_query_params($conn, $query, [$sin]);

if (!$result) die("Query failed: " . pg_last_error($conn));
if (pg_num_rows($result) == 0) die("Employee not found. <a href='employee_login.php'>Try again</a>");

$employee = pg_fetch_assoc($result);

// Store info in session
$_SESSION['employee_id'] = $employee['employee_id'];
$_SESSION['employee_name'] = $employee['full_name'];
$_SESSION['hotel_id'] = $employee['hotel_id'];

// Detect if manager
$_SESSION['is_manager'] = ($employee['role'] === 'manager');

// Redirect to dashboard
header("Location: employee_dashboard.php");
exit;
?>