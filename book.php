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

// Get hotel_id and room info
$hotel_query = "SELECT r.*, h.hotel_name, h.address FROM room r JOIN hotel h ON r.hotel_id = h.hotel_id WHERE r.room_id = $room_id";
$hotel_result = pg_query($conn, $hotel_query);

if (!$hotel_result || pg_num_rows($hotel_result) == 0) {
    die("Invalid room.");
}

$row = pg_fetch_assoc($hotel_result);
$hotel_id = $row['hotel_id'];
$hotel_name = $row['hotel_name'];
$room_price = $row['price'];
$room_address = $row['address'];

// Insert booking
$insert_query = "
INSERT INTO booking (room_id, customer_id, hotel_id, start_date, end_date)
VALUES ($room_id, $customer_id, $hotel_id, '$start', '$end')
";

$insert_result = pg_query($conn, $insert_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('hotel2.jpg');
            background-size: cover;
            background-position: center;
            color: #333;
            padding: 50px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: rgba(255,255,255,0.95);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 25px rgba(0,0,0,0.25);
            text-align: center;
        }

        h1 {
            color: #0d4d63;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            color: #444;
            margin-bottom: 20px;
        }

        .details {
            background: rgba(27,166,166,0.05);
            border-left: 5px solid #1ba6a6;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
            text-align: left;
        }

        button {
            background: linear-gradient(135deg, #1ba6a6, #0d4d63);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: 0.3s;
            text-decoration: none;
            margin: 5px;
        }

        button:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
<div class="container">
    <?php if ($insert_result): ?>
        <h1>Booking Successful!</h1>
        <p>Your booking has been confirmed.</p>
        <div class="details">
            <p><strong>Room ID:</strong> <?php echo $room_id; ?></p>
            <p><strong>Hotel:</strong> <?php echo htmlspecialchars($hotel_name); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($room_address); ?></p>
            <p><strong>Price:</strong> $<?php echo $room_price; ?></p>
            <p><strong>Start Date:</strong> <?php echo $start; ?></p>
            <p><strong>End Date:</strong> <?php echo $end; ?></p>
        </div>
    <?php else: ?>
        <h1>Booking Failed!</h1>
        <p>Something went wrong. Please try again.</p>
    <?php endif; ?>

    <a href="search.php"><button>Back to Search</button></a>
    <a href="index.php"><button>Go to Dashboard</button></a>
</div>
</body>
</html>