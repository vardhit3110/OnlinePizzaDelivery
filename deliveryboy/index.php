<?php 
    session_start();
    
    // Restore session if lost
    if(!isset($_SESSION['loggedin'])) {
        if(isset($_COOKIE['deliveryboy_id'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['role'] = 'delivery_boy';
            $_SESSION['deliveryboy_id'] = $_COOKIE['deliveryboy_id'];
        }
    }

    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'delivery_boy') {
        $deliveryboy_loggedin = true;
        $deliveryboyId = $_SESSION['deliveryboy_id'];
    } else {
        header("location: /OnlinePizzaDelivery/index.php");
        exit();
    }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" crossorigin="anonymous">
    <title>Delivery Boy Dashboard</title>
    <link rel="icon" href="/OnlinePizzaDelivery/img/logo.jpg" type="image/x-icon">
    
    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="assetsForSideBar/css/styles.css">
</head>
<body id="body-pd" style="background: #80808045;">
     
    <?php
        require 'partials/_dbconnect.php';
        require 'partials/_nav.php';

        if(isset($_GET['loginsuccess']) && $_GET['loginsuccess'] == "true") {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert" style="width:100%" id="loginAlert">
                    <strong>Success!</strong> You are logged in
                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                  </div>';
        }
    ?>

    <?php $page = isset($_GET['page']) ? $_GET['page'] : 'home'; ?>
    <?php include $page . '.php'; ?>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" crossorigin="anonymous"></script>         
    <script src="https://unpkg.com/bootstrap-show-password@1.2.1/dist/bootstrap-show-password.min.js"></script>
    <script src="assetsForSideBar/js/main.js"></script>
     <script>
        // Auto slide up the alert after 3 seconds
        $(document).ready(function () {
            setTimeout(function () {
                $('#loginAlert').slideUp(400);
            }, 3000);
        });
    </script>
</body>
</html>