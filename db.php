<?php
$conn = pg_connect("host=localhost port=5432 dbname=e-hotels user=postgres password=Btsardy6677");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
} else {
    echo "Connection successful!";
}
?>