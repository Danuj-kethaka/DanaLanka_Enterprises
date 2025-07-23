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

// Add product functionality
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $price = $_POST['price'];
    $description = $_POST['description']; // Get the product description
    
    // Handle file upload
    $image = $_FILES['image']['name'];
    $target_dir = "images/"; // Use the "images" folder in your project
    $target_file = $target_dir . basename($image);

    // Move the uploaded file to the "images" directory
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        // Insert product into database
        $insert_query = "INSERT INTO auto_parts_inventory (name, brand, price, image, description) 
                         VALUES ('$name', '$brand', '$price', '$target_file', '$description')";
        mysqli_query($conn, $insert_query);
        header('Location: admin_product_management.php'); // Refresh page
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

// Fetch products from database
$query = "SELECT * FROM auto_parts_inventory";
$result = mysqli_query($conn, $query);

// Delete product functionality
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM auto_parts_inventory WHERE id = '$delete_id'";
    mysqli_query($conn, $delete_query);
    header('Location: admin_product_management.php'); // Refresh page
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Product Management</title>
    <link rel="stylesheet" href="admin_style.css">
    <style>
       
    </style>
</head>
<body>
    <h1>Admin - Product Management</h1>

    <!-- Add Product Heading -->
    <!-- This is the heading for the "Add Product" section -->

    <!-- Add New Product Form -->
    <form method="POST" action="admin_product_management.php" enctype="multipart/form-data">
    
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" required>
        
        <label for="brand">Brand:</label>
        <input type="text" id="brand" name="brand" required>
        
        <label for="price">Price:</label>
        <input type="number" id="price" name="price" required>
        
        <label for="description">Product Description:</label>
        <textarea id="description" name="description" required></textarea> <!-- Added Description -->

        <label for="image">Product Image:</label>
        <input type="file" id="image" name="image" required>
        
        <button type="submit" name="add_product">Add Product</button>
    </form><br><br>

    <h2>Existing Products</h2>
    <table>
        <tr>
            <th>Product Name</th>
            <th>Brand</th>
            <th>Price</th>
            <th>Image</th>
            <th>Description</th> <!-- Added Description column -->
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['brand']; ?></td>
                <td>Rs <?php echo $row['price']; ?></td>
                <td><img src="<?php echo $row['image']; ?>" alt="Product Image" width="50"></td>
                <td><?php echo $row['description']; ?></td> <!-- Display Description -->
                <td><a href="admin_product_management.php?delete_id=<?php echo $row['id']; ?>">Delete</a></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
