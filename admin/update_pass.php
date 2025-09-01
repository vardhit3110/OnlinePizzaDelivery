<?php
include 'partials/_dbconnect.php';
session_start();

$userId = $_SESSION['userId'];

if (isset($_POST['submit'])) {
    $npass = $_POST['npass'];
    $cpass = $_POST['cpass'];

    if ($npass == $cpass) {
        $npass = password_hash($npass, PASSWORD_DEFAULT); {
            $qu = "UPDATE `users` SET `password` = '$npass' where `id` ='$userId'";
            $que = mysqli_query($conn, $qu);
            header('location:index.php');
        }
    } else {
        $msg = "Comfirm password does not match compare to new password";
    }
}

?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"
        integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <title>password</title>
    <link rel="icon" href="img/logo.jpg" type="image/x-icon">
</head>

<body class="animsition">
    <div class="page-wrapper">
        <div class="page-content--bge5">
            <div class="container">
                <div class="login-wrap">
                    <div class="login-content">
                        <div class="login-logo">
                            <center>
                                <h2 class="text-info">Update Password</h2>
                            </center>
                        </div>
                        <?php if (isset($msg)) { ?>
                            <div class="alert alert-danger">
                                <?php echo $msg; ?>
                            </div>
                        <?php } ?>
                        <div class="login-form">
                            <form method="post">
                                <div class="form-group">
                                    <label>New Password</label>
                                    <input class="au-input au-input--full" type="password" name="npass"
                                        placeholder="New Password" Required>
                                </div>

                                <div class="form-group">
                                    <label>Comfirm Password</label>
                                    <input class="au-input au-input--full" type="password" name="cpass"
                                        placeholder="Comfirm Password" required>
                                </div>
                                <button name="submit" class="au-btn au-btn--block au-btn--green m-b-20"
                                    type="submit">submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
    <script src="https://unpkg.com/bootstrap-show-password@1.2.1/dist/bootstrap-show-password.min.js"></script>
</body>

</html>



<!-- end document-->
<!-- <div class="form-group">
    <div class="row">
        <div class="col">
            <label>New Password:- </label>
            <input class="form-control" type="password" name="npass" placeholder="New Password" Required>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col">
            <label>Confirm New Password:-</label>
            <input type="password" name="cpass" placeholder="Comfirm Password" class="form-control" Required>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
    </div>
    <div class="col-sm-6">
        <button class="btn btn-success" name="submit" type="submit">Change Password</button>
    </div>
</div> -->