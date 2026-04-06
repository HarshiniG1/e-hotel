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

<!DOCTYPE html>
<html>
<head>
    <title>Manage Customers</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('hotel2.jpg'); /* optional */
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

        input[type="text"], input[type="date"] {
            padding: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
            width: 100px;
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

        .add-form input[type="text"], .add-form input[type="date"] {
            width: 200px;
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
    <h2>Manage Customers</h2>

    <table>
        <tr>
            <th>ID</th><th>Name</th><th>Address</th><th>ID Type</th><th>Registration Date</th><th>Actions</th>
        </tr>
        <?php while($row = pg_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['customer_id']; ?></td>
                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                <td><?php echo htmlspecialchars($row['address']); ?></td>
                <td><?php echo htmlspecialchars($row['id_type']); ?></td>
                <td><?php echo $row['registration_date']; ?></td>
                <td>
                    <form action="" method="POST" class="inline-form">
                        <input type="hidden" name="customer_id" value="<?php echo $row['customer_id']; ?>">
                        <input type="text" name="full_name" value="<?php echo htmlspecialchars($row['full_name']); ?>" required>
                        <input type="text" name="address" value="<?php echo htmlspecialchars($row['address']); ?>">
                        <input type="text" name="id_type" value="<?php echo htmlspecialchars($row['id_type']); ?>">
                        <input type="date" name="registration_date" value="<?php echo $row['registration_date']; ?>" required>
                        <input type="hidden" name="action" value="update">
                        <button type="submit">Update</button>
                    </form>
                    <form action="" method="POST" class="inline-form">
                        <input type="hidden" name="customer_id" value="<?php echo $row['customer_id']; ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="delete-btn" onclick="return confirm('Delete customer?')">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <h3>Add New Customer</h3>
    <form action="" method="POST" class="add-form">
        Name: <input type="text" name="full_name" required>
        Address: <input type="text" name="address">
        ID Type: <input type="text" name="id_type">
        Registration Date: <input type="date" name="registration_date" required>
        <input type="hidden" name="action" value="add">
        <button type="submit">Add Customer</button>
    </form>

    <br><br>
    <a href="employee_dashboard.php">Back to Dashboard</a>
</div>
</body>
</html>