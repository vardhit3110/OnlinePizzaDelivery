<?php include 'partials/_dbconnect.php';

// $userId = $_SESSION['userId'];
// if(!isset($_SESSION["login_sess"])) 
// {
//     header("location:login.php"); 
// }
session_start();
//   $email=$_SESSION["email"];

$userId = $_SESSION['userId'];

?>
<!DOCTYPE html>
<html>

<head>
    <title>Change Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
            </div>
            <div class="col-sm-6">

                <form action="" method="POST">
                    <div class="login_form">

                        <img src="https://technosmarter.com/assets/images/logo.png" alt="Techno Smarter"
                            class="logo img-fluid"> <br> <?php
                            if (isset($_POST['change_password'])) {
                                $currentPassword = $_POST['currentPassword'];
                                $npassword = $_POST['npassword'];
                                $passwordConfirm = $_POST['passwordConfirm'];
                                $sql = "SELECT * FROM `users` WHERE `id` ='$userId'";
                                $res = mysqli_query($conn, $sql);
                                $res = mysqli_query($conn, $sql);
                                $row = mysqli_fetch_assoc($res);

                                if (password_verify($currentPassword, $row['password'])) {
                                    if ($passwordConfirm == '') {
                                        $error[] = 'Please confirm the password.';
                                    }
                                    if ($npassword != $passwordConfirm) {
                                        $error[] = 'Passwords do not match.';
                                    }
                                    if (strlen($npassword) < 5) { // min 
                                        $error[] = 'The password is 6 characters long.';
                                    }

                                    if (strlen($npassword) > 20) { // Max 
                                        $error[] = 'Password: Max length 20 Characters Not allowed';
                                    }
                                    if (!isset($error)) {
                                        $options = array("cost" => 4);
                                        $password = password_hash($password, PASSWORD_DEFAULT, $options);

                                        $result = mysqli_query($conn, "UPDATE users SET npassword='$npassword' WHERE `id` ='$userId'");
                                        if ($result) {
                                            header("location:index.php?password_updated=1");
                                        } else {
                                            $error[] = 'Something went wrong';
                                        }
                                    }
                                } else {
                                    $errors[] = 'Current password does not match.';
                                }
                            }
                            if (isset($errors)) {

                                foreach ($errors as $error) {
                                    echo '<p class="errmsg">' . $error . '</p>';
                                }
                            }
                            ?>
                        <form method="post" enctype='multipart/form-data' action="">
                            <div class="row">
                                <div class="col"></div>

                                <div class="col">
                                    <p><a href="logout.php"><span style="color:red;">Logout</span> </a></p>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label>Current Password:- </label>
                                        <input type="password" name="currentPassword" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label>New Password:- </label>
                                        <input type="password" name="npassword" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label>Confirm New Password:-</label>
                                        <input type="password" name="passwordConfirm" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                </div>
                                <div class="col-sm-6">
                                    <button class="btn btn-success" name="change_password">Change Password</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-3">
                    </div>
            </div>
        </div>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>

</html>