<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "opd"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Get form data
    $orderId = $_POST['orderId'];
    $userId = $_POST['userId'];
    $rating = $_POST['rating'];
    $complain = $_POST['complain']; // 'complain' is the name of the textarea in the form
    $delivery_boy_id = $_POST['delivery_boy_id']; // Get delivery boy ID from the form

    // Validate inputs (optional but recommended)
    if (empty($orderId) || empty($userId) || empty($rating)) {
        echo "Please fill in all required fields.";
    } else {
        // Prepare and execute the SQL query
        $stmt = $conn->prepare("INSERT INTO `reviews` (`userId`, `orderId`, `rating`, `complain`, `delivery_boy_id`) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiisi", $userId, $orderId, $rating, $complain, $delivery_boy_id);

        if ($stmt->execute()) {
            // Redirect to a thank-you page after successful submission
            header("Location: thank_you.php");
            exit(); // Ensure no further code is executed after the redirect
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }
}

// Close the database connection
$conn->close();
?>