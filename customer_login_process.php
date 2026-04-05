<?php
session_start();
include 'db.php';

$customer_id = $_POST['customer_id'];

// Check if customer exists
$query = "SELECT * FROM customer WHERE customer_id = $customer_id";
$result = pg_query($conn, $query);

if (!$result || pg_num_rows($result) == 0) {
    die("Customer not found.");
}

// Set session
$_SESSION['customer_id'] = $customer_id;

// Redirect
header("Location: index.php");
exit();
?>