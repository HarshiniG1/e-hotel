<?php
session_start();
include 'db.php';

if (!isset($_SESSION['employee_id'])) {
    die("Access denied.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $renting_id = $_POST['renting_id'];
    $payment_status = $_POST['payment_status'];

    // Convert to PostgreSQL boolean
    if ($payment_status == '1') {
        $payment_status = 'TRUE';
    } else {
        $payment_status = 'FALSE';
    }

    $query = "UPDATE renting SET payment_status = $1 WHERE renting_id = $2";
    $result = pg_query_params($conn, $query, array($payment_status, $renting_id));

    if ($result) {
        header("Location: employee_dashboard.php");
        exit();
    } else {
        echo "Error updating payment status.";
    }
} else {
    echo "Invalid request.";
}
?>