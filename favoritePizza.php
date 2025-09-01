<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'partials/_dbconnect.php';
session_start();

$userId = $_SESSION['userId'];

// Fetch liked items
$sql = "SELECT item.itemId, item.itemName, item.itemPrice, item.itemDesc
        FROM likes 
        JOIN item ON likes.itemId = item.itemId 
        WHERE likes.userId = ?";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("SQL error: " . $conn->error);
}

$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$items = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <title>Liked Pizzas</title>
    <link rel="icon" href="img/logo.jpg" type="image/x-icon">
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
}

.container {
    margin-top: 50px;
}

h2 {
    text-align: center;
    color: #333;
    margin-bottom: 30px;
}

.card {
    margin: 15px auto;
    border-radius: 10px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease-in-out;
}

.card:hover {
    transform: scale(1.05);
}

.card img {
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
}

.card-body {
    text-align: center;
    padding: 15px;
}

.card-title {
    font-size: 18px;
    font-weight: bold;
    color: #555;
}

.card-text {
    font-size: 14px;
    color: #666;
    margin-bottom: 10px;
}

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

.like-icon {
    display: inline-block;
    margin-bottom: 10px;
}

.like-icon i {
    font-size: 22px;
    color: red;
}

.row {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}
    </style>
</head>
<body>
<?php include 'partials/_nav.php';?>
<div class="container">
    <h2>Your Favorite Items</h2>
    <div class="row">
        <?php foreach ($items as $item): ?>
            <div class="col-xs-3 col-sm-3 col-md-4">
                <div class="card" style="width: 18rem;">
                    <img src="img/pizza-<?php echo $item['itemId']; ?>.jpg" class="card-img-top" alt="Item Image" width="249px" height="270px">
                    <div class="card-body">
                        <?php
                        // Check if this item is liked
                        $likedSql = "SELECT * FROM likes WHERE userId = ? AND itemId = ?";
                        $stmt = $conn->prepare($likedSql);
                        $stmt->bind_param("ii", $userId, $item['itemId']);
                        $stmt->execute();
                        $likedResult = $stmt->get_result();
                        $isLiked = $likedResult->num_rows > 0;
                        $stmt->close();
                        ?>
                        <a href="#" class="like-icon" data-id="<?php echo $item['itemId']; ?>">
                            <i class="fas fa-heart" style="cursor:pointer; font-size:20px; color:red;"></i>
                        </a>
                        <h5 class="card-title"><?php echo substr($item['itemName'], 0, 20); ?>...</h5>
                        <h6 style="color: #ff0000">Rs. <?php echo $item['itemPrice']; ?>/-</h6>
                        <p class="card-text"><?php echo substr($item['itemDesc'], 0, 29); ?>...</p>
                        
                        <div class="row justify-content-center">
                            <?php if ($_SESSION['loggedin']): ?>
                                <?php
                                $itemId = $item['itemId'];
                                $cartSql = "SELECT itemQuantity FROM viewcart WHERE itemId = '$itemId' AND userId='$userId'";
                                $cartResult = mysqli_query($conn, $cartSql);
                                $cartExists = mysqli_num_rows($cartResult);
                                ?>
                                <form action="partials/_manageCart.php" method="POST">
                                    <input type="hidden" name="itemId" value="<?php echo $itemId; ?>">
                                    <label for=""> Size : </label>
                                    <input type="radio" value="S" name="size" checked><strong> S</strong> 
                                    <input type="radio" value="M" name="size"><strong> M</strong> 
                                    <input type="radio" value="L" name="size"><strong> L</strong> <br>
                                    <?php if ($cartExists == 0): ?>
                                        <button type="submit" name="addToCart" class="btn btn-primary mx-2">Add to Cart</button>
                                    <?php else: ?>
                                        <a href="viewCart.php"><button class="btn btn-primary mx-2">Go to Cart</button></a>
                                    <?php endif; ?>
                                    </form>
                                    <?php else: ?>
                                        <button class="btn btn-primary mx-2" style="margin-top: 34px;" data-toggle="modal" data-target="#loginModal">Add to Cart</button>
                                    <?php endif; ?>
                                    <a href="viewPizza.php?itemid=<?php echo $item['itemId']; ?>" class="mx-2" style="margin-top: 34px;">
                                        <button class="btn btn-primary">Quick View</button>
                                    </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
    </div>
</div>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>         
</body>
</html>
