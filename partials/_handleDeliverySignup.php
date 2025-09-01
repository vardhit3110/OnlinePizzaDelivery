<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '_dbconnect.php'; // Database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize & Validate inputs
    $delivery_boy_name = trim($_POST["db_delivery_boy_name"]);
    $firstname = trim($_POST["db_firstname"]);
    $lastname = trim($_POST["db_lastname"]);
    $email = trim($_POST["db_email"]);
    $phone = trim($_POST["db_phone"]);
    $vehicle = trim($_POST["vehicle"]);
    $password = $_POST["db_password"];
    $cpassword = $_POST["db_cpassword"];

    // Function to handle errors and redirect
    function redirectWithError($message) {
        $_SESSION['signup_error'] = $message;
        header("Location: /OnlinePizzaDelivery/index.php");
        exit();
    }

    // Validate required fields
    if (empty($delivery_boy_name) || empty($firstname) || empty($lastname) || empty($email) || empty($phone) || empty($vehicle) || empty($password) || empty($cpassword)) {
        redirectWithError("All fields are required!");
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirectWithError("Invalid email format!");
    }

    // Validate phone number format (10-digit numeric)
    if (!preg_match("/^[0-9]{10}$/", $phone)) {
        redirectWithError("Invalid phone number format! Must be exactly 10 digits.");
    }

    // Validate delivery_boy_name format (only letters, numbers, and underscores)
    if (!preg_match("/^[a-zA-Z0-9_]+$/", $delivery_boy_name)) {
        redirectWithError("Delivery boy name can only contain letters, numbers, and underscores!");
    }

    // Validate password strength (6-10 characters, 1 uppercase, 1 number, 1 special character)
    if (!preg_match("/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,10}$/", $password)) {
        redirectWithError("Password must be 6-10 characters long, contain at least one uppercase letter, one number, and one special character.");
    }

    // Check if passwords match
    if ($password !== $cpassword) {
        redirectWithError("Passwords do not match!");
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if delivery_boy_name, email, or phone already exists using Prepared Statement
    $checkQuery = "SELECT id FROM delivery_boys WHERE delivery_boy_name = ? OR email = ? OR phone = ?";
    $stmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($stmt, "sss", $delivery_boy_name, $email, $phone);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        redirectWithError("Delivery boy name, Email, or Phone number already exists!");
    }
    mysqli_stmt_close($stmt);

    // Insert new delivery boy into the database with 'pending' status
    $sql = "INSERT INTO delivery_boys (delivery_boy_name, first_name, last_name, email, phone, vehicle_type, password, status, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssss", $delivery_boy_name, $firstname, $lastname, $email, $phone, $vehicle, $hashed_password);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['signup_success'] = "Signup successful! Please wait for admin approval before logging in.";
        header("Location: /OnlinePizzaDelivery/index.php");
        exit();
    } else {
        redirectWithError("Signup failed due to a database error!");
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
