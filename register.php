<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "dana_auto_parts");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if email already exists
    $checkEmail = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($checkEmail->num_rows > 0) {
        echo "Email already registered.";
    } else {
        // Insert user into the database
        $sql = "INSERT INTO users (full_name, email, password) VALUES ('$full_name', '$email', '$password')";
        if ($conn->query($sql) === TRUE) {
            // Redirect to the registration success page
            header("Location: register_success.html");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

$conn->close();
?>
