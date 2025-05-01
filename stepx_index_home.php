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
    <div class="logo">StepX</div>
    <div class="search-container">
        <i class="fa fa-search search-icon"></i>
        <input type="text" placeholder="Search for brand, color, etc">
    </div>

    <div class="icons">
        <div class="favorites">
            <i class="fa fa-heart"></i>
            <span>Favorites</span>
        </div>
        <i class="fa fa-shopping-cart"></i>
        <i class="fa fa-bell"></i>
        <i class="fa fa-user"></i>
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
    <?php include 'stepx_products.php'; ?>
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
