<?php
// Database connection directly in this file
$servername = "localhost";
$username = "root"; // Or your MySQL username
$password = ""; // Or your MySQL password
$dbname = "dana_auto_parts"; // Use your existing database name here

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products from database
$query = "SELECT * FROM auto_parts_inventory";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Page</title>
    <link rel="stylesheet" href="adminProducts.css">
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #1a1a1a; /* Dark background */
    color: #fff; /* White text for contrast */
    margin: 0;
    padding: 0;
}

h1 {
    text-align: center;
    margin: 30px 0;
    font-size: 36px;
    color: #fff; /* White text for heading */
}

.product-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
    padding: 20px;
}

.product {
    background-color: #2c2c2c; /* Dark product card background */
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    transition: transform 0.3s ease;
}

.product:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3); /* More intense shadow on hover */
}

.product-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.product-details {
    padding: 20px;
}

.product-name {
    font-size: 22px;
    color: #fff; /* White text for product name */
    margin-bottom: 10px;
}

.product-brand,
.product-price,
.product-description {
    font-size: 16px;
    color: #ccc; /* Lighter gray for secondary text */
    margin-bottom: 8px;
}

.add-to-cart {
    padding: 10px 20px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

.add-to-cart:hover {
    background-color: #218838;
}

        </style>
</head>
<body>
    <h1>Our  Products</h1>

    <div class="product-list">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="product">
                <img src="<?php echo $row['image']; ?>" alt="Product Image" class="product-image">
                <div class="product-details">
                    <h2 class="product-name"><?php echo $row['name']; ?></h2>
                    <p class="product-brand">Brand: <?php echo $row['brand']; ?></p>
                    <p class="product-price">Price: Rs <?php echo $row['price']; ?></p>
                    <p class="product-description"><?php echo $row['description']; ?></p>
                    
                    <a href="cart.php?add_id=<?php echo $row['id']; ?>">

                        <button class="add-to-cart">Add to Cart</button>
                    </a>
                </div>
            </div>
        <?php } ?>
    </div>
</body>
</html>
