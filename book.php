<?php
session_start();
include 'db.php';

if (!isset($_SESSION['customer_id'])) {
    die("Please register/login first.");
}

$customer_id = $_SESSION['customer_id'];
$room_id = $_POST['room_id'];
$start = $_POST['start'];
$end = $_POST['end'];

$query = "
INSERT INTO booking (room_id, customer_id, start_date, end_date)
VALUES ($room_id, $customer_id, '$start', '$end')
";

$result = pg_query($conn, $query);

if (!$result) {
    die("Booking failed: " . pg_last_error($conn));
}

echo "Room booked successfully!";
echo "<br><a href='index.php'>Back to search</a>";
?>