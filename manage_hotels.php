<?php
session_start();
include 'db.php';
if (!isset($_SESSION['employee_id']) || !$_SESSION['is_manager']) die("Access denied.");

// Handle POST: Add / Update / Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add') {
        $chain_id = $_POST['chain_id'];
        $name = $_POST['hotel_name'];
        $address = $_POST['address'];
        $category = $_POST['category'];
        $rooms = $_POST['number_of_rooms'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $query = "INSERT INTO hotel (chain_id,hotel_name,address,category,number_of_rooms,email,phone) VALUES ($1,$2,$3,$4,$5,$6,$7)";
        pg_query_params($conn, $query, [$chain_id,$name,$address,$category,$rooms,$email,$phone]);
    } elseif ($action === 'update') {
        $id = $_POST['hotel_id'];
        $chain_id = $_POST['chain_id'];
        $name = $_POST['hotel_name'];
        $address = $_POST['address'];
        $category = $_POST['category'];
        $rooms = $_POST['number_of_rooms'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $query = "UPDATE hotel SET chain_id=$1,hotel_name=$2,address=$3,category=$4,number_of_rooms=$5,email=$6,phone=$7 WHERE hotel_id=$8";
        pg_query_params($conn, $query, [$chain_id,$name,$address,$category,$rooms,$email,$phone,$id]);
    } elseif ($action === 'delete') {
        $id = $_POST['hotel_id'];
        $query = "DELETE FROM hotel WHERE hotel_id=$1";
        pg_query_params($conn, $query, [$id]);
    }
}

// Fetch all hotels
$result = pg_query($conn, "SELECT * FROM hotel ORDER BY hotel_id ASC");
?>

<h2>Manage Hotels</h2>
<table border="1" cellpadding="5">
<tr><th>ID</th><th>Chain ID</th><th>Name</th><th>Address</th><th>Category</th><th>Rooms</th><th>Email</th><th>Phone</th><th>Actions</th></tr>
<?php while($row = pg_fetch_assoc($result)): ?>
<tr>
<td><?php echo $row['hotel_id']; ?></td>
<td><?php echo $row['chain_id']; ?></td>
<td><?php echo htmlspecialchars($row['hotel_name']); ?></td>
<td><?php echo htmlspecialchars($row['address']); ?></td>
<td><?php echo $row['category']; ?></td>
<td><?php echo $row['number_of_rooms']; ?></td>
<td><?php echo htmlspecialchars($row['email']); ?></td>
<td><?php echo htmlspecialchars($row['phone']); ?></td>
<td>
<form action="" method="POST" style="display:inline-block;">
<input type="hidden" name="hotel_id" value="<?php echo $row['hotel_id']; ?>">
<input type="number" name="chain_id" value="<?php echo $row['chain_id']; ?>" required>
<input type="text" name="hotel_name" value="<?php echo htmlspecialchars($row['hotel_name']); ?>" required>
<input type="text" name="address" value="<?php echo htmlspecialchars($row['address']); ?>">
<input type="number" name="category" value="<?php echo $row['category']; ?>" min="1" max="5">
<input type="number" name="number_of_rooms" value="<?php echo $row['number_of_rooms']; ?>" min="0">
<input type="text" name="email" value="<?php echo htmlspecialchars($row['email']); ?>">
<input type="text" name="phone" value="<?php echo htmlspecialchars($row['phone']); ?>">
<input type="hidden" name="action" value="update">
<button type="submit">Update</button>
</form>
<form action="" method="POST" style="display:inline-block;">
<input type="hidden" name="hotel_id" value="<?php echo $row['hotel_id']; ?>">
<input type="hidden" name="action" value="delete">
<button type="submit" onclick="return confirm('Delete hotel?')">Delete</button>
</form>
</td>
</tr>
<?php endwhile; ?>
</table>

<h3>Add New Hotel</h3>
<form action="" method="POST">
Chain ID: <input type="number" name="chain_id" required><br>
Name: <input type="text" name="hotel_name" required><br>
Address: <input type="text" name="address"><br>
Category: <input type="number" name="category" min="1" max="5"><br>
Rooms: <input type="number" name="number_of_rooms" min="0"><br>
Email: <input type="text" name="email"><br>
Phone: <input type="text" name="phone"><br>
<input type="hidden" name="action" value="add">
<button type="submit">Add Hotel</button>
</form>

<br><a href="employee_dashboard.php">Back to Dashboard</a>