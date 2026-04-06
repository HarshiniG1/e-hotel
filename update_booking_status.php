<?php
session_start();
include 'db.php';

if (!isset($_SESSION['employee_id'])) die("Access denied.");

$booking_id = intval($_POST['booking_id']);
$status = $_POST['status'];

$query = "UPDATE booking SET status=$1 WHERE booking_id=$2";
$res = pg_query_params($conn, $query, [$status, $booking_id]);

if ($res) {
    header("Location: employee_dashboard.php");
} else {
    die("Failed to update status: " . pg_last_error($conn));
}
?>