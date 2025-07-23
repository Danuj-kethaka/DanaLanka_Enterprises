<?php
session_start();
// Database connection
$conn = new mysqli("localhost", "root", "", "dana_auto_parts");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Check if user exists
    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Start session and store user info
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['email'] = $user['email']; // Store email
            // Redirect to account page
            header("Location: account.php");
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No account found with this email.";
    }
}

$conn->close();
?>
