<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('partials/_dbconnect.php');

$adminName = "vardhit";
$email = "vamjavardhit461@gmail.com";
$phone = 8238648728;
$rawPassword = "Vardhit@3110"; // User input password
$hashedPassword = password_hash($rawPassword, PASSWORD_DEFAULT); // Hashing password
$status = "active";

$sql = "INSERT INTO admin (adminName, email, phone, password, status) 
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssiss", $adminName, $email, $phone, $hashedPassword, $status);

if ($stmt->execute()) {
    echo "Admin successfully added!";
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
?>