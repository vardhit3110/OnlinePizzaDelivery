<?php
include '_dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Update user status
    if (isset($_POST['Id']) && isset($_POST['status'])) {
        $Id = $_POST["Id"];
        $status = $_POST["status"];

        $sql = "UPDATE `users` SET `status`='$status' WHERE `id`='$Id'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            echo "<script>alert('User status updated successfully');
                        window.location=document.referrer;
                    </script>";
        } else {
            echo "<script>alert('Failed to update user status');
                        window.location=document.referrer;
                    </script>";
        }
    }

    // Remove User
    if (isset($_POST['removeUser'])) {
        $Id = $_POST["Id"];
        $sql = "DELETE FROM `users` WHERE `id`='$Id'";
        $result = mysqli_query($conn, $sql);
        echo "<script>alert('User removed successfully');
                window.location=document.referrer;
                </script>";
    }

    // Create User
    if (isset($_POST['createUser'])) {
        $username = $_POST["username"];
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $email = $_POST["email"];
        $phone = $_POST["phone"];
        $password = $_POST["password"];
        $cpassword = $_POST["cpassword"];

        // Check if username exists
        $existSql = "SELECT * FROM `users` WHERE username = '$username'";
        $result = mysqli_query($conn, $existSql);
        $numExistRows = mysqli_num_rows($result);

        if ($numExistRows > 0) {
            echo "<script>alert('Username already exists');
                        window.location=document.referrer;
                    </script>";
        } else {
            if (($password == $cpassword)) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO `users` (`username`, `firstName`, `lastName`, `email`, `phone`, `password`, `joinDate`) VALUES ('$username', '$firstName', '$lastName', '$email', '$phone', '$hash', current_timestamp())";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    echo "<script>alert('User created successfully');
                                window.location=document.referrer;
                            </script>";
                } else {
                    echo "<script>alert('Failed to create user');
                                window.location=document.referrer;
                            </script>";
                }
            } else {
                echo "<script>alert('Passwords do not match');
                        window.location=document.referrer;
                    </script>";
            }
        }
    }

    // Edit User
    if (isset($_POST['editUser'])) {
        $id = $_POST["userId"];
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $email = $_POST["email"];
        $phone = $_POST["phone"];

        $sql = "UPDATE `users` SET `firstName`='$firstName', `lastName`='$lastName', `email`='$email', `phone`='$phone' WHERE `id`='$id'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            echo "<script>alert('User updated successfully');
                    window.location=document.referrer;
                    </script>";
        } else {
            echo "<script>alert('Failed to update user');
                    window.location=document.referrer;
                    </script>";
        }
    }

    // Update Profile Photo
    if (isset($_POST['updateProfilePhoto'])) {
        $id = $_POST["userId"];
        $check = getimagesize($_FILES["userimage"]["tmp_name"]);
        if ($check !== false) {
            $newfilename = "person-" . $id . ".jpg";

            $uploaddir = $_SERVER['DOCUMENT_ROOT'] . '/OnlinePizzaDelivery/img/';
            $uploadfile = $uploaddir . $newfilename;

            if (move_uploaded_file($_FILES['userimage']['tmp_name'], $uploadfile)) {
                echo "<script>alert('Profile photo updated successfully');
                            window.location=document.referrer;
                        </script>";
            } else {
                echo "<script>alert('Failed to update profile photo');
                            window.location=document.referrer;
                        </script>";
            }
        } else {
            echo '<script>alert("Please select a valid image file to upload.");
                window.location=document.referrer;
                    </script>';
        }
    }

    // Remove Profile Photo
    if (isset($_POST['removeProfilePhoto'])) {
        $id = $_POST["userId"];
        $filename = $_SERVER['DOCUMENT_ROOT'] . "/OnlinePizzaDelivery/img/person-" . $id . ".jpg";

        if (file_exists($filename)) {
            if (unlink($filename)) {
                echo "<script>alert('Profile photo removed successfully'); window.location=document.referrer;</script>";
            } else {
                echo "<script>alert('Failed to remove profile photo'); window.location=document.referrer;</script>";
            }
        } else {
            echo "<script>alert('No profile photo available to remove'); window.location=document.referrer;</script>";
        }
    }
}
?>