<style>
  .navbar-dark .dropdown-menu a {
    color: black !important;
    /* Set text color to black */
  }
</style>
<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include 'partials/_dbconnect.php'; // Ensure database connection is included

$loggedin = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true;
$userId = $loggedin ? $_SESSION['userId'] : 0;
$username = $loggedin ? $_SESSION['username'] : '';
$sql = "SELECT * FROM `sitedetail`";
$result = mysqli_query($conn, $sql);

if (!$result) {
  die("Query Failed: " . mysqli_error($conn)); // Debugging step
}

if (mysqli_num_rows($result) > 0) {
  $row = mysqli_fetch_assoc($result);
  $systemName = $row['systemName'];
} else {
  $systemName = "Online Pizza Delivery"; // Default fallback
}


echo '<nav class="navbar navbar-expand-lg navbar-dark bg-dark navbar-fixed-top on">
      <a class="navbar-brand" href="index.php">' . $systemName . '</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active"><a class="nav-link" href="home.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="index.php">Menu</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
              Categories
            </a>
            <div class="dropdown-menu">';
$sql = "SELECT categorieName, categorieId FROM `categories`";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
  echo '<a class="dropdown-item" href="viewPizzaList.php?catid=' . $row['categorieId'] . '">' . $row['categorieName'] . '</a>';
}
echo '</div>
          </li>
          <li class="nav-item"><a class="nav-link" href="viewOrder.php">Your Orders</a></li>
          <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact Us</a></li>';

// Feedback Link with Login Check
if ($loggedin) {
  echo '<li class="nav-item"><a class="nav-link" href="feedback.php">Feedback</a></li>';
} else {
  echo '<li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#loginModal">Feedback</a>
                  </li>';
}

echo '</ul>
        <div class="form-inline my-2 my-lg-0 mx-3">
          <input class="form-control mr-sm-2" type="search" id="liveSearchInput" placeholder="Search pizza..." autocomplete="off">
        </div>';

// Cart Icon
$countsql = "SELECT SUM(`itemQuantity`) FROM `viewcart` WHERE `userId`=$userId";
$countresult = mysqli_query($conn, $countsql);
$countrow = mysqli_fetch_assoc($countresult);
$count = $countrow['SUM(`itemQuantity`)'] ?? 0;

echo '<a href="viewCart.php">
        <button type="button" class="btn btn-secondary mx-2" title="MyCart">
          <i class="bi bi-cart">Cart(' . $count . ')</i>
        </button>
      </a>';

// User Profile/Login Buttons
if ($loggedin) {
  echo '<ul class="navbar-nav mr-2">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"> Welcome ' . $username . '</a>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="favoritePizza.php">Favorite Items</a>
              <a class="dropdown-item" href="partials/_logout.php">Logout</a>
            </div>
          </li>
        </ul>
        <div class="text-center image-size-small">
          <a href="viewProfile.php"><img src="img/person-' . $username . '.jpg" class="rounded-circle" onError="this.src=\'img/profilePic.jpg\'" style="width:40px; height:40px"></a>
        </div>';
} else {
  echo '<button type="button" class="btn btn-success mx-2" data-toggle="modal" data-target="#loginModal">Login</button>
        <button type="button" class="btn btn-success mx-2" data-toggle="modal" data-target="#signupModal">SignUp</button>';
}

echo '</div></nav>';

// Include login/signup modals
include 'partials/_loginModal.php';
include 'partials/_signupModal.php';

// Display alerts
if (isset($_GET['signupsuccess']) && $_GET['signupsuccess'] == "true") {
  echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
          <strong>Success!</strong> You can now login.
          <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
        </div>';
}
if (isset($_GET['loginsuccess']) && $_GET['loginsuccess'] == "true") {
  echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
          <strong>Success!</strong> You are logged in.
          <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
        </div>';
}
if (isset($_GET['loginsuccess']) && $_GET['loginsuccess'] == "false") {
  echo '<div class="alert alert-warning alert-dismissible fade show" role="alert" id="loginAlert">
          <strong>Warning!</strong> Invalid Credentials.
          <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
        </div>';
}
?>
<!-- Add this in footer.php or just before </body> -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
  // Auto slide up the alert after 3 seconds
  $(document).ready(function () {
    setTimeout(function () {
      $('#loginAlert').slideUp(400);
    }, 3000);
  });
</script>
<script>
  $(document).ready(function () {
    $('.dropdown-toggle').dropdown(); // Enable Bootstrap dropdowns

    // Live search functionality
    let searchTimer;
    $('#liveSearchInput').on('input', function () {
      clearTimeout(searchTimer);
      const query = $(this).val().trim();

      if (query.length > 1) { // Only search after 2+ characters
        searchTimer = setTimeout(function () {
          window.location.href = 'search.php?search=' + encodeURIComponent(query);
        }, 500); // 0.5 second delay after typing stops
      }
    });

    // Submit on Enter key
    $('#liveSearchInput').keypress(function (e) {
      if (e.which == 13) { // Enter key
        const query = $(this).val().trim();
        if (query.length > 0) {
          window.location.href = 'search.php?search=' + encodeURIComponent(query);
        }
        return false; // Prevent form submission
      }
    });
  });
</script>