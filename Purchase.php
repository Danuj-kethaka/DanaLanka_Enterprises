<?php
session_start(); // Start session to get the logged-in user

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
    die("Please log in to make a purchase.");
}

$user_id = $_SESSION['user_id']; // Get the logged-in user ID

// Fetch all cart items
$sql = "SELECT * FROM cart";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Insert each cart item into purchase_history
    while ($row = $result->fetch_assoc()) {
        $product_name = $row['product_name'];
        $product_price = $row['product_price'];
        $product_quantity = $row['product_quantity'];
        $total_price = $product_price * $product_quantity;

        $insertSql = "INSERT INTO purchase_history (user_id, product_name, product_price, product_quantity, total_price, order_status, purchase_date)
                      VALUES (?, ?, ?, ?, ?, 'Pending', NOW())";

        $stmt = $conn->prepare($insertSql);
        $stmt->bind_param("isddi", $user_id, $product_name, $product_price, $product_quantity, $total_price);
        $stmt->execute();
    }

    // Clear the cart after purchase
    $clearCartSql = "DELETE FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($clearCartSql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    echo "<script>alert('Purchase successful!'); window.location.href = 'PurchaseHistory.php';</script>";
} else {
    echo "<script>alert('Cart is empty.'); window.location.href = 'Cart.php';</script>";
}

$conn->close();
?>
