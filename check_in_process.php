<?php
session_start();
include 'db.php';

// Make sure employee is logged in
if (!isset($_SESSION['employee_id'])) {
    die("Please login as employee first.");
}

$employee_id = $_SESSION['employee_id'];

// Get POST data
$customer_id = $_POST['customer_id'] ?? null;  // may be null for walk-in
$room_id = $_POST['room_id'] ?? null;
$start = $_POST['start'] ?? null;
$end = $_POST['end'] ?? null;

// Validate inputs
if (!$room_id || !$start || !$end) {
    die("Missing room or date information.");
}

// 1️⃣ Handle walk-in customer if customer_id is null
if (!$customer_id) {
    $query = "INSERT INTO customer (full_name, address, id_type, registration_date)
              VALUES ('Walk-in Customer', 'Unknown', 'N/A', CURRENT_DATE)
              RETURNING customer_id";
    $res = pg_query($conn, $query);
    if (!$res) {
        die("Failed to create customer: " . pg_last_error($conn));
    }
    $row = pg_fetch_assoc($res);
    $customer_id = $row['customer_id'];
}

// 2️⃣ Get hotel_id from the room
$query = "SELECT hotel_id FROM room WHERE room_id = $room_id";
$res = pg_query($conn, $query);
if (!$res) {
    die("Failed to get hotel_id: " . pg_last_error($conn));
}
$row = pg_fetch_assoc($res);
$hotel_id = $row['hotel_id'];

// 3️⃣ Check if this customer already has a booking for this room and dates
$query_check = "
SELECT * FROM booking
WHERE customer_id = $customer_id AND room_id = $room_id
AND (start_date, end_date) OVERLAPS ('$start', '$end')
";
$res_check = pg_query($conn, $query_check);
$has_booking = pg_num_rows($res_check) > 0;

// 4️⃣ If booking exists, archive it
if ($has_booking) {
    $row_booking = pg_fetch_assoc($res_check);
    $booking_id = $row_booking['booking_id'];
    
    // Insert into booking_archive
    $query_archive = "
    INSERT INTO booking_archive (archived_booking_id, customer_name, room_info, hotel_info, booking_date, start_date, end_date, status)
    SELECT $booking_id, c.full_name, r.room_id::text, h.hotel_name, CURRENT_DATE, b.start_date, b.end_date, 'Archived'
    FROM booking b
    JOIN customer c ON c.customer_id = b.customer_id
    JOIN room r ON r.room_id = b.room_id
    JOIN hotel h ON h.hotel_id = r.hotel_id
    WHERE b.booking_id = $booking_id
    ";
    $res_archive = pg_query($conn, $query_archive);
    if (!$res_archive) {
        die("Failed to archive booking: " . pg_last_error($conn));
    }

    // Delete the booking
    $res_del = pg_query($conn, "DELETE FROM booking WHERE booking_id = $booking_id");
    if (!$res_del) {
        die("Failed to delete booking: " . pg_last_error($conn));
    }
}

// 5️⃣ Insert into renting
$query_rent = "
INSERT INTO renting (customer_id, hotel_id, room_id, employee_id, start_date, end_date, payment_status)
VALUES ($customer_id, $hotel_id, $room_id, $employee_id, '$start', '$end', NULL)
RETURNING renting_id
";
$res_rent = pg_query($conn, $query_rent);
if (!$res_rent) {
    die("Failed to create renting: " . pg_last_error($conn));
}
$row_rent = pg_fetch_assoc($res_rent);
$renting_id = $row_rent['renting_id'];

// 6️⃣ Archive renting (optional if you want to keep renting history immediately)
$query_rent_archive = "
INSERT INTO renting_archive (archived_renting_id, customer_name, room_info, hotel_info, renting_date, start_date, end_date, status, employee_name)
SELECT $renting_id, c.full_name, r.room_id::text, h.hotel_name, CURRENT_DATE, '$start', '$end', 'Active', e.full_name
FROM customer c
JOIN room r ON r.room_id = $room_id
JOIN hotel h ON h.hotel_id = r.hotel_id
JOIN employee e ON e.employee_id = $employee_id
";
$res_rent_archive = pg_query($conn, $query_rent_archive);
if (!$res_rent_archive) {
    die("Failed to archive renting: " . pg_last_error($conn));
}

echo "Check-in successful! Renting created with ID: $renting_id";
?>