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
    <img src="assetsForSideBar/img/sample_logo.png" alt="">
  </div>
</header>
<div class="l-navbar" id="nav-bar">
  <nav class="nav">
    <div>
      <a href="index.php" class="nav__logo">
        <i class='bx bx-layer nav__logo-icon'></i>
        <span class="nav__logo-name">Pizza Delivery</span>
      </a>
      <div class="nav__list">
        <a href="index.php" class="nav__link nav-home">
          <i class='bx bx-grid-alt nav__icon'></i>
          <span class="nav__name">Home</span>
        </a>
        <a href="index.php?page=orderManage" class="nav__link nav-orderManage">
          <i class='bx bx-bar-chart-alt-2 nav__icon'></i>
          <span class="nav__name">Orders</span>
        </a>
        <a href="index.php?page=categoryManage" class="nav__link nav-categoryManage">
          <i class='bx bx-folder nav__icon'></i>
          <span class="nav__name">Category List</span>
        </a>
        <a href="index.php?page=menuManage" class="nav__link nav-menuManage">
          <i class='bx bx-message-square-detail nav__icon'></i>
          <span class="nav__name">Menu</span>
        </a>
        <a href="index.php?page=contactManage" class="nav__link nav-contactManage">
          <i class="fas fa-hands-helping"></i>
          <span class="nav__name">Contact Info</span>
        </a>
        <a href="index.php?page=feedbackManage" class="nav__link nav-feedbackManage">
          <i class="fas fa-comments"></i>
          <span class="nav__name">Feedbacks</span>
        </a>
        <a href="index.php?page=userManage" class="nav__link nav-userManage">
          <i class='bx bx-user nav__icon'></i>
          <span class="nav__name">Users</span>
        </a>
        <a href="index.php?page=deliveryRequests" class="nav__link nav-deliveryRequests">
          <i class="fas fa-truck"></i>
          <span class="nav__name">Delivery Requests</span>
        </a>
        <a href="index.php?page=deliveryBoyReviews" class="nav__link nav-deliveryBoyReviews">
          <i class="fas fa-star"></i>
          <span class="nav__name">Delivery Boy Reviews</span>
        </a>
        <a href="index.php?page=payouts" class="nav__link nav-payouts">
          <i class="fas fa-money-check-alt"></i>
          <span class="nav__name">Payouts</span>
        </a>
        <a href="index.php?page=siteManage" class="nav__link nav-siteManage">
          <i class="fas fa-cogs"></i>
          <span class="nav__name">Site Settings</span>
        </a>
        <a href="partials/_logout.php" class="nav__link">
          <i class='bx bx-log-out nav__icon'></i>
          <span class="nav__name">Log Out</span>
        </a>
      </div>
    </div>
  </nav>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  <?php $page = isset($_GET['page']) ? $_GET['page'] : 'home'; ?>
  $('.nav-<?php echo $page; ?>').addClass('active');
</script>