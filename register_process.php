<?php
session_start();
include 'db.php';

$name = $_POST['name'];
$address = $_POST['address'];
$id_type = $_POST['id_type'];

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


$_SESSION['customer_id'] = $row['customer_id'];

echo "Registration successful!";
echo "<br><a href='search.php'>Go to Search</a>";
?>