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
        <!-- Latest Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <title id=title>Item</title>
    <link rel="icon" href="img/logo.jpg" type="image/x-icon">
    <style>
        #cont {
            min-height: 578px;
        }
    </style>
</head>

<body>
    <?php include 'partials/_dbconnect.php'; ?>
    <?php require 'partials/_nav.php' ?>

    <div class="container my-4" id="cont">
        <div class="row jumbotron">
            <?php
            $itemId = $_GET['itemid'];
            $sql = "SELECT * FROM `item` WHERE itemId = $itemId";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $itemName = $row['itemName'];
            $itemPrice = $row['itemPrice'];
            $itemM = 2 * $row['itemPrice'];
            $itemL = 3 * $row['itemPrice'];
            $itemDesc = $row['itemDesc'];
            $itemCategorieId = $row['itemCategorieId'];
            $small = "S Size Price";
            ?>
            <script> document.getElementById("title").innerHTML = "<?php echo $itemName; ?>"; </script>
            <?php
            echo '<div class="col-md-4">
                <img src="img/pizza-' . $itemId . '.jpg" width="249px" height="262px">
            </div>
            <div class="col-md-8 my-4">
                <h3>' . $itemName . '</h3>
                <h5 style="color: #ff0000"><small> S size Rs.</small> ' . $itemPrice . '/-</h5>
                <h5 style="color: #ff0000"><small>M size Rs.</small> ' . $itemM . '/-</h5>
                <h5 style="color: #ff0000"><small>L size Rs.</small> ' . $itemL . '/-</h5>
                <p class="mb-0">' . $itemDesc . '</p>';

            if ($loggedin) {
                $quaSql = "SELECT `itemQuantity` FROM `viewcart` WHERE itemId = '$itemId' AND `userId`='$userId'";
                $quaresult = mysqli_query($conn, $quaSql);
                $quaExistRows = mysqli_num_rows($quaresult);
                if ($quaExistRows == 0) {
                    // echo '<form action="partials/_manageCart.php" method="POST">
                    //       <input type="hidden" name="itemId" value="'.$itemId. '">
                    //       <button type="submit" name="addToCart" class="btn btn-primary my-2">Add to Cart</button>';
                    $quaSql = "SELECT `itemQuantity` FROM `viewcart` WHERE itemId = '$itemId' AND `userId`='$userId'";
                    $quaresult = mysqli_query($conn, $quaSql);
                    $quaExistRows = mysqli_num_rows($quaresult);
                    if ($quaExistRows == 0) {
                        echo '<form action="partials/_manageCart.php" method="POST">
                                  <input type="hidden" name="itemId" value="' . $itemId . '">
                                  <label for=""> Size : </label>
                                  <input type="radio"  value="S"  name="size"  checked ><strong class="text-10 "> S</strong> 
                                  <input type="radio" value="M" name="size"><strong> M</strong> 
                                  <input type="radio" value="L" name="size"><strong>L</strong> <br>
                                  <button type="submit" name="addToCart" class="btn btn-primary mx-3">Add to Cart</button><br>';
                    }

                } else {
                    //     echo '<button type="submit" name="addToCart" class="btn btn-primary mx-2">Add to Cart</button><br>';
                    if ($quaExistRows == 1) {
                        echo '<form action="partials/_manageCart.php" method="POST">
                            <input type="hidden" name="itemId" value="' . $itemId . '">
                              <label for=""> Size : </label>
                              <input type="radio"  value="S"  name="size"  checked ><strong> S</strong> 
                              <input type="radio" value="M" name="size"><strong> M</strong> 
                              <input type="radio" value="L" name="size"><strong> L</strong> <br>
                              <div class="row">
                              <button type="submit" name="addToCart" class="btn btn-primary my-2 mt-2 ml-4">Add to Cart</button>
                              </div>';
                    }
                }
            } else {
                echo '<button class="btn btn-primary my-2" data-toggle="modal" data-target="#loginModal">Add to Cart</button>';
            }
            echo '</form>
                <h6 class="my-1"> View </h6>
                <div class="mx-4">
                    <a href="viewPizzaList.php?catid=' . $itemCategorieId . '" class="active text-dark">
                    <i class="fas fa-qrcode"></i>
                        <span>All Items</span>
                    </a>
                </div>
                <div class="mx-4">
                    <a href="index.php" class="active text-dark">
                    <i class="fas fa-qrcode"></i>
                        <span>All Category</span>
                    </a>
                </div>
            </div>'
                ?>
        </div>
    </div>
    <?php include 'partials/_signupModal.php'; ?>
    <?php include 'partials/_loginModal.php'; ?>
    <?php include 'partials/_forgot_pass.php'; ?>
    <?php include 'partials/_deliveryboySignupModal.php'; ?>
    <?php require 'partials/_footer.php' ?>

    <!-- Optional JavaScript -->
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