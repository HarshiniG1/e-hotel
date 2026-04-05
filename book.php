<?php
session_start();
include 'db.php';

if (!isset($_SESSION['customer_id'])) {
    die("Please login first.");
}

$customer_id = $_SESSION['customer_id'];
$room_id = $_POST['room_id'];
$start = $_POST['start'];
$end = $_POST['end'];

// Get hotel_id
$hotel_query = "SELECT hotel_id FROM room WHERE room_id = $room_id";
$hotel_result = pg_query($conn, $hotel_query);

if (!$hotel_result || pg_num_rows($hotel_result) == 0) {
    die("Invalid room.");
}

$row = pg_fetch_assoc($hotel_result);
$hotel_id = $row['hotel_id'];

// Insert booking
$query = "
INSERT INTO booking (room_id, customer_id, hotel_id, start_date, end_date)
VALUES ($room_id, $customer_id, $hotel_id, '$start', '$end')
";

$result = pg_query($conn, $query);

if (!$result) {
    die("Booking failed: " . pg_last_error($conn));
}

echo "Booking successful!";
echo "<br><a href='search.php'>Back</a>";
?>