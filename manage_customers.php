<?php
session_start();
include 'db.php';

if (!isset($_SESSION['employee_id']) || !$_SESSION['is_manager']) {
    die("Access denied.");
}

// Handle POST: Add / Update / Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add') {
        $name = $_POST['full_name'];
        $address = $_POST['address'];
        $id_type = $_POST['id_type'];
        $reg_date = $_POST['registration_date'];
        $query = "INSERT INTO customer (full_name, address, id_type, registration_date) VALUES ($1,$2,$3,$4)";
        pg_query_params($conn, $query, [$name, $address, $id_type, $reg_date]);
    } elseif ($action === 'update') {
        $id = $_POST['customer_id'];
        $name = $_POST['full_name'];
        $address = $_POST['address'];
        $id_type = $_POST['id_type'];
        $reg_date = $_POST['registration_date'];
        $query = "UPDATE customer SET full_name=$1, address=$2, id_type=$3, registration_date=$4 WHERE customer_id=$5";
        pg_query_params($conn, $query, [$name,$address,$id_type,$reg_date,$id]);
    } elseif ($action === 'delete') {
        $id = $_POST['customer_id'];
        $query = "DELETE FROM customer WHERE customer_id=$1";
        pg_query_params($conn, $query, [$id]);
    }
}

// Fetch all customers
$result = pg_query($conn, "SELECT * FROM customer ORDER BY customer_id ASC");
?>

<h2>Manage Customers</h2>
<table border="1" cellpadding="5">
<tr><th>ID</th><th>Name</th><th>Address</th><th>ID Type</th><th>Registration Date</th><th>Actions</th></tr>
<?php while($row = pg_fetch_assoc($result)): ?>
<tr>
<td><?php echo $row['customer_id']; ?></td>
<td><?php echo htmlspecialchars($row['full_name']); ?></td>
<td><?php echo htmlspecialchars($row['address']); ?></td>
<td><?php echo htmlspecialchars($row['id_type']); ?></td>
<td><?php echo $row['registration_date']; ?></td>
<td>
<!-- Update form -->
<form action="" method="POST" style="display:inline-block;">
<input type="hidden" name="customer_id" value="<?php echo $row['customer_id']; ?>">
<input type="text" name="full_name" value="<?php echo htmlspecialchars($row['full_name']); ?>" required>
<input type="text" name="address" value="<?php echo htmlspecialchars($row['address']); ?>">
<input type="text" name="id_type" value="<?php echo htmlspecialchars($row['id_type']); ?>">
<input type="date" name="registration_date" value="<?php echo $row['registration_date']; ?>" required>
<input type="hidden" name="action" value="update">
<button type="submit">Update</button>
</form>
<form action="" method="POST" style="display:inline-block;">
<input type="hidden" name="customer_id" value="<?php echo $row['customer_id']; ?>">
<input type="hidden" name="action" value="delete">
<button type="submit" onclick="return confirm('Delete customer?')">Delete</button>
</form>
</td>
</tr>
<?php endwhile; ?>
</table>

<h3>Add New Customer</h3>
<form action="" method="POST">
Name: <input type="text" name="full_name" required><br>
Address: <input type="text" name="address"><br>
ID Type: <input type="text" name="id_type"><br>
Registration Date: <input type="date" name="registration_date" required><br>
<input type="hidden" name="action" value="add">
<button type="submit">Add Customer</button>
</form>

<br><a href="employee_dashboard.php">Back to Dashboard</a>