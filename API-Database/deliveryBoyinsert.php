<?php
include "_dbconnect.php";

header("Content-Type:application/json");

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode([
        "Status" => "Request Error",
        "Message" => "Please change request method to POST."
    ]);
    exit();
}

$delivery_boy_name = $_POST['delivery_boy_name'];
$fname = $_POST['first_name'];
$lname = $_POST['last_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = $_POST['password'];
$password_hash = password_hash($password, PASSWORD_DEFAULT);
$vehicle = $_POST['vehicle_type'];

if (empty($delivery_boy_name) || empty($fname) || empty($lname) || empty($email) || empty($phone) || empty($password) || empty($vehicle)) {
    echo json_encode([
        "Status" => "Error",
        "Message" => "All fields are required."
    ]);
    exit();
}

$Query = "INSERT INTO delivery_boys (delivery_boy_name, first_name, last_name, email, phone, password, vehicle_type) VALUES ('$delivery_boy_name', '$fname', '$lname', '$email', '$phone', '$password_hash', '$vehicle')";
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