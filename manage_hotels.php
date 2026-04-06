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

<!DOCTYPE html>
<html>
<head>
    <title>Manage Hotels</title>
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
            max-width: 1300px;
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
            width: 120px;
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
    <h2>Manage Hotels</h2>

    <table>
        <tr>
            <th>ID</th><th>Chain ID</th><th>Name</th><th>Address</th><th>Category</th><th>Rooms</th><th>Email</th><th>Phone</th><th>Actions</th>
        </tr>
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
                    <form action="" method="POST" class="inline-form">
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
                    <form action="" method="POST" class="inline-form">
                        <input type="hidden" name="hotel_id" value="<?php echo $row['hotel_id']; ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="delete-btn" onclick="return confirm('Delete hotel?')">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <h3>Add New Hotel</h3>
    <form action="" method="POST" class="add-form">
        Chain ID: <input type="number" name="chain_id" required>
        Name: <input type="text" name="hotel_name" required>
        Address: <input type="text" name="address">
        Category: <input type="number" name="category" min="1" max="5">
        Rooms: <input type="number" name="number_of_rooms" min="0">
        Email: <input type="text" name="email">
        Phone: <input type="text" name="phone">
        <input type="hidden" name="action" value="add">
        <button type="submit">Add Hotel</button>
    </form>

    <br><br>
    <a href="employee_dashboard.php">Back to Dashboard</a>
</div>
</body>
</html>