<?php
// Database connection to the 'user_management' database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dana_auto_parts";  // Connect to the user_management database

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle user deletion with confirmation
if (isset($_GET['delete_id'])) {
    $user_id = $_GET['delete_id'];
    // Add confirmation before deletion
    if (isset($_GET['confirm']) && $_GET['confirm'] == 'yes') {
        $delete_sql = "DELETE FROM users WHERE id=$user_id";
        $conn->query($delete_sql);
        header("Location: admin_user_management.php");
        exit;
    } else {
        echo "<script>
                if (confirm('Are you sure you want to delete this user?')) {
                    window.location.href = 'admin_user_management.php?delete_id=$user_id&confirm=yes';
                } else {
                    window.location.href = 'admin_user_management.php';
                }
              </script>";
    }
}

// Handle adding a new user
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Simple password hashing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $insert_sql = "INSERT INTO users (full_name, email, password, status) VALUES ('$full_name', '$email', '$hashed_password', 'active')";
    if ($conn->query($insert_sql) === TRUE) {
        echo "New user added successfully!";
    } else {
        echo "Error: " . $insert_sql . "<br>" . $conn->error;
    }
    header("Location: admin_user_management.php");
    exit;
}

// Pagination logic
$limit = 10;  // Number of users per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch users from the database with pagination
$sql = "SELECT * FROM users ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Get the total number of users for pagination
$total_sql = "SELECT COUNT(*) as total FROM users";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_users = $total_row['total'];
$total_pages = ceil($total_users / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="style.css">
    <style>
body, html {
    height: 100%; /* Ensure the body and html take up the full height of the viewport */
    margin: 0;
    padding: 0;
}

body {
    font-family: Arial, sans-serif;
    background-image: url("images/hee.jpg"); /* Ensure the background image exists */
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    color: #333;
}

h1 {
    text-align: center;
    color: #fff; /* White text for contrast */
    margin-top: 20px;
    background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
    padding: 20px;
    border-radius: 5px;
    font-size: 2em;
}

form {
    width: 100%;
    max-width: 600px;
    margin: 20px auto;
    background-color: rgba(255, 255, 255, 0.8); /* Light background with transparency */
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.15);
    display: flex;
    flex-direction: column;
    gap: 15px;
}

input[type="text"],
input[type="email"],
input[type="password"] {
    padding: 12px;
    font-size: 1em;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #fff;
    transition: border 0.3s;
}

input[type="text"]:focus,
input[type="email"]:focus,
input[type="password"]:focus {
    border-color: #4CAF50;
    outline: none;
    box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
}

button {
    padding: 12px;
    font-size: 1.2em;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #45a049;
}

button:active {
    background-color: #388e3c;
}

form input, form button {
    width: 100%;
}

@media (max-width: 600px) {
    h1 {
        font-size: 1.5em;
        padding: 15px;
    }

    form {
        padding: 20px;
        margin: 10px;
    }
}

table {
    width: 90%;
    margin: 20px auto;
    border-collapse: collapse;
    background-color: white;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

th, td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #f2f2f2;
    font-weight: bold;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f1f1f1;
}

.status-active {
    color: #4CAF50;
    font-weight: bold;
    text-transform: uppercase;
}

.status-inactive {
    color: #f44336;
    font-weight: bold;
    text-transform: uppercase;
}

a {
    text-decoration: none;
    color: #4CAF50;
    padding: 8px 16px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

a:hover {
    background-color: #45a049;
    color: white;
}

a.delete {
    color: #f44336;
}

a.delete:hover {
    background-color: #e53935;
}

.pagination {
    text-align: center;
    margin-top: 20px;
}

.pagination a {
    padding: 10px 20px;
    margin: 0 5px;
    background-color: #4CAF50;
    color: white;
    border-radius: 50%;
    transition: background-color 0.3s ease;
}

.pagination a:hover {
    background-color: #45a049;
}

.pagination a.active {
    background-color: #45a049;
    pointer-events: none;
}

    </style>
</head>
<body>
    <h1>User Management</h1>

    <!-- Form to add a new user -->
    <div style="text-align: center; margin-bottom: 20px;">
        <form method="POST" action="admin_user_management.php">
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="add_user">Add User</button>
        </form>
    </div>

    <table>
        <tr>
            <th>Full Name</th>
            <th>Email</th>
            <th>Created At</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $status_class = $row['status'] == 'active' ? 'status-active' : 'status-inactive';
                echo "<tr>
                        <td>{$row['full_name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['created_at']}</td>
                        <td class='$status_class'>{$row['status']}</td>
                        <td>
                            <a href='admin_user_management.php?delete_id={$row['id']}'>Delete</a>
                        </td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No users found.</td></tr>";
        }
        ?>
    </table>

    <!-- Pagination -->
    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="admin_user_management.php?page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>
</body>
</html>

<?php $conn->close(); ?>
