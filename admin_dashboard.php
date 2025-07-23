<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch admin name
$admin_name = $_SESSION['admin_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        /* Background Video */
        .background-video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }
        .container {
            max-width: 800px;
            margin: 100px auto;
            background: rgba(0, 0, 0, 0.6); /* Dark background with transparency */
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: relative;
            color: white; /* White text to contrast with dark background */
        }
        h1 {
            color: white;
            font-size: 2.5em;
            margin-bottom: 30px;
            font-weight: bold;
            text-transform: uppercase;
            background: #e57373; /* Subtle Red */
            padding: 10px;
            border-radius: 5px;
        }
        .links a {
            display: block;
            margin: 20px 0;
            padding: 15px 30px;
            background-color: #e57373; /* Subtle Red */
            color: white;
            text-decoration: none;
            font-size: 1.2em;
            border-radius: 8px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .links a:hover {
            background-color: #d32f2f; /* Darker Red on Hover */
            transform: scale(1.05);
        }
        .logout {
            color: #e57373; /* Subtle Red */
            font-size: 1.5em;
            margin-top: 30px;
            display: block;
            transition: color 0.3s ease;
        }
        .logout:hover {
            color: #d32f2f; /* Darker Red on Hover */
        }
    </style>
</head>
<body>
    <!-- Background Video -->
    <video class="background-video" autoplay loop muted>
    <source src="videos/96757-657131776_medium.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <div class="container">
        <h1>Welcome, <?= htmlspecialchars($admin_name) ?>!</h1>
        <div class="links">
            <a href="admin_product_management.php">Product Management</a>
            <a href="admin_update_status.php">Order Management</a>
            <a href="admin_faq_management.php">FAQ Management</a>
            <a href="admin_user_management.php">User Management</a>
        </div>
        <a href="logout.php" class="logout">Logout</a>
        <a href="admin_register.php" class="logout">Admin Register</a>
    </div>
</body>
</html>
