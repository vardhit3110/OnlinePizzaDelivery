<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include '_dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $rating = mysqli_real_escape_string($conn, $_POST['rating']);
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    // Check if the user is already registered
    $checkUser = "SELECT id FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $checkUser);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $user_id = $row['id'];

        // ✅ Insert feedback including email
        $sql = "INSERT INTO feedback (user_id, rating, comment, submission_date, status) 
        VALUES ('$user_id', '$rating', '$comment', NOW(), 'pending')";$insertResult = mysqli_query($conn, $sql);

        if ($insertResult) {
            echo "<script>alert('Thank you for your feedback!'); window.location.href='../feedback.php';</script>";
        } else {
            echo "<script>alert('Error submitting feedback. Please try again.'); window.location.href='../feedback.php';</script>";
        }
    } else {
        // User not registered
        echo "<script>alert('You need to register first before submitting feedback!'); window.location.href='../feedback.php';</script>";
    }
}
?>

