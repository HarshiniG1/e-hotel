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
        $sea = isset($_POST['sea_view']) ? true:false;
        $mountain = isset($_POST['mountain_view']) ? true:false;
        $extend = isset($_POST['extendable']) ? true:false;
        $problems = $_POST['problems_description'];
        $query = "INSERT INTO room (hotel_id,price,capacity,sea_view,mountain_view,extendable,problems_description) VALUES ($1,$2,$3,$4,$5,$6,$7)";
        pg_query_params($conn, $query, [$hotel_id,$price,$capacity,$sea,$mountain,$extend,$problems]);
    } elseif ($action === 'update') {
        $id = $_POST['room_id'];
        $hotel_id = $_POST['hotel_id'];
        $price = $_POST['price'];
        $capacity = $_POST['capacity'];
        $sea = isset($_POST['sea_view']) ? true:false;
        $mountain = isset($_POST['mountain_view']) ? true:false;
        $extend = isset($_POST['extendable']) ? true:false;
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

<h2>Manage Rooms</h2>
<table border="1" cellpadding="5">
<tr><th>ID</th><th>Hotel ID</th><th>Price</th><th>Capacity</th><th>Sea View</th><th>Mountain View</th><th>Extendable</th><th>Problems</th><th>Actions</th></tr>
<?php while($row = pg_fetch_assoc($result)): ?>
<tr>
<td><?php echo $row['room_id']; ?></td>
<td><?php echo $row['hotel_id']; ?></td>
<td><?php echo $row['price']; ?></td>
<td><?php echo htmlspecialchars($row['capacity']); ?></td>
<td><?php echo $row['sea_view'] ? 'Yes':'No'; ?></td>
<td><?php echo $row['mountain_view'] ? 'Yes':'No'; ?></td>
<td><?php echo $row['extendable'] ? 'Yes':'No'; ?></td>
<td><?php echo htmlspecialchars($row['problems_description']); ?></td>
<td>
<form action="" method="POST" style="display:inline-block;">
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
<form action="" method="POST" style="display:inline-block;">
<input type="hidden" name="room_id" value="<?php echo $row['room_id']; ?>">
<input type="hidden" name="action" value="delete">
<button type="submit" onclick="return confirm('Delete room?')">Delete</button>
</form>
</td>
</tr>
<?php endwhile; ?>
</table>

<h3>Add New Room</h3>
<form action="" method="POST">
Hotel ID: <input type="number" name="hotel_id" required><br>
Price: <input type="number" step="0.01" name="price" required><br>
Capacity: <input type="text" name="capacity" required><br>
Sea View: <input type="checkbox" name="sea_view"><br>
Mountain View: <input type="checkbox" name="mountain_view"><br>
Extendable: <input type="checkbox" name="extendable"><br>
Problems: <input type="text" name="problems_description"><br>
<input type="hidden" name="action" value="add">
<button type="submit">Add Room</button>
</form>

<br><a href="employee_dashboard.php">Back to Dashboard</a>