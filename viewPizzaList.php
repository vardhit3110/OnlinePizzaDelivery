<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" crossorigin="anonymous">

    <title id="title">Category</title>
    <link rel="icon" href="img/logo.jpg" type="image/x-icon">
    <style>
        .jumbotron {
            padding: 2rem 1rem;
        }

        #cont {
            min-height: 570px;
        }

        /* General Page Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        /* Container Styling */
        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }

        /* Pizza Card Styling */
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            margin: 15px auto;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .card img {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            object-fit: cover;
        }

        /* Card Body */
        .card-body {
            text-align: center;
            background: #ffffff;
            padding: 15px;
        }

        /* Like Icon */
        .like-icon {
            font-size: 22px;
            cursor: pointer;
            transition: color 0.3s ease-in-out;
        }

        .like-icon.fas {
            color: red;
        }

        .like-icon.far {
            color: grey;
        }

        /* Pizza Name & Price */
        .card-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .card h6 {
            font-size: 16px;
            font-weight: bold;
            color: #ff0000;
        }

        /* Buttons */
        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 8px 16px;
            font-size: 14px;
            border-radius: 5px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .card {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <?php
    include 'partials/_dbconnect.php';
    require 'partials/_nav.php';

    @session_start();
    $loggedin = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true;
    $userId = $loggedin ? $_SESSION['userId'] : null;
    ?>

    <div>&nbsp;
        <a href="index.php" class="active text-dark">
            <i class="fas fa-qrcode"></i>
            <span>All Category</span>
        </a>
    </div>

    <?php
    $id = $_GET['catid'];
    $sql = "SELECT * FROM `categories` WHERE categorieId = $id";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $catname = $row['categorieName'];
        $catdesc = $row['categorieDesc'];
    }
    ?>

    <div class="container my-3" id="cont">
        <div class="col-lg-4 text-center bg-light my-3"
            style="margin:auto;border-top: 2px groove black;border-bottom: 2px groove black;">
            <h2 class="text-center"><span id="catTitle">Items</span></h2>
        </div>
        <div class="row">
            <?php
            $likedPizzas = []; // Array to store liked items
            if ($loggedin) {
                $sqlLiked = "SELECT itemId FROM likes WHERE userId = $userId";
                $resultLiked = mysqli_query($conn, $sqlLiked);
                while ($rowLiked = mysqli_fetch_assoc($resultLiked)) {
                    $likedPizzas[] = $rowLiked['itemId'];
                }
            }

            $sql = "SELECT * FROM `item` WHERE itemCategorieId = $id AND status = 'available'";
            $result = mysqli_query($conn, $sql);
            $noResult = true;

            while ($row = mysqli_fetch_assoc($result)) {
                $noResult = false;
                $itemId = $row['itemId'];
                $itemName = $row['itemName'];
                $itemPrice = $row['itemPrice'];
                $itemDesc = $row['itemDesc'];

                // Check if pizza is liked
                $likedClass = in_array($itemId, $likedPizzas) ? "fas" : "far";
                $likedColor = in_array($itemId, $likedPizzas) ? "red" : "grey";

                echo '<div class="col-xs-3 col-sm-3 col-md-4">
<div class="card" style="width: 18rem;">
    <img src="img/pizza-' . $itemId . '.jpg" class="card-img-top" alt="image for this item" width="249px" height="270px">
    <div class="card-body">
        <i class="' . $likedClass . ' fa-heart like-icon" data-id="' . $itemId . '" style="cursor:pointer; font-size:20px; color:' . $likedColor . ';"></i>
        <h5 class="card-title">' . substr($itemName, 0, 20) . '...</h5>
        <h6 style="color: #ff0000">Rs. ' . $itemPrice . '/-</h6>
        <p class="card-text">' . substr($itemDesc, 0, 29) . '...</p>  
        <div class="row justify-content-center">';
                if ($loggedin) {
                    echo '<form action="partials/_manageCart.php" method="POST">
                  <input type="hidden" name="itemId" value="' . $itemId . '">
                  <label for=""> Size : </label>
                  <input type="radio"  value="S"  name="size"  checked ><strong> S</strong> 
                  <input type="radio" value="M" name="size"><strong> M</strong> 
                  <input type="radio" value="L" name="size"><strong> L</strong> <br>
                  <button type="submit" name="addToCart" class="btn btn-primary mx-2">Add to Cart</button><br>';
                } else {
                    echo '<button class="btn btn-primary mx-2" data-toggle="modal" data-target="#loginModal" style="margin-top: 34px;">Add to Cart</button>';
                }
                echo '</form>   
        <a href="viewPizza.php?itemid=' . $itemId . '" class="mx-2 " style="margin-top: 34px;"><button class="btn btn-primary">Quick View</button></a> 
        </div>
    </div>
</div>
</div>';
            }
            if ($noResult) {
                echo '<div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <p class="display-4">Sorry, no items available in this category.</p>
                        <p class="lead"> We will update soon.</p>
                    </div>
                </div>';
            }
            ?>
        </div>
    </div>
    <?php include 'partials/_signupModal.php'; ?>
    <?php include 'partials/_loginModal.php'; ?>
    <?php include 'partials/_forgot_pass.php'; ?>
    <?php include 'partials/_deliveryboySignupModal.php'; ?>
    <?php require 'partials/_footer.php' ?>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
   <script>
    $(document).ready(function () {
    $(".like-icon").click(function () {
        var itemId = $(this).attr("data-id");
        var heartIcon = $(this);

        $.ajax({
            url: "partials/likePizza.php",  // Updated to match the `item` table
            type: "POST",
            data: { itemId: itemId },
            success: function (response) {
                if (response.trim() === "liked") {
                    heartIcon.removeClass("far").addClass("fas").css("color", "red");
                } else if (response.trim() === "unliked") {
                    heartIcon.removeClass("fas").addClass("far").css("color", "grey");
                }
            },
            error: function () {
                alert("Something went wrong!");
            }
        });
    });
});

   </script>
</body>

</html>