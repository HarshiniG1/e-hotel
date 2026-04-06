<?php
session_start();
include 'db.php';
if (!isset($_SESSION['employee_id']) || !$_SESSION['is_manager']) die("Access denied.");

// Handle POST: Add / Update / Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add') {
        $name = $_POST['full_name'];
        $address = $_POST['address'];
        $sin = $_POST['sin'];
        $role = $_POST['role'];
        $hotel_id = $_POST['hotel_id'];
        $query = "INSERT INTO employee (full_name,address,sin,role,hotel_id) VALUES ($1,$2,$3,$4,$5)";
        pg_query_params($conn,$query,[$name,$address,$sin,$role,$hotel_id]);
    } elseif ($action === 'update') {
        $id = $_POST['employee_id'];
        $name = $_POST['full_name'];
        $address = $_POST['address'];
        $sin = $_POST['sin'];
        $role = $_POST['role'];
        $hotel_id = $_POST['hotel_id'];
        $query = "UPDATE employee SET full_name=$1,address=$2,sin=$3,role=$4,hotel_id=$5 WHERE employee_id=$6";
        pg_query_params($conn,$query,[$name,$address,$sin,$role,$hotel_id,$id]);
    } elseif ($action === 'delete') {
        $id = $_POST['employee_id'];
        $query = "DELETE FROM employee WHERE employee_id=$1";
        pg_query_params($conn, $query, [$id]);
    }
}

// Fetch all employees
$result = pg_query($conn, "SELECT * FROM employee ORDER BY employee_id ASC");
?>

<h2>Manage Employees</h2>
<table border="1" cellpadding="5">
<tr><th>ID</th><th>Name</th><th>Address</th><th>SIN</th><th>Role</th><th>Hotel ID</th><th>Actions</th></tr>
<?php while($row = pg_fetch_assoc($result)): ?>
<tr>
<td><?php echo $row['employee_id']; ?></td>
<td><?php echo htmlspecialchars($row['full_name']); ?></td>
<td><?php echo htmlspecialchars($row['address']); ?></td>
<td><?php echo htmlspecialchars($row['sin']); ?></td>
<td><?php echo htmlspecialchars($row['role']); ?></td>
<td><?php echo $row['hotel_id']; ?></td>
<td>
<form action="" method="POST" style="display:inline-block;">
<input type="hidden" name="employee_id" value="<?php echo $row['employee_id']; ?>">
<input type="text" name="full_name" value="<?php echo htmlspecialchars($row['full_name']); ?>" required>
<input type="text" name="address" value="<?php echo htmlspecialchars($row['address']); ?>">
<input type="text" name="sin" value="<?php echo htmlspecialchars($row['sin']); ?>" required>
<input type="text" name="role" value="<?php echo htmlspecialchars($row['role']); ?>" required>
<input type="number" name="hotel_id" value="<?php echo $row['hotel_id']; ?>" required>
<input type="hidden" name="action" value="update">
<button type="submit">Update</button>
</form>
<form action="" method="POST" style="display:inline-block;">
<input type="hidden" name="employee_id" value="<?php echo $row['employee_id']; ?>">
<input type="hidden" name="action" value="delete">
<button type="submit" onclick="return confirm('Delete employee?')">Delete</button>
</form>
</td>
</tr>
<?php endwhile; ?>
</table>

<h3>Add New Employee</h3>
<form action="" method="POST">
Name: <input type="text" name="full_name" required><br>
Address: <input type="text" name="address"><br>
SIN: <input type="text" name="sin" required><br>
Role: <input type="text" name="role" required><br>
Hotel ID: <input type="number" name="hotel_id" required><br>
<input type="hidden" name="action" value="add">
<button type="submit">Add Employee</button>
</form>

<br><a href="employee_dashboard.php">Back to Dashboard</a>