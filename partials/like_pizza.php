<?php
session_start();
include '_dbconnect.php';  // Your database connection file

if (!isset($_SESSION['user_id'])) {
    echo "not_logged_in";
    exit;
}

$userId = $_SESSION['user_id'];
$pizzaId = $_POST['pizzaId'];

// Check if pizza is already liked
$sql = "SELECT * FROM liked_pizzas WHERE user_id = ? AND pizza_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $userId, $pizzaId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Unlike the pizza
    $sql = "DELETE FROM liked_pizzas WHERE user_id = ? AND pizza_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $pizzaId);
    $stmt->execute();
    echo "unliked";
} else {
    // Like the pizza
    $sql = "INSERT INTO liked_pizzas (user_id, pizza_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $pizzaId);
    $stmt->execute();
    echo "liked";
}
?>