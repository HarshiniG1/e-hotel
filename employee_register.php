<?php
include 'db.php';

// Fetch hotels for dropdown
$hotels_result = pg_query($conn, "SELECT hotel_id, hotel_name, address FROM hotel ORDER BY hotel_name");
?>

<!DOCTYPE html>
<html>
<body>

<h2>Employee Registration</h2>

<form action="employee_register_process.php" method="POST">
    Full Name: <input type="text" name="full_name" required><br><br>
    Address: <input type="text" name="address"><br><br>
    SIN: <input type="text" name="sin" required><br><br>
    Role: <input type="text" name="role" required><br><br>

    Hotel:
    <select name="hotel_id" required>
        <option value="">Select Hotel</option>
        <?php while ($row = pg_fetch_assoc($hotels_result)): ?>
            <option value="<?php echo $row['hotel_id']; ?>">
                <?php echo $row['hotel_name'] . " - " . $row['address']; ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <button type="submit">Register Employee</button>
</form>

</body>
</html>