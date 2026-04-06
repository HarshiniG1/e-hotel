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
SELECT r.*, h.hotel_name
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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('hotel2.jpg');
            background-size: cover;
            background-position: center;
            color: #333;
            padding: 40px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: rgba(255,255,255,0.95);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 25px rgba(0,0,0,0.25);
        }

        h2 {
            color: #0d4d63;
            margin-bottom: 20px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background-color: #0d4d63;
            color: white;
            padding: 12px;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #f4f9fb;
        }

        tr:hover {
            background-color: #e2f4f7;
        }

        button {
            background: linear-gradient(135deg, #1ba6a6, #0d4d63);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: 0.3s;
        }

        button:hover {
            opacity: 0.9;
        }

        .actions {
            margin-top: 20px;
            text-align: center;
        }

        .actions a {
            text-decoration: none;
        }

    </style>
</head>
<body>
<div class="container">
    <h2>Available Rooms</h2>

    <?php if (pg_num_rows($result) == 0): ?>
        <p style="text-align:center;">No rooms found for your criteria.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Room ID</th>
                <th>Hotel Name</th>
                <th>Price</th>
                <th>Action</th>
            </tr>

            <?php while ($row = pg_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['room_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['hotel_name']); ?></td>
                    <td><?php echo $row['price']; ?></td>
                    <td>
                        <form method="POST" action="book.php">
                            <input type="hidden" name="room_id" value="<?php echo $row['room_id']; ?>">
                            <input type="hidden" name="start" value="<?php echo $start; ?>">
                            <input type="hidden" name="end" value="<?php echo $end; ?>">
                            <button type="submit">Book</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>

    <div class="actions">
        <a href="search.php"><button>Back to Search</button></a>
        <a href="index.php"><button>Go to Dashboard</button></a>
    </div>

</div>
</body>
</html>