<!--stepx_cart.php-->
<?php
session_start();
include 'stepx_db.php';



// Get current user's ID (assumes login system sets $_SESSION['user_id'])
$user_id = $_SESSION['user_id'] ?? null;

$cart_items = [];
$subtotal = 0;

if ($user_id) {
    $sql = "SELECT c.id AS cart_id, p.name, p.image_path, p.price, c.quantity, c.size
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $cart_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Cart - StepX</title>
    <link rel="stylesheet" href="stepx_cart.css">
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
        <i class="fa fa-shopping-cart"></i>
    </a>

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

<main class="cart-main">
    <section class="cart-left">
        <h1>My Cart</h1>
        <hr>

        <?php if (!empty($cart_items)): ?>
            <?php foreach ($cart_items as $item): 
                $total_price = $item['price'] * $item['quantity'];
                $subtotal += $total_price;
            ?>
                <div class="cart-item">
                    <img src="<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                    <div class="item-details">
                        <h2><?= htmlspecialchars($item['name']) ?></h2>
                        <p>Unisex</p>
                        <p>Size: <?= htmlspecialchars($item['size']) ?> | Standard</p>
                        <p class="price">$<?= number_format($total_price, 2) ?></p>

                        <div class="qty-controls">
                            <button class="decrease">–</button>
                            <span class="qty">QTY <?= $item['quantity'] ?></span>
                            <button class="increase">+</button>
                        </div>
                    </div>
                    <form method="POST" action="remove_from_cart.php">
                        <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                        <button class="remove-btn"><i class="fa fa-times"></i></button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Your cart is currently empty.</p>
        <?php endif; ?>
    </section>

    <section class="cart-right">
        <h2>Order Summary</h2>
        <form method="POST" class="promo-form" style="margin-bottom: 15px;">
    <label for="promo">Promo Code</label>
    <input type="text" id="promo" name="promo_code" placeholder="Enter code" value="<?= htmlspecialchars($_POST['promo_code'] ?? '') ?>">
    <button type="submit">Apply</button>
</form>
        <div class="summary-line"><span>Sub Total</span><span>$<?= number_format($subtotal, 2) ?></span></div>
        <div class="summary-line"><span>Shipping: Standard</span><span>FREE</span></div>
        <div class="summary-line"><span>Sales tax</span><span>$9.00</span></div>
        <div class="summary-line"><span>Promo Discount</span><span>–$<?= number_format($promo_discount, 2) ?></span></div>
<div class="summary-line total">
    <span>Order Total</span>
    <span>$<?= number_format($subtotal + 9 - $promo_discount, 2) ?></span>
</div>
        <button class="checkout-btn">Checkout</button>
    </section>
</main>

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

