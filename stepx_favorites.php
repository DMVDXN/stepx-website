<?php
session_start();
include 'stepx_db.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Temp fallback
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_favorite'])) {
    $remove_id = intval($_POST['remove_favorite']);
    $remove_stmt = $conn->prepare("DELETE FROM favorites WHERE user_id = ? AND product_id = ?");
    $remove_stmt->bind_param("ii", $user_id, $remove_id);
    $remove_stmt->execute();
}

$sql = "SELECT p.* FROM favorites f
        JOIN products p ON f.product_id = p.id
        WHERE f.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Favorites - StepX</title>
    <link rel="stylesheet" href="stepx_favorites.css">
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

<!--<h1 class="favorites-heading">Favorites</h1>-->

<div class="product-container">
    <h1 class="favorites-heading">Favorites</h1>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="product">
        <a href="stepx_product_detail.php?id=<?php echo $row['id']; ?>">
  <img src="<?php echo $row['image_path']; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
</a>

            <div class="product-info">
                <h2><?php echo htmlspecialchars($row['name']); ?></h2>
                <p>$<?php echo htmlspecialchars($row['price']); ?></p>

                <form action="stepx_cart_add.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="cart-btn">Add to Cart</button>
                </form>

                <form action="stepx_favorites.php" method="POST">
                    <input type="hidden" name="remove_favorite" value="<?php echo $row['id']; ?>">
                    <button type="submit" class="remove-btn">Remove</button>
                </form>
            </div>
        </div>
    <?php endwhile; ?>
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
    function setDarkMode() {
        document.body.classList.add('dark-mode');
    }

    function setLightMode() {
        document.body.classList.remove('dark-mode');
    }
</script>

</body>
</html>
