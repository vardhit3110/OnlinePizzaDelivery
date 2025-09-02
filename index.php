<?php session_start(); ?>
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

    <title>Home</title>
    <link rel="icon" href="img/logo.jpg" type="image/x-icon">
</head>

<body>

    <?php include 'partials/_dbconnect.php'; ?>
    <?php require 'partials/_nav.php'; ?>

    <!-- Message Display Section (Moved Below Navigation Bar) -->
    <!-- Message Display Section (Moved Below Navigation Bar) -->
    <div class="container mt-3"> <?php if (!empty($_SESSION['signup_error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert"> <strong>Error!</strong>
                <?= htmlspecialchars($_SESSION['signup_error']); ?> <button type="button" class="close" data-dismiss="alert"
                    aria-label="Close"> <span aria-hidden="true">&times;</span> </button> </div>
            <?php unset($_SESSION['signup_error']); ?> <?php endif; ?>
        <?php if (!empty($_SESSION['signup_success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert"zz>
                <strong>Success!</strong> <?= htmlspecialchars($_SESSION['signup_success']); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php unset($_SESSION['signup_success']); ?>
        <?php endif; ?>
    </div>
    <div class="container mt-3">
        <?php if (!empty($_SESSION['message'])): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <strong>Message:</strong> <?= htmlspecialchars($_SESSION['message']); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
    </div>

    <div class="container mt-4">
        <div class="text-center mb-4">
            <h2 class="fw-bold">Our Delicious Menu</h2>
        </div>

        <!-- Veg Items with Slider -->
        <div class="menu-container">
            <h3 class="text-success text-center"></h3>
            <div class="slider-wrapper">
                <div class="slider">
                    <?php
                    $pizzaImages = [
                        ["img/pizza-69.jpg", "Margherita", "A classic pizza with a simple topping of tomato sauce and cheese, offering a perfect balance of flavors.", 69],
                        ["img/pizza-77.jpg", "Indi Tandoori Paneer", "Spicy tandoori paneer with bold Indian flavors on a cheesy, smoky crust.", 77],
                        ["img/pizza-92.jpg", "Stuffed Garlic Bread", "Soft, buttery bread loaded with melted cheese and flavorful garlic seasoning.", 92],
                        ["img/pizza-79.jpg", "Chicken Sausage", "Juicy chicken sausages topped on a cheesy base, giving a smoky and savory delight in every bite.", 79],
                        ["img/pizza-80.jpg", "Chicken Golden Delig", "Succulent chicken chunks with a golden, crispy touch, layered on a cheesy and flavorful crust.", 80],
                        ["img/pizza-82.jpg", "Indi Chicken Tikka", "Spicy and smoky tandoori chicken with a rich blend of Indian flavors on a cheesy crust.", 82],
                        ["img/pizza-95.jpg", "Coca Cola Can", "A classic fizzy drink with a bold and refreshing taste, perfect for any meal.", 95],
                        ["img/pizza-97.jpg", "Combo Drinks", "A perfect mix of refreshing beverages to complement your meal with every sip.", 97],
                        ["img/pizza-98.jpg", "Coffee", "A rich and aromatic brew to energize your day with every sip.", 98],
                        ["img/pizza-69.jpg", "Margherita", "A classic pizza with a simple topping of tomato sauce and cheese, offering a perfect balance of flavors.", 69],
                        ["img/pizza-77.jpg", "Indi Tandoori Paneer", "Spicy tandoori paneer with bold Indian flavors on a cheesy, smoky crust.", 77],
                        ["img/pizza-92.jpg", "Stuffed Garlic Bread", "Soft, buttery bread loaded with melted cheese and flavorful garlic seasoning.", 92],
                        ["img/pizza-79.jpg", "Chicken Sausage", "Juicy chicken sausages topped on a cheesy base, giving a smoky and savory delight in every bite.", 79],
                        ["img/pizza-80.jpg", "Chicken Golden Delig", "Succulent chicken chunks with a golden, crispy touch, layered on a cheesy and flavorful crust.", 80],
                        ["img/pizza-82.jpg", "Indi Chicken Tikka", "Spicy and smoky tandoori chicken with a rich blend of Indian flavors on a cheesy crust.", 82],
                        ["img/pizza-95.jpg", "Coca Cola Can", "A classic fizzy drink with a bold and refreshing taste, perfect for any meal.", 95],
                        ["img/pizza-97.jpg", "Combo Drinks", "A perfect mix of refreshing beverages to complement your meal with every sip.", 97],
                        ["img/pizza-98.jpg", "Coffee", "A rich and aromatic brew to energize your day with every sip.", 98]
                    ];
                    $allImages = array_merge($pizzaImages, $pizzaImages);
                    foreach ($allImages as $pizza) {
                        $itemId = $pizza[3]; // Get the item ID from the array
                        echo '<div class="pizza-slide">
                        <a href="http://localhost/OnlinePizzaDelivery/viewPizza.php?itemid=' . $itemId . '" style="text-decoration: none; color: inherit;">
                            <img src="' . $pizza[0] . '" class="pizza-img" alt="' . $pizza[1] . '">
                            <p class="pizza-name">' . $pizza[1] . '</p>
                            <p class="pizza-desc">' . $pizza[2] . '</p>
                        </a>
                      </div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Category container starts here -->
    <div class="container my-3 mb-5">
        <div class="col-lg-2 text-center bg-light my-3"
            style="margin:auto;border-top: 2px groove black;border-bottom: 2px groove black;">
            <h2 class="text-center">Menu </h2>
        </div>
        <div class="row">
            <!-- Fetch all the categories and use a loop to iterate through categories -->
            <?php
            $sql = "SELECT * FROM categories WHERE status = 'active'";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $id = $row['categorieId'];
                $cat = $row['categorieName'];
                $desc = $row['categorieDesc'];
                echo '<div class="col-xs-3 col-sm-3 col-md-4">
                    <div class="card" style="width: 22rem;">
                        <img src="img/card-' . $id . '.jpg" class="card-img-top" alt="image for this category" width="249px" height="270px">
                        <div class="card-body">
                            <h5 class="card-title"><a href="viewPizzaList.php?catid=' . $id . '">' . $cat . '</a></h5>
                            <p class="card-text">' . substr($desc, 0, 30) . '</p>
                            <a href="viewPizzaList.php?catid=' . $id . '" class="btn btn-primary">View All</a>
                        </div>
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
    <?php require 'partials/_footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/bootstrap-show-password@1.2.1/dist/bootstrap-show-password.min.js"></script>

</body>

</html>
<style>
    /* General Styling */
    body {
        background-color: #f8f9fa;
        font-family: 'Arial', sans-serif;
    }

    .container {
        max-width: 1200px;
    }

    /* Menu Heading */
    .col-lg-2.text-center {
        padding: 10px;
        font-weight: bold;
        font-size: 22px;
        border-radius: 10px;
    }

    /* Cards Styling */
    .card {
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        margin-bottom: 20px;
    }

    .card:hover {
        transform: scale(1.05);
        box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.2);
    }

    /* Card Image */
    .card-img-top {
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        height: 230px;
        object-fit: cover;
    }

    /* Card Title */
    .card-title a {
        font-weight: bold;
        text-decoration: none;
        transition: color 0.3s ease-in-out;
    }

    .card-title a:hover {
        text-decoration: underline;
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

    /* Responsive Layout */
    @media (max-width: 768px) {

        .col-xs-3,
        .col-sm-3,
        .col-md-4 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }

    /* Slider CSS */
    .menu-container {
        margin-bottom: 30px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 10px;
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
    }

    .slider-wrapper {
        width: 100%;
        overflow: hidden;
        display: flex;
        justify-content: center;
        padding: 10px 0;
    }

    .slider {
        display: flex;
        gap: 60px;
        animation: slide 20s linear infinite;
    }

    .pizza-slide {
        text-align: center;
        flex: 0 0 auto;
        width: 130px;
    }

    .pizza-img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #ddd;
    }

    .pizza-name {
        font-size: 16px;
        margin-top: 5px;
        font-weight: bold;
    }

    @keyframes slide {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(calc(-140px * 6));
        }
    }
</style>