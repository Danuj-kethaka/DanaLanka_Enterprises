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

// Handle form submission to update order status
$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['order_status'];
    $sql = "UPDATE purchase_history SET order_status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_status, $order_id);
    if ($stmt->execute()) {
        $message = "Order status updated successfully!";
    } else {
        $message = "Failed to update order status.";
    }
}

// Fetch all orders to display in the dropdown
$sql = "SELECT * FROM purchase_history ORDER BY purchase_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Product Status Change</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #fff;
        }

        /* Background video styling */
        video {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }

        /* Banner styling */
        .banner {
            background: linear-gradient(to right, #ff7e5f, #feb47b);
            color: #fff;
            text-align: center;
            padding: 20px;
            font-size: 32px;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
            border-bottom: 4px solid #ff4e00;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background: rgba(0, 0, 0, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
        }

        h1 {
            text-align: center;
            color: #fff;
        }

        form {
            margin-top: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        select {
            background: #333;
            color: #fff;
        }

        button {
            background-color: #ff4e00;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }

        button:hover {
            background-color: #e64500;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
        }

        .success {
            background-color: #28a745;
            color: #fff;
        }

        .error {
            background-color: #dc3545;
            color: #fff;
        }
    </style>
</head>
<body>
    <!-- Background Video -->
    <video autoplay muted loop>
    <source src="videos/4788-180289892.mp4" type="video/mp4">


        Your browser does not support the video tag.
    </video>

    <div class="banner">
        Admin - Product Status Change
    </div>
    <div class="container">
    <h1>Update Order Status</h1>
    <?php if ($message): ?>
        <div id="statusMessage" class="message <?= strpos($message, 'successfully') !== false ? 'success' : 'error' ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>
    <form method="POST">
        <label for="order_id">Select Order:</label>
        <select name="order_id" required>
            <?php while ($row = $result->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>">
                    Order ID: <?= $row['id'] ?> - <?= $row['product_name'] ?>
                </option>
            <?php endwhile; ?>
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
</div>

<script>
    // Automatically hide the message after 5 seconds
    const statusMessage = document.getElementById('statusMessage');
    if (statusMessage) {
        setTimeout(() => {
            statusMessage.style.display = 'none';
        }, 5000); // 5000ms = 5 seconds
    }
</script>

</body>
</html>

<?php
$conn->close();
?>
