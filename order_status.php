<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dana_auto_parts";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['order_status'];
    $sql = "UPDATE purchase_history SET order_status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_status, $order_id);
    $stmt->execute();
    echo "Order status updated successfully!";
}

// Fetch orders
$sql = "SELECT * FROM purchase_history ORDER BY purchase_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Order Update</title>
</head>
<body>
    <h1>Update Order Status</h1>
    <form method="POST">
        <label for="order_id">Order ID:</label>
        <select name="order_id" required>
            <?php
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['id']}'>{$row['id']} - {$row['product_name']}</option>";
            }
            ?>
        </select>
        <label for="order_status">New Status:</label>
        <select name="order_status" required>
            <option value="Pending">Pending</option>
            <option value="Shipped">Shipped</option>
            <option value="Delivered">Delivered</option>
            <option value="Cancelled">Cancelled</option>
        </select>
        <button type="submit">Update Status</button>
    </form>
</body>
</html>
