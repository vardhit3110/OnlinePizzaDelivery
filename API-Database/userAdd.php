<?php
include "_dbconnect.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode([
        "Status" => "Request Error",
        "Message" => "Please change request method to POST."
    ]);
    exit();
}

// Get data safely
$username = $_POST['username'] ?? '';
$firstName = $_POST['firstName'] ?? '';
$lastName = $_POST['lastName'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? '';
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Validate required fields BEFORE proceeding
if (empty($username) || empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($password)) {
    echo json_encode([
        "Status" => "Error",
        "Message" => "All fields are required."
    ]);
    exit();
}

// Check if email or phone already exists
$checkQuery = "SELECT * FROM users WHERE email = '$email' OR phone = '$phone'";
$checkResult = mysqli_query($conn, $checkQuery);
if (mysqli_num_rows($checkResult) > 0) {
    echo json_encode([
        "Status" => "Error",
        "Message" => "User already registered with this email or phone number."
    ]);
    exit();
}

// Handle file upload
$path = $_FILES['profilePic']['name'] ?? '';
$tmp_name = $_FILES['profilePic']['tmp_name'] ?? '';

if (empty($path)) {
    echo json_encode([
        "Status" => "Error",
        "Message" => "Profile picture is required."
    ]);
    exit();
}

// Create unique filename using username
$ext = pathinfo($path, PATHINFO_EXTENSION);
$filename = "person-" . $username . "." . $ext;
$uploadfile = "../img/" . $filename;

if (!move_uploaded_file($tmp_name, $uploadfile)) {
    echo json_encode([
        "Status" => "Error",
        "Message" => "Failed to upload profile picture."
    ]);
    exit();
}

// Insert into database
$Query = "INSERT INTO users (username, firstName, lastName, profilePic, email, phone, password) 
          VALUES ('$username', '$firstName', '$lastName', '$filename', '$email', '$phone', '$password_hash')";

$ApiData = mysqli_query($conn, $Query);

if ($ApiData) {
    echo json_encode([
        "Status" => "Success",
        "Message" => "Record inserted successfully."
    ]);
} else {
    echo json_encode([
        "Status" => "Error",
        "Message" => "Failed to insert record."
    ]);
}
?>