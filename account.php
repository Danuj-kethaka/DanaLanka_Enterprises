<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: Account.html");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "dana_auto_parts");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user details from session
$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM users WHERE id='$user_id'");

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}

$conn->close();

// Set profile picture (fixed path)
$profile_picture = "http://localhost/New Dana lanka Website/images/huu.jpg";


// Greeting based on time
$hour = date("H");
if ($hour < 12) {
    $greeting = "Good Morning ☀️";
} elseif ($hour < 18) {
    $greeting = "Good Afternoon 🌤";
} else {
    $greeting = "Good Evening 🌙";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link rel="stylesheet" href="">
    <style>
        /* Full-screen video background */
        .video-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover; /* Ensures the video covers the entire screen */
            z-index: -1; /* Places the video behind the content */
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            overflow: hidden; /* Prevents scrolling */
        }

        .account-container {
            width: 50%;
            margin: auto;
            background: rgba(0, 0, 0, 0.7); /* Dark background to contrast the video */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-top: 50px;
            z-index: 1; /* Keeps the form above the video */
        }

        .profile-section {
            margin-bottom: 20px;
        }

        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #3498db;
        }

        .email {
            font-size: 16px;
            color: #fff;
        }

        .account-options {
            margin: 20px 0;
        }

        .button {
            display: block;
            width: 80%;
            margin: 10px auto;
            padding: 12px;
            text-decoration: none;
            background: #3498db;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            transition: 0.3s;
        }

        .button:hover {
            background: #2980b9;
        }

        .logout-button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            background: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: 0.3s;
        }

        .logout-button:hover {
            background: #c0392b;
        }

        h2 {
            color: #fff; /* White text for greeting */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); /* Adds shadow to make the text more visible */
        }
    </style>
</head>
<body>

    <!-- Background Video -->
    <video autoplay muted loop class="video-background">
    <source src="http://localhost/New Dana lanka Website/videos/vecteezy_racing-car-speedometer-closeup_26651531.mp4" type="video/mp4">

        Your browser does not support the video tag.
</video>

    <div class="account-container">
        <div class="profile-section">
            <img src="<?php echo $profile_picture; ?>" alt="Profile Picture" class="profile-img">
            <h2><?php echo $greeting; ?>, <?php echo htmlspecialchars($user['full_name']); ?>! 👋</h2>
            <p class="email"><?php echo htmlspecialchars($user['email']); ?></p>
        </div>

        <div class="account-options">
            <a href="PurchaseHistory.php" class="button">🛍 Purchase History</a>
            <a href="view_order_tracking.php" class="button">📦 Track Orders</a>
            <a href="Cart.php" class="button">🛒 Shopping Cart</a>
        </div>

        <div class="logout-section">
            <a href="logout.php" class="logout-button">🚪 Logout</a>
        </div>
    </div>

</body>
</html>
