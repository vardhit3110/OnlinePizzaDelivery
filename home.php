<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Start your development with Pigga landing page.">
    <meta name="author" content="Devcrud">
    <link rel="stylesheet" href="pigga/assets/css/pigga.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"
        integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <!-- Latest Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- jQuery (required for Bootstrap JavaScript) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <title>Home</title>
    <link rel="icon" href="img/logo.jpg" type="image/x-icon">

</head>

<body data-spy="scroll" data-target=".navbar" data-offset="40" id="home">
    
    <?php include 'partials/_dbconnect.php'; ?>
    <?php require 'partials/_nav.php' ?>
    <!-- End Of Second Navigation -->
    <!-- Page Header -->
    <header class="header">
        <div class="overlay">
            <img src="pigga/assets/imgs/logo.svg"
                alt="Download free bootstrap 4 landing page, free boootstrap 4 templates, Download free bootstrap 4.1 landing page, free boootstrap 4.1.1 templates, Pigga Landing page"
                class="logo">
            <h1 class="subtitle">Welcome To Our Pizza World</h1>
            <h1 class="title">Really Fresh &amp; Tasty</h1>
            <a class="btn btn-primary mt-3" href="#showmenu">Show Menu</a>
        </div>
    </header>
    <section class="has-img-bg">
        <div class="container" id="showmenu">
            <h6 class="section-subtitle text-center">Great Food</h6>
            <h3 class="section-title mb-6 text-center">Main Menu</h3>
            <div class="card bg-light">
                <div class="card-body px-4 pb-4 text-center">
                    <div class="row text-left">
                        <div class="col-md-6 my-4">
                            <a href="#"
                                class="pb-3 mx-3 d-block text-dark text-decoration-none border border-left-0 border-top-0 border-right-0">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        VEG PIZZA
                                        <p class="mt-1 mb-0">A delight for veggie lovers! C...</p>
                                    </div>

                                </div>
                            </a>
                        </div>
                        <div class="col-md-6 my-4">
                            <a href="#"
                                class="pb-3 mx-3 d-block text-dark text-decoration-none border border-left-0 border-top-0 border-right-0">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        NON-VEG PIZZA
                                        <p class="mt-1 mb-0">Choose your favourite non-veg ...

                                        </p>
                                    </div>

                                </div>
                            </a>
                        </div>
                        <div class="col-md-6 my-4">
                            <a href="#"
                                class="pb-3 mx-3 d-block text-dark text-decoration-none border border-left-0 border-top-0 border-right-0">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        SIDES ORDERS
                                        <p class="mt-1 mb-0">Complement your pizza with wid...

                                        </p>
                                    </div>

                                </div>
                            </a>
                        </div>
                        <div class="col-md-6 my-4">
                            <a href="#"
                                class="pb-3 mx-3 d-block text-dark text-decoration-none border border-left-0 border-top-0 border-right-0">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        BEVERAGES
                                        <p class="mt-2 mb-0">Complement your pizza with wid...

                                        </p>
                                    </div>

                                </div>
                            </a>
                        </div>
                        <div class="col-md-6 my-4">
                            <a href="#"
                                class="pb-3 mx-3 d-block text-dark text-decoration-none border border-left-0 border-top-0 border-right-0">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        CHOICE OF CRUSTS
                                        <p class="mt-2 mb-0">Fresh Pan Pizza Tastiest Pan P...</p>
                                    </div>

                                </div>
                            </a>
                        </div>
                        <div class="col-md-6 my-4">
                            <a href="#"
                                class="pb-3 mx-3 d-block text-dark text-decoration-none border border-left-0 border-top-0 border-right-0">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        BURGER PIZZA
                                        <p class="mt-2 mb-0">Domino’s Pizza Introducing a...</p>
                                    </div>

                                </div>
                            </a>
                        </div>
                    </div>
                    <a href="index.php" class="btn btn-primary mt-4">Book a Order</a>
                </div>
            </div>
        </div>
    </section>
    <!-- End of Menu Section -->
    <?php include 'partials/_signupModal.php'; ?>
    <?php include 'partials/_loginModal.php'; ?>
    <?php include 'partials/_forgot_pass.php'; ?>
    <?php include 'partials/_deliveryboySignupModal.php'; ?>
    <?php require 'partials/_footer.php' ?>
    <!-- core  -->
    <script src="pigga/assets/vendors/jquery/jquery-3.4.1.js"></script>
    <script src="pigga/assets/vendors/bootstrap/bootstrap.bundle.js"></script>
    <!-- bootstrap affix -->
    <script src="pigga/assets/vendors/bootstrap/bootstrap.affix.js"></script>
    <!-- Pigga js -->
    <script src="pigga/assets/js/pigga.js"></script>
</body>
</html>