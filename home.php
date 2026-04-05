<?php
session_start();
?>

<!DOCTYPE html>
<html>
<body>

<h2>Welcome to e-Hotels</h2>

<?php if (!isset($_SESSION['customer_id'])): ?>

    <p>You are not logged in.</p>

    <a href="register.php">
        <button>Register as Customer</button>
    </a>

<?php else: ?>

    <p>You are logged in!</p>

    <a href="index.php">
        <button>Search Rooms</button>
    </a>

<?php endif; ?>

</body>
</html>