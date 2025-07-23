<?php
session_start();

// Check if the cart is already initialized
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Check if product data is received via POST
if (isset($_POST['productName'], $_POST['productPrice'], $_POST['productQuantity'], $_POST['productImage'])) {
    $productName = $_POST['productName'];
    $productPrice = $_POST['productPrice'];
    $productQuantity = $_POST['productQuantity'];
    $productImage = $_POST['productImage'];

    // Create a product array
    $product = [
        'name' => $productName,
        'price' => $productPrice,
        'quantity' => $productQuantity,
        'image' => $productImage
    ];

    // Add product to the cart (if not already present)
    $found = false;
    foreach ($_SESSION['cart'] as &$cartProduct) {
        if ($cartProduct['name'] === $productName) {
            $cartProduct['quantity'] += $productQuantity;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $_SESSION['cart'][] = $product;
    }

    // Send a success response back to JavaScript
    echo "Product added to cart successfully!";
}
?>
