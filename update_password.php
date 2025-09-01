<?php include 'partials/_dbconnect.php';
session_start();

$userId = $_SESSION['userId'];

if (isset($_POST['submit'])) {
    $npass = $_POST['npass'];
    $cpass = $_POST['cpass'];

    if ($npass == $cpass) {
        $npass = password_hash($npass, PASSWORD_DEFAULT); {
            $qu = "UPDATE `users` SET `password` = '$npass' where `id` ='$userId'";
            $que = mysqli_query($conn, $qu);
            header('location:viewProfile.php');
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

<style>
    body{
		width: 100%;
	    height: calc(100%);
	    /*background: #007bff;*/
        background-image: url("/OnlinePizzaDelivery/img/pizzapass1.png");
        background-size: 1500px 900px;
        background-repeat: no-repeat;
	}
	main#main{
		width:100%;
		height: calc(100%);
		background:white;
	}
	#login-right{
		position: absolute;
		right:0;
		width:40%;
		height: calc(100%);
		background:white;
		display: flex;
		align-items: center;
	}
    </style>

<body class="animsition">
    <div class="page-wrapper">
        <div class="page-content--bge5">
            <div class="container">
                <div class="login-wrap">
                    <div class="login-content">
                        <br>
                        <div class="login-logo">
                            <center>
                                <!-- <h2 class="text-info"><b>Update Password</b></h2> -->
                            </center>
                            
                            <p><a href="viewProfile.php" style="color:white;">Back To..</a></p>
                            
                        </div>
                        <br>
                        <?php if (isset($msg)) { ?>
                            <div class="alert alert-danger">
                                <?php echo $msg; ?>
                            </div>
                        <?php } ?>

                        <!-- <div class="login-form">
                            <form method="post">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col">
                                            <label>New Password</label>
                                            <input type="password" name="npass" placeholder="New Password"
                                                class="form-control" Required data-toggle="password">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col">
                                            <label>Comfirm Password</label>
                                            <input type="password" name="cpass" placeholder="Comfirm Password"
                                                class="form-control" Required data-toggle="password">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                    </div>
                                    <div class="col-sm-6">
                                        <button name="submit" class="btn btn-success" type="submit">submit</button>
                                    </div>
                                </div>
                            </form>
                        </div> -->
                        <!-- ============================= -->
                        <center><div id="login-form">
        <div class="card col-md-6">
            <div class="card-body">
            <form action="#" method="post">
                <div class="form-group">
                <label for="New Password" class="control-label"><b>New Password</b></label>
                <input type="password" id="npass" name="npass" class="form-control" placeholder="New Password" Required data-toggle="password">
                </div>
                <div class="form-group">
                <label for="Comfirm Password" class="control-label"><b>Comfirm Password</b></label>
                <input type="password" id="cpass" name="cpass" placeholder="Comfirm Password"
                                                class="form-control" Required data-toggle="password">
                </div>
                <center><button name="submit" type="submit" class="btn-sm btn-block btn-wave col-md-4 btn-primary">submit</button></center>
            </form>
            </div>
        </div>
        </div>
                        </center>
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