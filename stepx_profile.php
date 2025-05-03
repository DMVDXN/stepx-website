<!--stepx_profile.php-->
<?php
session_start();

$first_name = $_SESSION['first_name'] ?? 'John';
$last_name = $_SESSION['last_name'] ?? 'Doe';
$email = $_SESSION['email'] ?? 'john@example.com';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile - StepX</title>
    <link rel="stylesheet" href="stepx_profile.css">
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
        <i class="fa fa-shopping-cart"></i>
        <i class="fa fa-bell"></i>
        <a href="stepx_profile.php">
            <i class="fa fa-user"></i>
        </a>    
    </div>
</header>

    <div class="navigation-wrapper">
        <div class="navigation">
            <a href="stepx_index_home.php">Home</a>
            <a href="#">Brands</a>
            <a href="#">New</a>
            <a href="#">Men</a>
            <a href="#">Women</a>
            <a href="#">Kids</a>
            <a href="#">Shoes</a>
        </div>
    </div>

    <div class="edit-profile-container">
        <h1>Edit Profile</h1>
        <div class="profile-header">
  <i class="fa fa-user-circle"></i>
  <span class="profile-name"><?= htmlspecialchars($first_name . ' ' . $last_name) ?></span>
</div>

        <form action="#" method="POST">
            <input type="text" name="first_name" placeholder="First Name*" value="<?= htmlspecialchars($first_name) ?>" required>
            <input type="text" name="last_name" placeholder="Last Name*" value="<?= htmlspecialchars($last_name) ?>" required>
            <input type="email" name="email" placeholder="Email Address*" value="<?= htmlspecialchars($email) ?>" required>
            <input type="password" name="password" placeholder="Password*" required>
            <button type="submit" class="save-btn">Save</button>
        </form>

        <a href="stepxlogout.php" class="signout-btn">Sign out</a>
    </div>

    <div class="theme-buttons">
  <button onclick="setDarkMode()">
      <i class="fa fa-moon"></i> <!-- Moon Icon -->
  </button>
  <button onclick="setLightMode()">
      <i class="fa fa-sun"></i> <!-- Sun Icon -->
  </button>
</div>

<script>
    let currentSlide = 0;

    function changeSlide(direction) {
        const slides = document.querySelectorAll('.brand-logo');
        currentSlide += direction;

        if (currentSlide < 0) {
            currentSlide = slides.length - 1;
        } else if (currentSlide >= slides.length) {
            currentSlide = 0;
        }

        // Hide all slides
        slides.forEach(slide => slide.style.display = 'none');
        
        // Show current slide
        slides[currentSlide].style.display = 'block';
    }

    // Initial display setup
    changeSlide(0);

    function setDarkMode() {
        document.body.classList.add('dark-mode');
    }

    function setLightMode() {
        document.body.classList.remove('dark-mode');
    }
</script>

</body>
</html>