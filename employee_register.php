<?php
include 'db.php';

// Fetch hotels for dropdown
$hotels_result = pg_query($conn, "SELECT hotel_id, hotel_name, address FROM hotel ORDER BY hotel_name");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('hotel2.jpg'); /* optional background */
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .registration-container {
            background-color: rgba(255,255,255,0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            width: 400px;
            text-align: center;
        }

        h2 {
            color: #222;
            margin-bottom: 20px;
        }

        input, select {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #4CAF50; /* green */
            color: white;
            padding: 10px 25px;
            margin-top: 15px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }

        a {
            display: block;
            margin-top: 15px;
            color: #008CBA;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="registration-container">
    <h2>Employee Registration</h2>
    <form action="employee_register_process.php" method="POST">
        Full Name:<br>
        <input type="text" name="full_name" required><br>

        Address:<br>
        <input type="text" name="address"><br>

        SIN:<br>
        <input type="text" name="sin" required><br>

        Role:<br>
        <input type="text" name="role" required><br>

        Hotel:<br>
        <select name="hotel_id" required>
            <option value="">Select Hotel</option>
            <?php while ($row = pg_fetch_assoc($hotels_result)): ?>
                <option value="<?php echo $row['hotel_id']; ?>">
                    <?php echo $row['hotel_name'] . " - " . $row['address']; ?>
                </option>
            <?php endwhile; ?>
        </select><br>

        <button type="submit">Register Employee</button>
    </form>
    <a href="index.php">Back to Home</a>
</div>
</body>
</html>