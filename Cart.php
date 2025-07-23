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
    echo "<div class='error-container'>
            <h2>Access Denied 🚫</h2>
            <p>You need to log in to access this page.</p>
            <a href='Account.html' class='login-btn'>Login Now</a>
          </div>";
    exit();
}


$user_id = $_SESSION['user_id'];

// Check if a product is being added to the cart
if (isset($_GET['add_id'])) {
    $product_id = $_GET['add_id'];

    // Fetch product details
    $query = "SELECT * FROM auto_parts_inventory WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product) {
        // Check if the product already exists in the cart
        $check_query = "SELECT * FROM cart WHERE user_id = ? AND id = ?";

        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("ii", $user_id, $product_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            // Update quantity if product already exists
            $update_query = "UPDATE cart SET product_quantity = product_quantity + 1 WHERE user_id = ? AND product_id = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("ii", $user_id, $product_id);
            $update_stmt->execute();
        } else {
           // Insert new product into the cart
$insert_query = "INSERT INTO cart (user_id, product_name, product_price, product_quantity, product_image) VALUES (?, ?, ?, ?, ?)";
$insert_stmt = $conn->prepare($insert_query);

// Fix bind_param: Change "iisd" to "issds" to match values correctly
$product_name = $product['name'];
$product_price = $product['price'];
$product_quantity = 1; // Default quantity when adding a new product
$product_image = $product['image']; // Assuming 'image' column exists in auto_parts_inventory

$insert_stmt->bind_param("issds", $user_id, $product_name, $product_price, $product_quantity, $product_image);
$insert_stmt->execute();

        }
    }

    // Redirect to cart page after adding
    header("Location: cart.php");
    exit();
}

// Remove item from cart
if (isset($_GET['remove_id'])) {
    $remove_id = $_GET['remove_id'];
    $delete_query = "DELETE FROM cart WHERE user_id = ? AND id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("ii", $user_id, $remove_id);
    $delete_stmt->execute();

    // Redirect to refresh the cart
    header("Location: cart.php");
    exit();
}

// Fetch only the logged-in user's cart items
$sql = "SELECT * FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Initialize subtotal
$subtotal = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cart Page</title>
    
    <style>
        
        /* Styling for Purchase button */
.purchase-btn {
    background-color:rgb(30, 176, 14); /* Green color */
    color: white;
    border: none;
    padding: 12px 20px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    border-radius: 5px;
    text-align: center;
    transition: background 0.3s ease;
    display: inline-block;
    width: 100%;
}

.purchase-btn:hover {
    background-color:rgb(226, 23, 23); /* Darker green on hover */
}

/* Styling for Remove button */
.remove-btn {
    background-color: #dc3545; /* Red color */
    color: white;
    text-decoration: none;
    padding: 8px 15px;
    font-size: 14px;
    font-weight: bold;
    border-radius: 5px;
    display: inline-block;
    text-align: center;
    transition: background 0.3s ease;
}

.remove-btn:hover {
    background-color: #c82333; /* Darker red on hover */
}



        </style>
    <link rel="stylesheet" href="Cart.css">
    
</head>
<body>
<nav class="navbar">
    <div class="logo">
        <a href="#"><img src="images/Logo.png.webp" alt="Dana Auto Parts">Dana Auto Parts</a>
    </div>
    <ul class="nav-links">
        <li><a href="Home.html">Home</a></li>
        <li><a href="products.html">Products</a></li>
        <li><a href="About.html">About</a></li>
        <li><a href="Account.html">Account</a></li>
        <li><a href="Cart.php"><img src="images/cart.png.jpg" width="auto" height="30px" alt="Cart Icon"></a></li>
    </ul>
    <div class="hamburger" id="hamburger">
        <span></span>
        <span></span>
        <span></span>
    </div>
</nav>

<script>
    // Toggle navbar on mobile
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.querySelector('.nav-links');

    hamburger.addEventListener('click', () => {
        navLinks.classList.toggle('active');
        hamburger.classList.toggle('active');
    });
</script>

<div class="small-container" id="cart-page">
    <table id="cart-table">
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $itemSubtotal = $row['product_price'] * $row['product_quantity'];
                $subtotal += $itemSubtotal;
                echo "<tr>
                        <td>
                            <div class='cart-info'>
                                <p>{$row['product_name']}</p>
                                <small>Price: Rs {$row['product_price']}</small>
                            </div>
                        </td>
                        <td>{$row['product_quantity']}</td>
                        <td>Rs {$itemSubtotal}</td>
                        <td>
                            <a href='cart.php?remove_id={$row['id']}' class='remove-btn'>Remove</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Your cart is empty</td></tr>";
        }
        ?>
    </table>
    <div class="total-price">
        <table>
            <tr>
                <td>Subtotal</td>
                <td>Rs <?php echo $subtotal; ?></td>
            </tr>
            <tr>
                <td>Total</td>
                <td>Rs <?php echo $subtotal; ?></td>
            </tr>
        </table>
    </div><br><br>

    <div class="purchase-section">
        <form action="purchase.php" method="POST">
            <button type="submit" class="purchase-btn">Purchase</button>
        </form>
    </div>
</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
