<?php
include "_dbconnect.php";

header("Content-Type:application/json");

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode([
        "Status" => "Request Error",
        "Message" => "Please Change Request Method"
    ]);
    exit();
}

$adminName = $_POST['adminName'];


$path = $_FILES['profilePic']['name'];
$tmp_name = $_FILES['profilePic']['tmp_name'];
$imagePath = "./adminProfile/" . $path;
move_uploaded_file($tmp_name, $imagePath);
    
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = $_POST['password'];
$password_hash = password_hash($password, PASSWORD_DEFAULT);

if (empty($adminName) || empty($email) || empty($phone) || empty($password)) {
    echo json_encode([
        "Status" => "Error",
        "Message" => "All fields are required."
    ]);
    exit();
}

$Query = "INSERT INTO admin (adminName, profilePic, email, phone, password) VALUES ('$adminName', '$imagePath', '$email', '$phone', '$password_hash')";
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