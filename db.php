<?php
$conn = pg_connect("host=192.168.0.14 port=5432 dbname=e-hotels user=chessinah password=chekasyes");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
} else {
    echo "Connection successful!";
}
?>