<!--stepx_index.home.php-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>StepX - Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="stepx_styles.css">
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

<!-- Advertisement Banner Section (With Arrows for Navigation) -->
<div class="advertisement-banner">
    <button class="arrow-left" onclick="changeSlide(-1)">&#10094;</button>
    <div class="banner-images">
        <div class="brand-logo">
            <img src="images/nike.png" alt="Nike">
        </div>
        <div class="brand-logo">
            <img src="images/jordan.png" alt="Jordan">
        </div>
        <div class="brand-logo">
            <img src="images/new balance.png" alt="New Balance">
        </div>
    </div>
    <button class="arrow-right" onclick="changeSlide(1)">&#10095;</button>
</div>

<div class="product-container">
    <h1>Recommended For You</h1>
    <?php
    include 'stepx_db.php';
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()): ?>
        <a href="stepx_product_detail.php?id=<?php echo $row['id']; ?>" class="product">
            <img src="<?php echo $row['image_path']; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
            <div class="product-info">
                <h2><?php echo htmlspecialchars($row['name']); ?></h2>
                <p>$<?php echo htmlspecialchars($row['price']); ?></p>
            </div>
        </a>
    <?php endwhile;

    ?>
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
    let currentSlide = 0;

    function changeSlide(direction) {
        const slides = document.querySelectorAll('.brand-logo');
        currentSlide += direction;

        if (currentSlide < 0) {
            currentSlide = slides.length - 1;
        } else if (currentSlide >= slides.length) {
            currentSlide = 0;
        }

        slides.forEach(slide => slide.style.display = 'none');
        slides[currentSlide].style.display = 'block';
    }

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
