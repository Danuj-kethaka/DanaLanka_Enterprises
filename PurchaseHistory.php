<?php
session_start(); // Start the session to get user information

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
    die("Please log in to view your purchase history.");
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

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
    <title>Purchase History</title>
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
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007BFF;
            color: white;
            text-transform: uppercase;
            font-size: 14px;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        td {
            font-size: 14px;
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
    <h1>Purchase History</h1>
    <table>
        <tr>
            <th>Product Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Purchase Date</th>
            <th>Order Status</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['product_name']}</td>
                        <td>Rs {$row['product_price']}</td>
                        <td>{$row['product_quantity']}</td>
                        <td>Rs {$row['total_price']}</td>
                        <td>{$row['purchase_date']}</td>
                        <td>{$row['order_status']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='6' class='no-data'>No purchases yet.</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
