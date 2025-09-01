<?php
include 'partials/_dbconnect.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['feedback_id'])) {
    $feedbackId = intval($_POST['feedback_id']); // Sanitize input

    // SQL query to delete feedback
    $sql = "DELETE FROM feedback WHERE feedback_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $feedbackId);

    if ($stmt->execute()) {
        // Redirect with success message
        header("Location: index.php?page=feedbackManage&msg=deleted");
        exit();
    } else {
        // Redirect with error message
        header("Location: index.php?page=feedbackManage&msg=error");
        exit();
    }
    $stmt->close();
}
$conn->close();

// Redirect in case of an issue
header("Location: index.php?page=feedbackManage&msg=error");
exit();
?>