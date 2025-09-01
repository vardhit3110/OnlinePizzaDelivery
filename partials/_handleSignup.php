<?php
session_start();
$showAlert = false;
$showError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '_dbconnect.php';

    $username = trim($_POST["username"]);
    $firstName = trim($_POST["firstName"]);
    $lastName = trim($_POST["lastName"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $password = $_POST["password"];
    $cpassword = $_POST["cpassword"];



    // Prevent spaces in fields
    if (strpos($username, ' ') !== false || strpos($firstName, ' ') !== false || strpos($lastName, ' ') !== false) {
        $_SESSION['signup_error'] = "Spaces are not allowed in Username, First Name, or Last Name.";
        header("Location: /OnlinePizzaDelivery/index.php");
        exit();
    }

    // Validate name fields (only letters)
    if (!preg_match("/^[A-Za-z]+$/", $firstName) || !preg_match("/^[A-Za-z]+$/", $lastName)) {
        $_SESSION['signup_error'] = "First Name and Last Name must contain only alphabets.";
        header("Location: /OnlinePizzaDelivery/index.php");
        exit();
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['signup_error'] = "Invalid email format.";
        header("Location: /OnlinePizzaDelivery/index.php");
        exit();
    }

    // Validate phone (only 10 digits)
    if (!preg_match("/^[0-9]{10}$/", $phone)) {
        $_SESSION['signup_error'] = "Phone number must be exactly 10 digits.";
        header("Location: /OnlinePizzaDelivery/index.php");
        exit();
    }

    //Validate password (6-10 characters, one uppercase, one digit, one special character)
    if (!preg_match("/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,10}$/", $password)) {
        $_SESSION['signup_error'] = "Password must be 6-10 characters long, containing at least one uppercase letter, one number, and one special character.";
        header("Location: /OnlinePizzaDelivery/index.php");
        exit();
    }

    // Check whether this username exists
    $existSql = "SELECT * FROM `users` WHERE username = '$username'";
    $result = mysqli_query($conn, $existSql);
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['signup_error'] = "Username Already Exists";
        header("Location: /OnlinePizzaDelivery/index.php");
        exit();
    }

    // Check if passwords match
    if ($password !== $cpassword) {
        $_SESSION['signup_error'] = "Passwords do not match.";
        header("Location: /OnlinePizzaDelivery/index.php");
        exit();
    }

    // If everything is valid, hash the password and insert the data
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, firstName, lastName, email, phone, password, joinDate) 
            VALUES ('$username', '$firstName', '$lastName', '$email', '$phone', '$hash', current_timestamp())";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['signup_success'] = "Account created successfully! You can now log in.";
        header("Location: /OnlinePizzaDelivery/index.php");
        exit();
    } else {
        die("Error inserting data: " . mysqli_error($conn)); // Debugging line
    }
}
?>