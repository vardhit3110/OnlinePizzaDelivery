<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '_dbconnect.php';
    session_start(); // Start session at the beginning

    // Secure email input to prevent SQL Injection
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = $_POST["password"];

    // Fetch admin details using email
    $sql = "SELECT * FROM admin WHERE email='$email' AND status='active'"; 
    $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);

    if ($num == 1) {
        $row = mysqli_fetch_assoc($result);

        // Check if password is correctly hashed before verifying
        if (isset($row['password']) && password_verify($password, $row['password'])) { 
            $_SESSION['adminloggedin'] = true;
            $_SESSION['adminemail'] = $row['email'];  // Store email
            $_SESSION['adminId'] = $row['id']; // Store Admin ID
            $_SESSION['adminName'] = $row['adminName']; // Store Admin Name

            header("location: /OnlinePizzaDelivery/admin/index.php?loginsuccess=true");
            exit();
        } else {
            header("location: /OnlinePizzaDelivery/admin/login.php?loginsuccess=false");
            exit();
        }
    } else {
        header("location: /OnlinePizzaDelivery/admin/login.php?loginsuccess=false");
        exit();
    }
}
?>