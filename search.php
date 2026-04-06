<!DOCTYPE html>
<html>
<head>
    <title>Search Available Rooms</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('hotel2.jpg');
            background-size: cover;
            background-position: center;
            color: #333;
            padding: 30px;
        }

        .container {
            background-color: rgba(255,255,255,0.95);
            padding: 25px;
            border-radius: 12px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #222;
        }

        input[type="text"], input[type="number"], input[type="date"], select {
            width: 100%;
            padding: 8px 10px;
            margin: 8px 0 16px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 10px 0;
            margin-top: 10px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
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
    <h2>Search Available Rooms</h2>

    <form action="search_results.php" method="POST">
        Area: <input type="text" name="area"><br>

        Capacity:
        <select name="capacity">
            <option value="">Any</option>
            <option value="S">Single</option>
            <option value="D">Double</option>
            <option value="Q">Queen</option>
            <option value="Sui">Suite</option>
        </select><br>

        Hotel Chain ID: <input type="number" name="chain"><br>
        Category: <input type="number" name="category"><br>
        Max Price: <input type="number" name="price"><br>
        Start Date: <input type="date" name="start" required><br>
        End Date: <input type="date" name="end" required><br>

        <button type="submit">Search</button>
    </form>

    <a href="customer_dashboard.php">Back to Dashboard</a>
</div>
</body>
</html>