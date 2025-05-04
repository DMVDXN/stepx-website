<?php
session_start();
include 'stepx_db.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: stepx_login.html");
    exit();
}

// Fetch all reviews submitted by this user
$sql = "SELECT r.*, p.name AS product_name 
        FROM reviews r 
        JOIN products p ON r.product_id = p.id 
        WHERE r.user_id = ? 
        ORDER BY r.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$reviews = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>StepX - Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="stepx_notifications.css">
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
        <i class="fa fa-shopping-cart"></i>
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


<div style="height: 50px;"></div>

<div class="notification-page">
    <h1>Notifications</h1>
    <p class="placeholder">You don’t have any notifications yet.</p>

    <h2>Your Reviews</h2>
    <?php if ($reviews->num_rows > 0): ?>
        <div class="user-reviews">
        <?php while ($row = $reviews->fetch_assoc()): ?>
            <div class="review-item">
                <strong><?= htmlspecialchars($row['product_name']) ?></strong>
                <div class="stars">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="fa<?= $i <= $row['rating'] ? 's' : 'r' ?> fa-star"></i>
                    <?php endfor; ?>
                </div>
                <p><?= htmlspecialchars($row['comment']) ?></p>
            </div>
            <form method="POST" action="delete_review_notifications.php" onsubmit="return confirm('Delete this review?');">
    <input type="hidden" name="review_id" value="<?= $row['id'] ?>">
    <button type="submit" class="delete-btn">Delete</button>
</form>


        <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="no-reviews">You haven’t written any reviews yet.</p>
    <?php endif; ?>
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
        slides[currentSlide].style.display = 'block';
    }

    // Initial display setup
    changeSlide(0);

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
