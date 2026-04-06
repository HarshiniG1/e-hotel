<?php
session_start();
include 'db.php';
if (!isset($_SESSION['employee_id']) || !$_SESSION['is_manager']) die("Access denied.");

// Handle POST: Add / Update / Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add') {
        $hotel_id = $_POST['hotel_id'];
        $price = $_POST['price'];
        $capacity = $_POST['capacity'];
        $sea = isset($_POST['sea_view']) ? true : false;
        $mountain = isset($_POST['mountain_view']) ? true : false;
        $extend = isset($_POST['extendable']) ? true : false;
        $problems = $_POST['problems_description'];
        $query = "INSERT INTO room (hotel_id,price,capacity,sea_view,mountain_view,extendable,problems_description) VALUES ($1,$2,$3,$4,$5,$6,$7)";
        pg_query_params($conn, $query, [$hotel_id,$price,$capacity,$sea,$mountain,$extend,$problems]);
    } elseif ($action === 'update') {
        $id = $_POST['room_id'];
        $hotel_id = $_POST['hotel_id'];
        $price = $_POST['price'];
        $capacity = $_POST['capacity'];
        $sea = isset($_POST['sea_view']) ? true : false;
        $mountain = isset($_POST['mountain_view']) ? true : false;
        $extend = isset($_POST['extendable']) ? true : false;
        $problems = $_POST['problems_description'];
        $query = "UPDATE room SET hotel_id=$1,price=$2,capacity=$3,sea_view=$4,mountain_view=$5,extendable=$6,problems_description=$7 WHERE room_id=$8";
        pg_query_params($conn, $query, [$hotel_id,$price,$capacity,$sea,$mountain,$extend,$problems,$id]);
    } elseif ($action === 'delete') {
        $id = $_POST['room_id'];
        $query = "DELETE FROM room WHERE room_id=$1";
        pg_query_params($conn, $query, [$id]);
    }
}

// Fetch all rooms
$result = pg_query($conn, "SELECT * FROM room ORDER BY room_id ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Rooms</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('hotel2.jpg');
            background-size: cover;
            background-position: center;
            color: #333;
            padding: 20px;
        }

        .container {
            background-color: rgba(255,255,255,0.95);
            padding: 20px;
            border-radius: 12px;
            max-width: 1200px;
            margin: auto;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        h2, h3 {
            color: #222;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #aaa;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        input[type="text"], input[type="number"] {
            padding: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
            width: 100px;
        }

        input[type="checkbox"] {
            transform: scale(1.2);
            margin: 0 5px;
        }

        button {
            padding: 6px 12px;
            margin: 2px;
            border: none;
            border-radius: 8px;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }

        .delete-btn {
            background-color: #f44336;
        }

        .delete-btn:hover {
            background-color: #d32f2f;
        }

        form.inline-form {
            display: inline-block;
            margin: 0;
        }

        .add-form input[type="text"], .add-form input[type="number"] {
            width: 180px;
        }

        a {
            color: #008CBA;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Manage Rooms</h2>

    <table>
        <tr>
            <th>ID</th><th>Hotel ID</th><th>Price</th><th>Capacity</th><th>Sea View</th><th>Mountain View</th><th>Extendable</th><th>Problems</th><th>Actions</th>
        </tr>
        <?php while($row = pg_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['room_id']; ?></td>
                <td><?php echo $row['hotel_id']; ?></td>
                <td><?php echo $row['price']; ?></td>
                <td><?php echo htmlspecialchars($row['capacity']); ?></td>
                <td><?php echo $row['sea_view'] ? 'Yes' : 'No'; ?></td>
                <td><?php echo $row['mountain_view'] ? 'Yes' : 'No'; ?></td>
                <td><?php echo $row['extendable'] ? 'Yes' : 'No'; ?></td>
                <td><?php echo htmlspecialchars($row['problems_description']); ?></td>
                <td>
                    <form action="" method="POST" class="inline-form">
                        <input type="hidden" name="room_id" value="<?php echo $row['room_id']; ?>">
                        <input type="number" name="hotel_id" value="<?php echo $row['hotel_id']; ?>" required>
                        <input type="number" step="0.01" name="price" value="<?php echo $row['price']; ?>" required>
                        <input type="text" name="capacity" value="<?php echo htmlspecialchars($row['capacity']); ?>" required>
                        Sea: <input type="checkbox" name="sea_view" <?php if($row['sea_view']) echo 'checked'; ?>>
                        Mountain: <input type="checkbox" name="mountain_view" <?php if($row['mountain_view']) echo 'checked'; ?>>
                        Extendable: <input type="checkbox" name="extendable" <?php if($row['extendable']) echo 'checked'; ?>>
                        <input type="text" name="problems_description" value="<?php echo htmlspecialchars($row['problems_description']); ?>">
                        <input type="hidden" name="action" value="update">
                        <button type="submit">Update</button>
                    </form>
                    <form action="" method="POST" class="inline-form">
                        <input type="hidden" name="room_id" value="<?php echo $row['room_id']; ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="delete-btn" onclick="return confirm('Delete room?')">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <h3>Add New Room</h3>
    <form action="" method="POST" class="add-form">
        Hotel ID: <input type="number" name="hotel_id" required>
        Price: <input type="number" step="0.01" name="price" required>
        Capacity: <input type="text" name="capacity" required><br><br>
        Sea View: <input type="checkbox" name="sea_view">
        Mountain View: <input type="checkbox" name="mountain_view">
        Extendable: <input type="checkbox" name="extendable"><br><br>
        Problems: <input type="text" name="problems_description">
        <input type="hidden" name="action" value="add">
        <button type="submit">Add Room</button>
    </form>

    <br><br>
    <a href="employee_dashboard.php">Back to Dashboard</a>
</div>
</body>
</html>