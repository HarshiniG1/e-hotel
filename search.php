<?php
session_start();
include 'db.php';

if (!isset($_SESSION['customer_id'])) {
    die("Please register/login first.");
}
?>

<!DOCTYPE html>
<html>
<body>

<h2>Search Available Rooms</h2>

<form action="search_results.php" method="POST">

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

    Category:
    <input type="number" name="category"><br><br>

    Max Price:
    <input type="number" name="price"><br><br>

    Start Date:
    <input type="date" name="start" required><br><br>

    End Date:
    <input type="date" name="end" required><br><br>

    <button type="submit">Search</button>

</form>

</body>
</html>