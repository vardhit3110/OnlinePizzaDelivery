<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('partials/_dbconnect.php');

$adminName = "admin";
$email = "admin@gmail.com";
$phone = 9876543210;
$rawPassword = "Admin@123";
$hashedPassword = password_hash($rawPassword, PASSWORD_DEFAULT);
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