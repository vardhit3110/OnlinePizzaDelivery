<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '_dbconnect.php';
session_start();

if (!isset($_SESSION['userId'])) {
    die("User  not logged in.");
}

$userId = $_SESSION['userId'];

// Fetch User's First Name and Username
$userSql = "SELECT firstName, username FROM users WHERE id=?";
$stmt = $conn->prepare($userSql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($firstName, $username);
$stmt->fetch();
$stmt->close();

// ✅ Handle Profile Picture Upload
if (isset($_POST["updateProfilePic"])) {
    if (!isset($_FILES["image"]) || $_FILES["image"]["error"] != UPLOAD_ERR_OK) {
        die("<script>alert('File upload failed. Error Code: " . $_FILES["image"]["error"] . "'); window.history.back();</script>");
    }

    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        $newfilename = "person-" . $username . ".jpg"; // Use username for filename
        $uploaddir = __DIR__ . "/../img/"; // Use relative path for compatibility
        $uploadfile = $uploaddir . $newfilename;

        if (!is_writable($uploaddir)) {
            die("<script>alert('Upload directory is not writable.'); window.history.back();</script>");
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {
            echo "<script>alert('Profile picture updated successfully!'); window.location=document.referrer;</script>";
        } else {
            echo "<script>alert('Image upload failed, please try again.'); window.location=document.referrer;</script>";
        }
    } else {
        echo '<script>alert("Please select a valid image file to upload."); window.history.back();</script>';
    }
}

// ✅ Handle Profile Picture Removal
if (isset($_POST["removeProfilePic"])) {
    $filename = __DIR__ . "/../img/person-" . $username . ".jpg"; // Use username for filename
    if (file_exists($filename)) {
        unlink($filename);
        echo "<script>alert('Profile picture removed.'); window.location=document.referrer;</script>";
    } else {
        echo "<script>alert('No profile picture found.'); window.location=document.referrer;</script>";
    }
}

// ✅ Handle Profile Details Update
if (isset($_POST["updateProfileDetail"])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    // Hash the password if it's provided
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $updateSql = "UPDATE users SET firstName=?, lastName=?, email=?, phone=?, password=? WHERE id=?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("sssssi", $firstName, $lastName, $email, $phone, $hashedPassword, $userId);
    } else {
        $updateSql = "UPDATE users SET firstName=?, lastName=?, email=?, phone=? WHERE id=?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("ssssi", $firstName, $lastName, $email, $phone, $userId);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Profile updated successfully!'); window.location=document.referrer;</script>";
    } else {
        echo "<script>alert('Profile update failed. Please try again.'); window.location=document.referrer;</script>";
    }
    $stmt->close();
}
?>