<?php
session_start();
include 'db.php';

$name = $_POST['name'];
$address = $_POST['address'];
$id_type = $_POST['id_type'];

// ✅ FIXED query (no id_number)
$query = "
INSERT INTO customer (full_name, address, id_type, registration_date)
VALUES ('$name', '$address', '$id_type', CURRENT_DATE)
RETURNING customer_id
";

$result = pg_query($conn, $query);

if (!$result) {
    die("Registration failed: " . pg_last_error($conn));
}

$row = pg_fetch_assoc($result);

// store session
$_SESSION['customer_id'] = $row['customer_id'];

echo "Registration successful!<br>";
echo "<a href='index.php'>Go search rooms</a>";
?>