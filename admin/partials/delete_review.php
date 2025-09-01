<?php
include '_dbconnect.php'; // Database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reviewId'])) {
    $reviewId = intval($_POST['reviewId']); // Sanitize input

    // SQL query to delete review
    $sql = "DELETE FROM reviews WHERE reviewId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reviewId);

    if ($stmt->execute()) {
        // Redirect with success message
        header("Location: http://localhost/OnlinePizzaDelivery/admin/index.php?page=deliveryBoyReviews&msg=deleted");
        exit();
    } else {
        // Redirect with error message
        header("Location: http://localhost/OnlinePizzaDelivery/admin/index.php?page=deliveryBoyReviews&msg=error");
        exit();
    }
    $stmt->close();
}
$conn->close();

// Redirect in case of an issue
header("Location: http://localhost/OnlinePizzaDelivery/admin/index.php?page=deliveryBoyReviews&msg=error");
exit();
?>
