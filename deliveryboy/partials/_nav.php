<?php
@session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: /OnlinePizzaDelivery/index.php");
    exit();
}
?>
<link rel="stylesheet" href="<?php echo $_SERVER['DOCUMENT_ROOT']; ?>/OnlinePizzaDelivery/assets/css/style.css">
<style>
    .nav {
        position: -webkit-sticky;
        position: sticky;
        top: 1rem;
        display: block !important;
        height: calc(100vh - 6rem);
        padding-left: 0.25rem;
        margin-left: -0.25rem;
        overflow-y: scroll;
        /* still allows scrolling */

        /* Hide scrollbar but keep scroll functionality */
        scrollbar-width: none;
        /* Firefox */
        -ms-overflow-style: none;
        /* Internet Explorer 10+ */
    }

    .nav::-webkit-scrollbar {
        display: none;
        /* Chrome, Safari, Edge */
    }
</style>

<header class="header" id="header">
    <div class="header__toggle">
        <i class='bx bx-menu' id="header-toggle"></i>
    </div>

    <div class="header__img">
        <img src="/OnlinePizzaDelivery/deliveryboy/assetsForSideBar/img/delivery2.png" alt="Delivery Logo"
            class="img-fluid" style="max-width: 200px;">
    </div>
</header>

<div class="l-navbar" id="nav-bar">
    <nav class="nav">
        <div>
            <a href="index.php" class="nav__logo">
                <i class='bx bx-layer nav__logo-icon'></i>
                <span class="nav__logo-name">Delivery Panel</span>
            </a>

            <div class="nav__list">
                <a href="index.php?page=home" class="nav__link nav-home active">
                    <i class='bx bx-home nav__icon'></i>
                    <span class="nav__name">Home</span>
                </a>
                <a href="index.php?page=orderDetails" class="nav__link nav-orders">
                    <i class='bx bx-cart nav__icon'></i>
                    <span class="nav__name">Orders</span>
                </a>
                <a href="index.php?page=deliveryDetails" class="nav__link nav-deliveryDetails">
                    <i class='bx bx-run nav__icon'></i>
                    <span class="nav__name">Delivery Details</span>
                </a>
                <a href="index.php?page=reviewManage" class="nav__link nav-reviewManage">
                    <i class="fas fa-star"></i>
                    <span class="nav__name">Reviews</span>
                </a>
                <a href="index.php?page=complaints" class="nav__link nav-complaints">
                    <i class="fas fa-exclamation-circle"></i>
                    <span class="nav__name">Complaints</span>
                </a>
                <a href="index.php?page=payouts" class="nav__link nav-payouts">
                    <i class="fas fa-wallet"></i>
                    <span class="nav__name">Payouts</span>
                </a>
                <a href="index.php?page=delivery_boy_profile" class="nav__link nav-payouts">
                    <i class="fas fa-user"></i> <!-- Change fa-solid to fas -->
                    <span class="nav__name">Profile Edit</span>
                </a>
            </div>
        </div>
        <a href="logout.php" class="nav__link">
            <i class='bx bx-log-out nav__icon'></i>
            <span class="nav__name">Log Out</span>
        </a>
    </nav>
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    <?php $page = isset($_GET['page']) ? $_GET['page'] : 'home'; ?>
    $('.nav-<?php echo $page; ?>').addClass('active');
</script>