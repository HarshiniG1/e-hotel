```php
<?php
session_start();
include 'db.php';


// VIEW 1
$available_query = "SELECT * FROM available_rooms_per_area";
$available_result = pg_query($conn, $available_query);


// VIEW 2
$capacity_query = "SELECT * FROM hotel_total_capacity";
$capacity_result = pg_query($conn, $capacity_query);
?>


<!DOCTYPE html>
<html>
<head>
    <title>e-Hotels Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        button {
            padding: 15px 30px;
            margin: 10px;
            font-size: 16px;
            cursor: pointer;
        }
        h1 {
            color: #2F4F4F;
        }
        table {
            margin: auto;
            border-collapse: collapse;
            width: 60%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
        }
        th {
            background-color: #2F4F4F;
            color: white;
        }
    </style>
</head>
<body>


<h1>Welcome to e-Hotels</h1>


<?php if (!isset($_SESSION['customer_id'])): ?>


    <p>You are not logged in.</p>


    <a href="register.php"><button>Customer Registration</button></a>
    <a href="customer_login.php"><button>Customer Login</button></a>


    <a href="employee_register.php"><button>Employee Registration</button></a>
    <a href="employee_login.php"><button>Employee Login</button></a>


<?php else: ?>


    <p>Logged in as Customer ID: <?php echo $_SESSION['customer_id']; ?></p>


    <a href="logout_customer.php"><button>Logout</button></a>


    <br><br>


    <h2>Search Available Rooms</h2>


    <form action="search.php" method="POST">


        Area: <input type="text" name="area"><br><br>


        Capacity:
        <select name="capacity">
            <option value="">Any</option>
            <option value="S">Single</option>
            <option value="D">Double</option>
            <option value="Q">Queen</option>
            <option value="Sui">Suite</option>
        </select><br><br>


        Hotel Chain ID:
        <input type="number" name="chain"><br><br>


        Category (Stars):
        <input type="number" name="category"><br><br>


        Max Price:
        <input type="number" name="price"><br><br>


        Start Date:
        <input type="date" name="start" required><br><br>


        End Date:
        <input type="date" name="end" required><br><br>


        <button type="submit">Search</button>


    </form>


<?php endif; ?>


<!-- VIEW 1 (VISIBLE TO EVERYONE) -->
<h2>Available Rooms Per Area</h2>


<table>
    <tr>
        <th>Area</th>
        <th>Available Rooms</th>
    </tr>


    <?php while ($row = pg_fetch_assoc($available_result)) { ?>
        <tr>
            <td><?php echo $row['address']; ?></td>
            <td><?php echo $row['available_rooms']; ?></td>
        </tr>
    <?php } ?>
</table>


<!-- VIEW 2 (FIXED: total_rooms) -->
<h2>Hotel Total Rooms</h2>


<table>
    <tr>
        <th>Hotel Name</th>
        <th>Total Rooms</th>
    </tr>


    <?php while ($row = pg_fetch_assoc($capacity_result)) { ?>
        <tr>
            <td><?php echo $row['hotel_name']; ?></td>
            <td><?php echo $row['total_rooms']; ?></td>
        </tr>
    <?php } ?>
</table>


</body>
</html>
```
