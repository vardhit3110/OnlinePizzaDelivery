<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Search Results</title>
    <link rel="icon" href="img/logo.jpg" type="image/x-icon">

    <!-- Bootstrap CSS & Font Awesome -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">

    <style>
        .container {
            border-radius: 15px;
            padding: 20px;
        }

        /* Card radius and hover zoom */
        .card {
            border-radius: 12px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        #cont {
            min-height: 515px;
        }
    </style>
</head>

<body>
    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    ?>

    <?php include 'partials/_dbconnect.php'; ?>
    <?php require 'partials/_nav.php'; ?>

    <div class="container my-3">
        <h2 class="py-2">Search Results for <em>"<?php echo htmlspecialchars($_GET['search']); ?>"</em> :</h2>

        <?php
        $noResult = true;
        $query = mysqli_real_escape_string($conn, $_GET["search"]);

        // Categories
        $sql = "SELECT * FROM `categories` WHERE (categorieName LIKE '%$query%' OR categorieDesc LIKE '%$query%') AND status = 'active'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $noResult = false;
            echo '<h3 class="py-2">Categories:</h3><div class="row">';

            while ($row = mysqli_fetch_assoc($result)) {
                $catId = $row['categorieId'];
                $catname = $row['categorieName'];
                $catdesc = $row['categorieDesc'];

                echo '<div class="col-sm-6 col-md-4 col-lg-3 d-flex align-items-stretch mb-4">
                <div class="card w-100 shadow-sm">
                    <img src="img/card-' . $catId . '.jpg" class="card-img-top" alt="' . $catname . '" style="height:200px; object-fit:cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <a href="viewPizzaList.php?catid=' . $catId . '">' . htmlspecialchars($catname) . '</a>
                        </h5>
                        <p class="card-text">' . htmlspecialchars(substr($catdesc, 0, 50)) . '...</p>
                        <a href="viewPizzaList.php?catid=' . $catId . '" class="btn btn-primary mt-auto">View All</a>
                    </div>
                </div>
            </div>';
            }

            echo '</div>';
        }

        // Items
        $sql = "SELECT * FROM `item` WHERE (itemName LIKE '%$query%' OR itemDesc LIKE '%$query%') AND status = 'available'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $noResult = false;
            echo '<h3 class="py-2">Items:</h3><div class="row">';

            while ($row = mysqli_fetch_assoc($result)) {
                $itemId = $row['itemId'];
                $itemName = $row['itemName'];
                $itemPrice = $row['itemPrice'];
                $itemDesc = $row['itemDesc'];
                $itemImage = $row['itemImage'] ? $row['itemImage'] : 'img/default-item.jpg';

                echo '<div class="col-sm-6 col-md-4 col-lg-3 d-flex align-items-stretch mb-4">
                <div class="card w-100 shadow-sm">
                    <img src="' . $itemImage . '" class="card-img-top" alt="' . htmlspecialchars($itemName) . '" style="height:200px; object-fit:cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">' . htmlspecialchars($itemName) . '</h5>
                        <h6 style="color: #ff0000;">Rs. ' . htmlspecialchars($itemPrice) . '/-</h6>
                        <p class="card-text">' . htmlspecialchars(substr($itemDesc, 0, 50)) . '...</p>
                        <div class="mt-auto">
                            <div class="d-flex flex-wrap justify-content-center">';
                if (isset($loggedin) && $loggedin) {
                    $quaSql = "SELECT `itemQuantity` FROM `viewcart` WHERE itemId = '$itemId' AND `userId`='$userId'";
                    $quaresult = mysqli_query($conn, $quaSql);
                    $quaExistRows = mysqli_num_rows($quaresult);
                    if ($quaExistRows == 0) {
                        echo '<form action="partials/_manageCart.php" method="POST" class="mr-2 mb-2">
                            <input type="hidden" name="itemId" value="' . $itemId . '">
                            <button type="submit" name="addToCart" class="btn btn-sm btn-primary">Add to Cart</button>
                          </form>';
                    } else {
                        echo '<a href="viewCart.php" class="btn btn-sm btn-primary mb-2 mr-2">Add to Cart</a>';
                    }
                } else {
                    echo '<button class="btn btn-sm btn-primary mb-2 mr-2"  data-toggle="modal" data-target="#loginModal">Add to Cart</button>';
                }

                echo '<a href="viewPizza.php?itemid=' . $itemId . '" class="btn btn-sm btn-primary mb-2">View</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
            }

            echo '</div>';
        }

        // No Results
        if ($noResult) {
            echo '<div class="jumbotron jumbotron-fluid">
            <div class="container">
                <h1>No results found for <em>"' . htmlspecialchars($_GET['search']) . '"</em></h1>
                <p class="lead">Suggestions:
                    <ul>
                        <li>Check your spelling</li>
                        <li>Try different keywords</li>
                        <li>Try more general terms</li>
                    </ul>
                </p>
            </div>
        </div>';
        }
        ?>
    </div>

    <?php require 'partials/_footer.php'; ?>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>

</html>