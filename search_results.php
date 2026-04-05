<?php
session_start();
include 'db.php';

if (!isset($_SESSION['customer_id'])) {
    die("Please login first.");
}

$area = $_POST['area'];
$capacity = $_POST['capacity'];
$chain = $_POST['chain'];
$category = $_POST['category'];
$price = $_POST['price'];
$start = $_POST['start'];
$end = $_POST['end'];

$query = "
SELECT r.*
FROM room r
JOIN hotel h ON r.hotel_id = h.hotel_id
WHERE 1=1
";

if (!empty($area)) {
    $query .= " AND h.address ILIKE '%$area%'";
}

if (!empty($capacity)) {
    $query .= " AND r.capacity = '$capacity'";
}

if (!empty($chain)) {
    $query .= " AND h.chain_id = $chain";
}

if (!empty($category)) {
    $query .= " AND h.category = $category";
}

if (!empty($price)) {
    $query .= " AND r.price <= $price";
}

$query .= "
AND r.room_id NOT IN (
    SELECT room_id FROM booking
    WHERE (start_date, end_date) OVERLAPS ('$start', '$end')
)
";

$result = pg_query($conn, $query);

if (!$result) {
    die("Query failed: " . pg_last_error($conn));
}

echo "<h2>Available Rooms</h2>";

while ($row = pg_fetch_assoc($result)) {

    echo "Room ID: " . $row['room_id'] . "<br>";
    echo "Price: " . $row['price'] . "<br>";

    echo "<form method='POST' action='book.php'>
        <input type='hidden' name='room_id' value='".$row['room_id']."'>
        <input type='hidden' name='start' value='".$start."'>
        <input type='hidden' name='end' value='".$end."'>
        <button type='submit'>Book</button>
    </form>";

    echo "<hr>";
}
?>