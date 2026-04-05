<?php
session_start();
include 'db.php';

$full_name = $_POST['full_name'];
$address = $_POST['address'];
$sin = $_POST['sin'];
$role = $_POST['role'];
$hotel_id = $_POST['hotel_id'];

// Insert employee into DB
$query = "
INSERT INTO employee (full_name, address, sin, role, hotel_id)
VALUES ('$full_name', '$address', '$sin', '$role', $hotel_id)
RETURNING employee_id
";

$result = pg_query($conn, $query);

if (!$result) {
    die("Employee registration failed: " . pg_last_error($conn));
}

$row = pg_fetch_assoc($result);
$employee_id = $row['employee_id'];

echo "Employee registered successfully! Employee ID: $employee_id<br>";
echo "<a href='index.php'>Go back to home</a>";
?>