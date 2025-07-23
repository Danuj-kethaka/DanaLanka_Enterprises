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

// Handle question submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_question'])) {
    $customer_question = $conn->real_escape_string($_POST['customer_question']);
    $insert_sql = "INSERT INTO faq (question) VALUES ('$customer_question')";
    $conn->query($insert_sql);
    echo "<script>alert('Your question has been submitted!');</script>";
}

// Fetch all FAQs with answers
$faq_sql = "SELECT question, answer FROM faq WHERE answer IS NOT NULL ORDER BY created_at DESC";
$faq_result = $conn->query($faq_sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer FAQ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .form-container, .faq-container {
            width: 60%;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .form-container input, .form-container textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-container button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #45a049;
        }
        .faq-container h2 {
            text-align: center;
            color: #333;
        }
        .faq-item {
            margin-bottom: 15px;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .faq-item:last-child {
            border-bottom: none;
        }
        .faq-item p {
            margin: 5px 0;
        }
        .faq-item .question {
            font-weight: bold;
        }
        .faq-item .answer {
            color: #555;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Customer FAQ</h1>
    <div class="form-container">
        <form method="POST" action="customer_faq.php">
            <label for="customer_question">Your Question:</label>
            <textarea id="customer_question" name="customer_question" rows="4" required></textarea>
            <button type="submit" name="submit_question">Submit</button>
        </form>
    </div>

    <div class="faq-container">
        <h2>Frequently Asked Questions</h2>
        <?php
        if ($faq_result && $faq_result->num_rows > 0) {
            while ($row = $faq_result->fetch_assoc()) {
                echo "<div class='faq-item'>
                        <p class='question'>Q: {$row['question']}</p>
                        <p class='answer'>A: {$row['answer']}</p>
                      </div>";
            }
        } else {
            echo "<p>No questions have been answered yet.</p>";
        }
        ?>
    </div>
</body>
</html>
