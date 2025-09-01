<?php
include('partials/_dbconnect.php');
@session_start();

// Restore session if lost
if (!isset($_SESSION['loggedin'])) {
    if (isset($_COOKIE['deliveryboy_id'])) {
        $_SESSION['loggedin'] = true;
        $_SESSION['role'] = 'delivery_boy';
        $_SESSION['deliveryboy_id'] = $_COOKIE['deliveryboy_id'];
    }
}

// Check if the user is logged in as a delivery boy
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'delivery_boy') {
    $deliveryboyId = $_SESSION['deliveryboy_id'];
    $query = "SELECT first_name, last_name, email, phone, delivery_boy_name FROM delivery_boys WHERE id = '$deliveryboyId'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $email = $row['email'];
    $phone = $row['phone'];
    $username = $row['delivery_boy_name'];
} else {
    header("location: /OnlinePizzaDelivery/index.php");
    exit();
}

// Handle form submission for updating delivery boy information
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updatedbp'])) {
    $new_first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $new_last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $new_email = mysqli_real_escape_string($conn, $_POST['email']);
    $new_phone = mysqli_real_escape_string($conn, $_POST['phone']);

    // Update the delivery boy's information in the database
    $update_query = "UPDATE delivery_boys SET 
                     first_name = '$new_first_name', 
                     last_name = '$new_last_name', 
                     email = '$new_email', 
                     phone = '$new_phone' 
                     WHERE id = '$deliveryboyId'";

    if (mysqli_query($conn, $update_query)) {
        // Update successful, refresh the page to show updated data
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

// Handle Profile Picture Upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['uploadProfilePic'])) {
    if (!isset($_FILES["profile_photo"]) || $_FILES["profile_photo"]["error"] != UPLOAD_ERR_OK) {
        die("<script>alert('File upload failed. Error Code: " . $_FILES["profile_photo"]["error"] . "'); window.history.back();</script>");
    }

    $check = getimagesize($_FILES["profile_photo"]["tmp_name"]);
    if ($check !== false) {
        $newfilename = "delivery_boy_" . $username . ".jpg";
        $uploaddir = __DIR__ . "/upload/"; // Use relative path for compatibility
        $uploadfile = $uploaddir . $newfilename;

        if (!is_writable($uploaddir)) {
            die("<script>alert('Upload directory is not writable.'); window.history.back();</script>");
        }

        if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $uploadfile)) {
            echo "<script>alert('Profile picture updated successfully!'); window.location=document.referrer;</script>";
        } else {
            echo "<script>alert('Image upload failed, please try again.'); window.location=document.referrer;</script>";
        }
    } else {
        echo '<script>alert("Please select a valid image file to upload."); window.history.back();</script>';
    }
}

// Handle Profile Picture Removal
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['removeProfilePic'])) {
    $filename = __DIR__ . "/upload/delivery_boy_" . $username . ".jpg";
    if (file_exists($filename)) {
        unlink($filename);
        echo "<script>alert('Profile picture removed.'); window.location=document.referrer;</script>";
    } else {
        echo "<script>alert('No profile picture found.'); window.location=document.referrer;</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Delivery Profile</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
    <style>
        /* Your existing styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 10px;
        }

        .container {
            display: flex;
            justify-content: center;
            gap: 70px;
        }

        .box {
            background: white;
            padding: 10px;
            width: 400px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 25px;
            text-align: left;
            background-color: aliceblue;
        }

        .box1 {
            background: white;
            padding: 10px;
            width: 300px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 50px;
            text-align: left;
            background-color: aliceblue;
        }

        img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            margin: 10px auto;
            border: 1px solid black;
        }

        label {
            font-weight: bold;
            font-size: 14px;
        }

        input,
        button {
            display: block;
            margin-top: 5px;
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 12px;
        }

        button {
            color: white;
            border: none;
            cursor: pointer;
            background-color: #28a745;
        }

        button:hover {
            background-color: #218838;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 15px;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            /* Buttons ko evenly space karega */
            gap: 10px;
            /* Buttons ke beech mein space */
        }

        .upload-btn-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }

        .forgot-password {
            font-size: 12px;
            color: blue;
            cursor: pointer;
            text-decoration: underline;
        }

        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            text-align: center;
        }

        .popup input {
            margin: 10px 0;
            padding: 5px;
            width: 100%;
        }
    </style>
</head>

<body>
    <h2 style="font-family: 'Poppins', sans-serif; font-weight: 600;">Delivery Boy Profile</h2>
    <div class="container">
        <div class="box1">
            <div class="profile-header"><br>
                <h3 style="font-family: 'Poppins', sans-serif; font-size: 25px;">Profile Photo</h3>
            </div>
            <img src="upload/delivery_boy_<?php echo $username; ?>.jpg" alt="Profile Photo"
                onError="this.src='upload/profilePic.jpg'">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="button-container">
                    <div class="upload-btn-wrapper">
                        <input type="file" name="profile_photo" id="profile_photo" accept="image/*"
                            style="font-family: 'Josefin Sans'">
                        <button type="submit" class="upload-button"
                            style="background-color: #28a745; width: 70px; height: 27px; border-radius: 10px; font-size: 13px; "
                            name="uploadProfilePic"><b>Upload</b></button>
                        <button type="submit" class="upload-button"
                            style="background-color:rgb(255, 0, 0); width: 70px; height: 27px; border-radius: 10px; font-size: 13px;"
                            name="removeProfilePic"><b>Remove</b></button>
                    </div>
                </div>
            </form>
            <div style="text-align: center; color: black; font-family: 'Josefin Sans'">
                <br>
                <?php echo htmlspecialchars($first_name); ?>
                <?php echo htmlspecialchars($last_name); ?>
            </div>
            <div
                style="text-align: center; color: black; font-family: 'Josefin Sans', sans-serif; letter-spacing: 2px;">
                <?php echo htmlspecialchars($email); ?>
            </div>
            <br>
        </div>
        <form action="" method="post">
            <div class="box">
                <div class="profile-header"><br>
                    <h3 style="">Update Information</h3>
                </div>
                <label style="font-family: 'Josefin Sans'; font-weight: bold; font-size: 17px;">First Name:</label>
                <input type="text" name="first_name"
                    style="color: black; font-family: 'Josefin Sans', sans-serif; letter-spacing: 2px; font-size: 16px;"
                    value="<?php echo htmlspecialchars($first_name); ?>"><br>
                <label style="font-family: 'Josefin Sans'; font-weight: bold; font-size: 17px;">Last Name:</label>
                <input type="text" name="last_name"
                    style="color: black; font-family: 'Josefin Sans', sans-serif; letter-spacing: 2px; font-size: 16px;"
                    value="<?php echo htmlspecialchars($last_name); ?>"><br>
                <label style="font-family: 'Josefin Sans'; font-weight: bold; font-size: 17px;">Email:</label>
                <input type="email" name="email"
                    style="color: black; font-family: 'Josefin Sans', sans-serif; letter-spacing: 2px; font-size: 16px;"
                    value="<?php echo htmlspecialchars($email); ?>"><br>
                <label style="font-family: 'Josefin Sans'; font-weight: bold; font-size: 17px;">Phone:</label>
                <input type="text" name="phone"
                    style="color: black; font-family: 'Josefin Sans', sans-serif; letter-spacing: 2px; font-size: 16px;"
                    value="<?php echo htmlspecialchars($phone); ?>"><br>
                <div style="display: flex; justify-content: center;">
                    <button type="submit" class="upload-button"
                        style="width: 120px; height: 30px; border-radius: 10px; font-size: 15px; font-family: 'Josefin Sans'"
                        name="updatedbp">
                        <b>Update Profile</b>
                    </button>
                </div>
            </div>
        </form>
    </div>

</body>

</html>
<script>
    window.onclick = function (event) {
        var popup = document.getElementById('forgot-popup');
        if (event.target == popup) {
            popup.style.display = "none";
        }
    }

    $('#profile_photo').change(function () {
        var i = $(this).prev('button').clone();
        var file = ($('#profile_photo')[0].files[0].name).substring(0, 5) + "..";
        $(this).prev('button').text(file);
    });
</script>