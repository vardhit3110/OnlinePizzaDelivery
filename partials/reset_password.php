<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '_dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate email
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "Invalid email format.";
        header("Location: /OnlinePizzaDelivery/index.php");
        exit();
    }

    // Sanitize and validate phone number
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING); // Use string to preserve leading zeros
    if (!preg_match('/^\d{10}$/', $phone)) { // Check for exactly 10 digits
        $_SESSION['message'] = "Invalid phone number. It should be exactly 10 digits.";
        header("Location: /OnlinePizzaDelivery/index.php");
        exit();
    }

    // Get new password and confirm password
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Check if passwords match
    if ($newPassword !== $confirmPassword) {
        $_SESSION['message'] = "Passwords do not match.";
        header("Location: /OnlinePizzaDelivery/index.php");
        exit();
    }

    // Validate password strength
    // if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $newPassword)) {
    //     $_SESSION['message'] = "Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
    //     header("Location: /OnlinePizzaDelivery/index.php");
    //     exit();
    // }

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Check users table
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND phone = ?");
    $stmt->bind_param("si", $email, $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found, update password in users table
        $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ? AND phone = ?");
        $updateStmt->bind_param("ssi", $hashedPassword, $email, $phone);
        if ($updateStmt->execute()) {
            $_SESSION['message'] = "Password updated successfully.";
        } else {
            $_SESSION['message'] = "Error updating password.";
        }
        $updateStmt->close();
    } else {
        // Check delivery_boys table
        $stmt = $conn->prepare("SELECT * FROM delivery_boys WHERE email = ? AND phone = ?");
        $stmt->bind_param("ss", $email, $phone);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Delivery boy found, update password in delivery_boys table
            $updateStmt = $conn->prepare("UPDATE delivery_boys SET password = ? WHERE email = ? AND phone = ?");
            $updateStmt->bind_param("ssi", $hashedPassword, $email, $phone);
            if ($updateStmt->execute()) {
                $_SESSION['message'] = "Password updated successfully.";
            } else {
                $_SESSION['message'] = "Error updating password.";
            }
            $updateStmt->close();
        } else {
            $_SESSION['message'] = "No account found with that email and phone number.";
        }
    }

    $stmt->close();
    header("Location: /OnlinePizzaDelivery/index.php");
    exit();
}
$conn->close();
?>