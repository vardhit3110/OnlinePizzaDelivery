<?php
// Start the session if it hasn't been started already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '_dbconnect.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["loginusername"];
    $password = $_POST["loginpassword"];

    // **Check in users table first**
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['userId'] = $row['id'];
            $_SESSION['role'] = 'user';
            header("location: /OnlinePizzaDelivery/index.php?loginsuccess=true");
            exit();
        }
    }

    // Check in `delivery_boys` table for delivery boys
    $sql = "SELECT * FROM delivery_boys WHERE delivery_boy_name = ? AND status = 'approved'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['deliveryboy_id'] = $row['id'];  // Use consistent variable names
            $_SESSION['deliveryboy_name'] = $row['first_name']; // Store name
            $_SESSION['role'] = 'delivery_boy';

            // Redirect to delivery boy dashboard
            header("Location: /OnlinePizzaDelivery/deliveryboy/index.php?loginsuccess=true");
            exit();
        }
    }

    // If no match found, redirect with an error
    header("location: /OnlinePizzaDelivery/index.php?loginsuccess=false&loginerror=Invalid credentials");
    exit();
}
?>