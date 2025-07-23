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

// Handle registration
if (isset($_POST['register'])) {
    $admin_name = $_POST['admin_name'];
    $admin_email = $_POST['admin_email'];
    $admin_password = password_hash($_POST['admin_password'], PASSWORD_BCRYPT);

    // Check if the email already exists
    $check_query = "SELECT * FROM administrators WHERE email = ?";
    $stmt_check = $conn->prepare($check_query);
    $stmt_check->bind_param("s", $admin_email);
    $stmt_check->execute();
    $check_result = $stmt_check->get_result();
    
    if ($check_result->num_rows > 0) {
        $message = "Email already exists. Please try a different one.";
    } else {
        $sql = "INSERT INTO administrators (name, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $admin_name, $admin_email, $admin_password);
        if ($stmt->execute()) {
            $message = "Registration successful! You can now log in.";
        } else {
            $message = "Registration failed. Please try again.";
        }
    }
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
    <title>Admin Login and Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
       body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f4f8;
            background-image: url('images/admin.png'); /* Use relative path */
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-wrapper {
            display: flex;
            background: rgba(0, 0, 0, 0.5); /* Transparent background with dark tint */
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3); /* Darker shadow */
            overflow: hidden;
            width: 80%;
            max-width: 900px;
        }

        .form-image {
            flex: 1;
            background-image: url('/New Dana lanka Website/images/admin.png');
            background-size: cover;
            background-position: center;
            height: auto;
        }

        .form-content {
            flex: 1;
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background-color: rgba(0, 0, 0, 0.7); /* Dark transparent background */
            color: #fff; /* White text for contrast */
        }

        h1 {
            text-align: center;
            color: #fff; /* Light color for heading */
            font-size: 28px;
            margin-bottom: 20px;
        }

        form {
            margin-top: 20px;
        }

        label {
            font-weight: 500;
            display: block;
            margin-bottom: 8px;
            color: #ddd; /* Lighter label color */
        }

        input, button {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #444; /* Darker border */
            border-radius: 8px;
            font-size: 16px;
        }

        input {
            background-color: #444; /* Dark background for inputs */
            color: #fff; /* White text in input */
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

        .form-container {
            display: none;
        }

        .form-container.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="form-wrapper">
        <div class="form-image"></div>
        <div class="form-content">
            <h1>Admin Register</h1>
            <?php if (isset($message)): ?>
                <div class="message <?= strpos($message, 'successful') !== false ? 'success' : 'error' ?>">
                    <?= $message ?>
                </div>
            <?php endif; ?>

            <!-- Toggle Button -->
            <button class="toggle-button" id="toggleButton">Switch to Login</button>

            <!-- Register Form -->
            <div class="form-container active" id="registerForm">
                <form method="POST">
                    <label for="admin_name">Name:</label>
                    <input type="text" name="admin_name" required>

                    <label for="admin_email">Email:</label>
                    <input type="email" name="admin_email" required>

                    <label for="admin_password">Password:</label>
                    <input type="password" name="admin_password" required>

                    <button type="submit" name="register">Register</button>
                </form>
            </div>

            <!-- Login Form -->
            <div class="form-container" id="loginForm">
                <h1>Admin Login</h1>
                <form method="POST">
                    <label for="admin_email">Email:</label>
                    <input type="email" name="admin_email" required>

                    <label for="admin_password">Password:</label>
                    <input type="password" name="admin_password" required>

                    <button type="submit" name="login">Login</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const toggleButton = document.getElementById('toggleButton');
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');

        toggleButton.addEventListener('click', () => {
            loginForm.classList.toggle('active');
            registerForm.classList.toggle('active');
            toggleButton.textContent = loginForm.classList.contains('active') ? 'Switch to Register' : 'Switch to Login';
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>
