<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dana_auto_parts";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to view your order history.");
}

$user_id = $_SESSION['user_id'];

// Fetch purchase history for the logged-in user
$sql = "SELECT * FROM purchase_history WHERE user_id = ? ORDER BY purchase_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Tracking</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }
        h1 {
            text-align: center;
            margin-top: 20px;
            color: #444;
        }
        .back-button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px 20px;
            text-align: center;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
        .card-container {
            display: block;
            margin: 20px auto;
            width: 90%;
            max-width: 600px;
        }
        .card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
            transition: transform 0.3s ease;
            text-align: center;
        }
        .card:hover {
            transform: translateY(-10px);
        }
        .card h3 {
            margin: 0;
            font-size: 18px;
            color: #007BFF;
        }
        .card p {
            margin: 10px 0;
            font-size: 14px;
            color: #666;
        }
        .status {
            font-weight: bold;
            color: #28a745;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            font-size: 16px;
            color: #666;
        }
    </style>
</head>
<body>
    <a href="account.php" class="back-button">Back to Account Page</a>
    <h1>Order Tracking</h1>
    <div class="card-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $quantity = isset($row['product_quantity']) ? $row['product_quantity'] : 'N/A'; 
                echo "<div class='card'>
                        <h3>{$row['product_name']}</h3>
                        <p><strong>Purchase Date:</strong> {$row['purchase_date']}</p>
                        <p><strong>Status:</strong> <span class='status'>{$row['order_status']}</span></p>
                        <p><strong>Quantity:</strong> {$quantity}</p>
                      </div>";
            }
        } else {
            echo "<p class='no-data'>No purchases yet.</p>";
        }
        ?>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
