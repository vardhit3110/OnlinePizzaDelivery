<?php
session_start();
include 'partials/_dbconnect.php'; 
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

$sql = "SELECT p.pizzaId, p.pizzaName, p.pizzaPrice, p.pizzaDesc FROM likes l
        JOIN pizza p ON l.pizzaId = p.pizzaId
        WHERE l.userId = '$userId'";
$result = mysqli_query($conn, $sql);

echo "<h2 class='text-center my-3'>Your Liked Pizzas</h2><div class='row'>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<div class='col-md-4'>
            <div class='card' style='width: 18rem;'>
                <img src='img/pizza-" . $row['pizzaId'] . ".jpg' class='card-img-top' height='200px'>
                <div class='card-body'>
                    <h5 class='card-title'>" . $row['pizzaName'] . "</h5>
                    <p class='card-text'>" . substr($row['pizzaDesc'], 0, 50) . "...</p>
                    <p class='text-danger'>Rs. " . $row['pizzaPrice'] . "/-</p>
                </div>
            </div>
          </div>";
}
echo "</div>";
?>
 <!-- Latest Bootstrap CSS -->
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<?include '_footer.php';?>