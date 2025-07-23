<?php
// Include database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dana_auto_parts";

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch unanswered FAQs
$sql = "SELECT * FROM faq WHERE answer IS NULL ORDER BY created_at DESC";
$result = $conn->query($sql);

// Handle FAQ answering
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_answer'])) {
    $faq_id = $_POST['faq_id'];
    $answer = $conn->real_escape_string($_POST['answer']);
    $update_sql = "UPDATE faq SET answer='$answer' WHERE id=$faq_id";
    $conn->query($update_sql);
    header("Location: admin_faq_management.php");
    exit;
}

// Handle FAQ deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM faq WHERE id=$delete_id";
    $conn->query($delete_sql);
    header("Location: admin_faq_management.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin FAQ Management</title>
    <style>
      html, body {
    height: 100%; /* Ensure the body takes up the full height of the viewport */
    margin: 0;
    padding: 0;
}

body {
    font-family: Arial, sans-serif;
    background-image: url("images/aha.jpg");
    background-size: cover; /* Ensure the image covers the entire background */
    background-position: center; /* Center the background image */
    background-repeat: no-repeat; /* Prevent image repetition */
}

h1 {
    text-align: center;
    color: #fff; /* Make the text white for better contrast */
    margin-top: 20px;
    background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background for better visibility */
    padding: 10px;
    border-radius: 5px;
}

.container {
    width: 80%;
    margin: 20px auto;
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #ddd;
        }
        table th {
            background-color:rgb(21, 23, 163);
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tr:hover {
            background-color: #f1f1f1;
        }
        .action-btn {
            padding: 8px 12px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .submit-btn {
            background-color: #4CAF50;
        }
        .submit-btn:hover {
            background-color: #45a049;
        }
        .delete-btn {
            background-color: #f44336;
        }
        .delete-btn:hover {
            background-color: #e53935;
        }
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>Admin FAQ Management</h1>
    <div class="container">
        <table>
            <tr>
                <th>Question</th>
                <th>Answer</th>
                <th>Actions</th>
            </tr>
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['question']}</td>
                            <td>
                                <form method='POST' action='admin_faq_management.php'>
                                    <input type='hidden' name='faq_id' value='{$row['id']}'>
                                    <textarea name='answer' rows='2' required></textarea>
                                    <button type='submit' name='submit_answer' class='action-btn submit-btn'>Submit Answer</button>
                                </form>
                            </td>
                            <td>
                                <a href='admin_faq_management.php?delete_id={$row['id']}' class='action-btn delete-btn'>Delete</a>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No unanswered questions.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
