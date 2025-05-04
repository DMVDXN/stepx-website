<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Cart - StepX</title>
    <link rel="stylesheet" href="stepx_success.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<header class="top-bar">
    <a href="stepx_index_home.php" class="logo">StepX</a>
    <div class="search-container">
        <i class="fa fa-search search-icon"></i>
        <input type="text" placeholder="Search for brand, color, etc">
    </div>

    <div class="icons">
    <a href="stepx_favorites.php" class="favorites">
        <i class="fa fa-heart"></i>
        <span>Favorites</span>
    </a>

    <a href="stepx_cart.php">
  <i class="fa fa-shopping-cart" style="color: #0056e0;"></i>
</a>

    <a href="stepx_notifications.php">
    <i class="fa fa-bell"></i>
    </a>

    <div class="profile-hover">
    <a href="stepx_profile.php">
    <i class="fa fa-user"></i>
  </a>

  <?php if (isset($_SESSION['first_name'])): ?>
    <div class="welcome-tooltip">
      Welcome, <?= htmlspecialchars($_SESSION['first_name']) ?>!
    </div>
  <?php endif; ?>
  
</div>    
</div>
</header>

<div class="navigation-wrapper">
    <div class="navigation">
        <a href="#">Brands</a>
        <a href="#">New</a>
        <a href="#">Men</a>
        <a href="#">Women</a>
        <a href="#">Kids</a>
        <a href="#">Sneakers</a>
        <a href="#">Shoes</a>
    </div>
</div>

<div class="success-container">
  <i class="fa fa-check-circle check-icon"></i>
  <h1>Thank you for your purchase, <?= htmlspecialchars($_SESSION['first_name'] ?? 'Customer') ?>!</h1>
  <p>Your order has been placed successfully.</p>
  <a href="stepx_index_home.php" class="continue-btn">Continue Shopping</a>
</div>

<div class="theme-buttons">
    <button onclick="setDarkMode()">
        <i class="fa fa-moon"></i>
    </button>
    <button onclick="setLightMode()">
        <i class="fa fa-sun"></i>
    </button>
</div>

<script>
    // Apply dark mode on page load if it was previously selected
    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark-mode');
    }

    function setDarkMode() {
        document.body.classList.add('dark-mode');
        localStorage.setItem('theme', 'dark');
    }

    function setLightMode() {
        document.body.classList.remove('dark-mode');
        localStorage.setItem('theme', 'light');
    }
</script>

</body>
</html>