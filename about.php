<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
    integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"
    integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">

  <title>About Us</title>
  <link rel="icon" href="img/logo.jpg" type="image/x-icon">
  <!-- Google Fonts -->
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/icofont/icofont.min.css" rel="stylesheet">
  <link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <!-- Latest Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

</head>

<body>
  <?php include 'partials/_dbconnect.php'; ?>
  <?php include 'partials/_nav.php'; ?>

  <main id="main">
    <?php
    include '_dbconnect.php'; // Ensure you have the database connection
    
    // Initialize rating counts
    $ratings = [
      5 => 0,
      4 => 0,
      3 => 0,
      2 => 0,
      1 => 0
    ];

    // Fetch total feedback count
    $totalQuery = "SELECT COUNT(*) AS total FROM feedback";
    $totalResult = mysqli_query($conn, $totalQuery);
    $totalRow = mysqli_fetch_assoc($totalResult);
    $totalFeedback = $totalRow['total'];

    // Fetch ratings count for each star level
    $ratingQuery = "SELECT rating, COUNT(*) AS count FROM feedback GROUP BY rating";
    $ratingResult = mysqli_query($conn, $ratingQuery);

    while ($row = mysqli_fetch_assoc($ratingResult)) {
      $ratings[$row['rating']] = $row['count'];
    }

    // Calculate percentages
    function getPercentage($count, $total)
    {
      return $total > 0 ? round(($count / $total) * 100) : 0;
    }
    ?>

    <style>
      .progress-bar {
        width: 0%;
        /* Start with zero width */
        height: 10px;
        background-color: #f39c12;
        /* Orange color */
        transition: width 1.5s ease-in-out;
        /* Smooth transition effect */
      }
    </style>

    <section id="about" class="about">
      <div class="container">
        <div class="section-title">
          <h2>About Us</h2>
        </div>

        <div class="row">
          <div class="col-lg-6">
            <h3>Welcome to <strong>Pizza World</strong></h3>
            <h3><strong>The Worldwide Leader in Pizza Delivery</strong></h3>
          </div>
          <div class="col-lg-6 pt-4 pt-lg-0 content">
            <div class="skills-content">
              <p><b>Rating: </b></p>

              <?php foreach ($ratings as $star => $count): ?>
                <div class="progress">
                  <span class="skill"><?php echo $star; ?> star <i
                      class="val"><?php echo getPercentage($count, $totalFeedback); ?>%</i></span>
                  <div class="progress-bar-wrap">
                    <div class="progress-bar" role="progressbar"
                      aria-valuenow="<?php echo getPercentage($count, $totalFeedback); ?>" aria-valuemin="0"
                      aria-valuemax="100"></div>
                  </div>
                </div>
              <?php endforeach; ?>

            </div>
          </div>
        </div>
      </div>
    </section>

    <script>
      document.addEventListener("DOMContentLoaded", function () {
        let progressBars = document.querySelectorAll(".progress-bar");

        progressBars.forEach(function (bar) {
          let percentage = bar.getAttribute("aria-valuenow"); // Get the percentage value
          bar.style.width = percentage + "%"; // Animate width
        });
      });
    </script>


    <!-- ======= Counts Section ======= -->
    <?php
    // Fetch total users
    $userQuery = "SELECT COUNT(*) AS totalUsers FROM users";
    $userResult = mysqli_query($conn, $userQuery);
    $userRow = mysqli_fetch_assoc($userResult);
    $totalUsers = $userRow['totalUsers'];

    // Fetch total pizza items
    $itemQuery = "SELECT COUNT(*) AS totalItems FROM item"; // Changed from pizza to item
    $itemResult = mysqli_query($conn, $itemQuery);
    $itemRow = mysqli_fetch_assoc($itemResult);
    $totalItems = $itemRow['totalItems']; // Changed from totalPizzas to totalItems
    
    // Fetch total categories
    $categoryQuery = "SELECT COUNT(*) AS totalCategories FROM categories";
    $categoryResult = mysqli_query($conn, $categoryQuery);
    $categoryRow = mysqli_fetch_assoc($categoryResult);
    $totalCategories = $categoryRow['totalCategories'];
    // Fetch total delivery boys
    $deliveryBoyQuery = "SELECT COUNT(*) AS totalDeliveryBoys FROM delivery_boys";
    $deliveryBoyResult = mysqli_query($conn, $deliveryBoyQuery);
    $deliveryBoyRow = mysqli_fetch_assoc($deliveryBoyResult);
    $totalDeliveryBoys = $deliveryBoyRow['totalDeliveryBoys'];
    ?>

    <section class="counts section-bg">
      <div class="container">
        <div class="row no-gutters">

          <div class="col-lg-3 col-md-6 d-md-flex align-items-md-stretch">
            <div class="count-box">
              <i class="icofont-simple-smile"></i>
              <span data-toggle="counter-up"><?php echo $totalUsers; ?></span>
              <p><strong>Happy Customers</strong></p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 d-md-flex align-items-md-stretch">
            <div class="count-box">
              <i class="icofont-fork-and-knife"></i>
              <span data-toggle="counter-up"><?php echo $totalItems; ?></span>
              <!-- Changed from totalPizzas to totalItems -->
              <p><strong>Items</strong></p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 d-md-flex align-items-md-stretch">
            <div class="count-box">
              <i class="icofont-runner-alt-1"></i>
              <span data-toggle="counter-up"><?php echo $totalDeliveryBoys; ?></span>
              <p><strong>Total Delivery Boys</strong></p>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 d-md-flex align-items-md-stretch">
            <div class="count-box">
              <i class="icofont-chef"></i>
              <span data-toggle="counter-up"><?php echo $totalCategories; ?></span>
              <p><strong>Categories</strong></p>
            </div>
          </div>

        </div>
      </div>
    </section>
    <!-- End Counts Section -->

    <!-- ======= Our Team Section ======= -->
    <section id="team" class="team">
      <div class="container">

        <div class="section-title">
          <h2>Owners</h2>
        </div>

        <div class="row" style="padding-left: 228px;">

          <div class="col-xl-3 col-lg-4 col-md-6" data-wow-delay="0.2s">
            <div class="member">
              <img src="assets/img/team/divyraj.jpg" class="img-fluid" alt="" style="height:250px; width:190px">
              <div class="member-info">
                <div class="member-info-content">
                  <h4>Gohil Divyraj</h4>
                </div>
                <div class="social">
                  <a href="https://twitter.com/home" target="_blank"><i class="icofont-twitter"></i></a>
                  <a href="https://github.com/henibalar" target="_blank"><i class="fab fa-github"></i></a>
                  <a href="https://www.linkedin.com/feed/" target="_blank"><i class="icofont-linkedin"
                      target="_blank"></i></a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-lg-4 col-md-6" data-wow-delay="0.2s">
            <div class="member">
              <img src="assets/img/team/pooja.jpeg" class="img-fluid" alt="" style="height:250px; width:190px">
              <div class="member-info">
                <div class="member-info-content">
                  <h4>Zanzmera Pooja</h4>
                </div>
                <div class="social">
                  <a href="https://twitter.com/home"><i class="icofont-twitter" target="_blank"></i></a>
                  <a href="https://github.com/henibalar" target="_blank"><i class="fab fa-github"></i></a>
                  <a href="https://www.linkedin.com/feed/"><i class="icofont-linkedin" target="_blank"></i></a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-lg-4 col-md-6" data-wow-delay="0.2s">
            <div class="member">
              <img src="assets/img/team/vardhit.jpg" class="img-fluid" alt="" style="height:250px; width:190px">
              <div class="member-info">
                <div class="member-info-content">
                  <h4>Vamja Vardhit</h4>
                </div>
                <div class="social">
                  <a href="https://twitter.com/home"><i class="icofont-twitter" target="_blank"></i></a>
                  <a href="https://github.com/henibalar" target="_blank"><i class="fab fa-github"></i></a>
                  <a href="https://www.linkedin.com/feed/"><i class="icofont-linkedin" target="_blank"></i></a>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section><!-- End Our Team Section -->
  </main>
  <?php include 'partials/_signupModal.php'; ?>
  <?php include 'partials/_loginModal.php'; ?>
  <?php include 'partials/_forgot_pass.php'; ?>
  <?php include 'partials/_deliveryboySignupModal.php'; ?>
  <?php include 'partials/_footer.php'; ?>

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

  <!-- Vendor JS Files -->
  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/jquery-sticky/jquery.sticky.js"></script>
  <script src="assets/vendor/waypoints/jquery.waypoints.min.js"></script>
  <script src="assets/vendor/counterup/counterup.min.js"></script>
  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
</body>

</html>