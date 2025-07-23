<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";  // default username for WAMP
$password = "";  // default password for WAMP
$dbname = "dana_auto_parts";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}

$user_id = $_SESSION['user_id']; // Get logged-in user ID
$productName = $_POST['productName'];
$productPrice = $_POST['productPrice'];
$productQuantity = $_POST['productQuantity'];
$productImage = $_POST['productImage'];

// Check if the product is already in the cart
$sql = "SELECT id, product_quantity FROM cart WHERE user_id = ? AND product_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $productName);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Update quantity if product already exists in cart
    $newQuantity = $row['product_quantity'] + $productQuantity;
    $updateSQL = "UPDATE cart SET product_quantity = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSQL);
    $updateStmt->bind_param("ii", $newQuantity, $row['id']);
    $updateStmt->execute();
    $updateStmt->close();
} else {
    // Insert new product into cart
    $insertSQL = "INSERT INTO cart (user_id, product_name, product_price, product_quantity, product_image) VALUES (?, ?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertSQL);
    $insertStmt->bind_param("issis", $user_id, $productName, $productPrice, $productQuantity, $productImage);
    $insertStmt->execute();
    $insertStmt->close();
}

$stmt->close();
$conn->close();
echo "Product added to cart successfully!";
?>
