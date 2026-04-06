<?php
session_start();
include 'db.php';

if (!isset($_SESSION['employee_id'])) {
    die("Access denied. <a href='employee_login.php'>Login</a>");
}

$employee_id = $_SESSION['employee_id'];
$employee_name = $_SESSION['employee_name'];
$hotel_id = $_SESSION['hotel_id'];

// POST data
$booking_id = isset($_POST['booking_id']) ? intval($_POST['booking_id']) : null;
$customer_id = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : null;
$room_id = isset($_POST['room_id']) ? intval($_POST['room_id']) : null;
$start_date = isset($_POST['start']) ? $_POST['start'] : null;
$end_date = isset($_POST['end']) ? $_POST['end'] : null;

if (!$room_id) die("Room ID is required.");
if (!$customer_id) die("Customer ID is required.");

// Check room exists
$query_room = "SELECT * FROM room WHERE room_id = $1 AND hotel_id = $2";
$result_room = pg_query_params($conn, $query_room, [$room_id, $hotel_id]);
if (pg_num_rows($result_room) == 0) die("Invalid Room ID or it does not belong to this hotel.");

// If converting a booking
if ($booking_id) {
    // Update booking status first
    $update_status = "UPDATE booking SET status='checked-in' WHERE booking_id=$1";
    pg_query_params($conn, $update_status, [$booking_id]);

    // Get booking info
    $query_booking = "SELECT * FROM booking WHERE booking_id=$1";
    $res_booking = pg_query_params($conn, $query_booking, [$booking_id]);
    if (pg_num_rows($res_booking) == 0) die("Booking not found.");

    $booking = pg_fetch_assoc($res_booking);
    $start_date = $booking['start_date'];
    $end_date = $booking['end_date'];

    // Create renting
    $insert_renting = "INSERT INTO renting (customer_id, hotel_id, room_id, employee_id, start_date, end_date, payment_status)
                       VALUES ($1,$2,$3,$4,$5,$6,FALSE)";
    $res_renting = pg_query_params($conn, $insert_renting, [
        $booking['customer_id'],
        $booking['hotel_id'],
        $booking['room_id'],
        $employee_id,
        $start_date,
        $end_date
    ]);
    if (!$res_renting) die("Failed to create renting: " . pg_last_error($conn));

    // Delete booking (will trigger archive)
    $delete_booking = "DELETE FROM booking WHERE booking_id=$1";
    pg_query_params($conn, $delete_booking, [$booking_id]);

    echo "Booking converted into renting successfully!";
} else {
    // Direct renting
    if (!$start_date || !$end_date) die("Start and End dates are required.");

    $insert_renting = "INSERT INTO renting (customer_id, hotel_id, room_id, employee_id, start_date, end_date, payment_status)
                       VALUES ($1,$2,$3,$4,$5,$6,FALSE)";
    $res_renting = pg_query_params($conn, $insert_renting, [
        $customer_id,
        $hotel_id,
        $room_id,
        $employee_id,
        $start_date,
        $end_date
    ]);
    if (!$res_renting) die("Failed to create direct renting: " . pg_last_error($conn));

    echo "Direct renting created successfully!";
}

// Optional: update renting_archive for past stays
$update_archive = "
INSERT INTO renting_archive (archived_renting_id, customer_name, room_info, hotel_info, renting_date, start_date, end_date, status, employee_name)
SELECT r.renting_id, c.full_name, r.room_id::text, h.hotel_name, CURRENT_DATE, r.start_date, r.end_date,
       CASE WHEN r.payment_status THEN 'paid' ELSE 'unpaid' END, e.full_name
FROM renting r
JOIN customer c ON c.customer_id = r.customer_id
JOIN hotel h ON h.hotel_id = r.hotel_id
JOIN employee e ON e.employee_id = r.employee_id
WHERE r.end_date < CURRENT_DATE
ON CONFLICT (archived_renting_id) DO NOTHING;
";
pg_query($conn, $update_archive);
?>