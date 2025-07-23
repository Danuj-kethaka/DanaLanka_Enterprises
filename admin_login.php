<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dana_auto_parts";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login
if (isset($_POST['login'])) {
    $admin_email = $_POST['admin_email'];
    $admin_password = $_POST['admin_password'];

    $sql = "SELECT * FROM administrators WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $admin_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($admin_password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $message = "Invalid email or password.";
        }
    } else {
        $message = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background: url('images/admin.png') no-repeat center center fixed; 
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-wrapper {
            display: flex;
            background: rgba(0, 0, 0, 0.7); /* Dark overlay for better contrast */
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            width: 80%;
            max-width: 900px;
            padding: 30px;
        }

        .form-image {
            flex: 1;
            background-image: url('images/admin.png'); /* Relative path for form image */
            background-size: cover;
            background-position: center;
            height: auto;
        }

        .form-content {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: #fff; /* White text for better readability */
        }

        h1 {
            text-align: center;
            font-size: 30px;
            margin-bottom: 20px;
            color: #f1f1f1;
        }

        form {
            margin-top: 20px;
        }

        label {
            font-weight: 500;
            display: block;
            margin-bottom: 8px;
            color: #f1f1f1;
        }

        input, button {
            width: 100%;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            color: #333;
        }

        input {
            background-color: rgba(255, 255, 255, 0.8); /* Lighter input fields */
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: 600;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 8px;
            font-weight: 500;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .toggle-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            width: 100%;
        }

        .toggle-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="form-wrapper">
        <div class="form-image"></div>
        <div class="form-content">
            <h1>Admin Login</h1>
            <?php if (isset($message)): ?>
                <div class="message <?= strpos($message, 'successful') !== false ? 'success' : 'error' ?>">
                    <?= $message ?>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form method="POST">
                <label for="admin_email">Email:</label>
                <input type="email" name="admin_email" required>

                <label for="admin_password">Password:</label>
                <input type="password" name="admin_password" required>

                <button type="submit" name="login">Login</button>
            </form>

        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
