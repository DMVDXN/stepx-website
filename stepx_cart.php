<!--stepx_cart.php-->
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include 'stepx_db.php';

$user_id = $_SESSION['user_id'] ?? null;
$cart_items = [];
$subtotal = 0;

if ($user_id) {
    $sql = "SELECT c.id AS cart_id, c.product_id, p.name, p.image_path, p.price, c.quantity, c.size
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $cart_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Calculate subtotal early
    foreach ($cart_items as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
}

$promo_discount = 0;
$promo_error = "";

// Promo code logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['promo_code'])) {
    $entered_code = trim($_POST['promo_code']);

    $promo_sql = "SELECT * FROM promo_codes WHERE code = ? AND active = 1 AND (expiration_date IS NULL OR expiration_date >= CURDATE())";
    $promo_stmt = $conn->prepare($promo_sql);
    $promo_stmt->bind_param("s", $entered_code);
    $promo_stmt->execute();
    $promo_result = $promo_stmt->get_result();

    if ($promo_row = $promo_result->fetch_assoc()) {
        if (!empty($promo_row['discount_amount'])) {
            $promo_discount = floatval($promo_row['discount_amount']);
        } elseif (!empty($promo_row['discount_percent'])) {
            $promo_discount = $subtotal * (floatval($promo_row['discount_percent']) / 100);
        }

        $_SESSION['applied_promo_code'] = $entered_code;
    } else {
        $promo_error = "Invalid or expired promo code.";
    }
}

$order_total = max(0, $subtotal + 9 - $promo_discount); // 9 is static sales tax
?>

<!-- HTML STARTS BELOW -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Cart - StepX</title>
    <link rel="stylesheet" href="stepx_cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<!-- HEADER -->
<header class="top-bar">
    <a href="stepx_index_home.php" class="logo">StepX</a>
    <div class="search-container">
        <i class="fa fa-search search-icon"></i>
        <input type="text" placeholder="Search for brand, color, etc">
    </div>
    <div class="icons">
        <a href="stepx_favorites.php" class="favorites">
            <i class="fa fa-heart"></i><span>Favorites</span>
        </a>
        <a href="stepx_cart.php">
            <i class="fa fa-shopping-cart" style="color: #0056e0;"></i>
        </a>
        <a href="stepx_notifications.php"><i class="fa fa-bell"></i></a>
        <div class="profile-hover">
            <a href="stepx_profile.php"><i class="fa fa-user"></i></a>
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
        <a href="#">Brands</a><a href="#">New</a><a href="#">Men</a><a href="#">Women</a><a href="#">Kids</a><a href="#">Sneakers</a><a href="#">Shoes</a>
    </div>
</div>

<main class="cart-main">
    <section class="cart-left">
        <h1>My Cart</h1>
        <hr>

        <?php if (!empty($cart_items)): ?>
            <?php foreach ($cart_items as $item): ?>
                <div class="cart-item">
                    <a href="stepx_product_detail.php?id=<?= $item['product_id'] ?>">
                        <img src="<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                    </a>
                    <div class="item-info">
                        <div class="item-details">
                            <h2><a href="stepx_product_detail.php?id=<?= $item['product_id'] ?>"><?= htmlspecialchars($item['name']) ?></a></h2>
                            <p>Unisex</p>
                            <p>Size: <?= htmlspecialchars($item['size']) ?> | Standard</p>
                            <div class="qty-controls">
                                <button class="decrease" data-cart-id="<?= $item['cart_id'] ?>">–</button>
                                <span class="qty" data-cart-id="<?= $item['cart_id'] ?>">QTY <?= $item['quantity'] ?></span>
                                <button class="increase" data-cart-id="<?= $item['cart_id'] ?>">+</button>
                            </div>
                        </div>
                        <div class="item-price">
                            <p class="price" data-cart-id="<?= $item['cart_id'] ?>" data-unit-price="<?= $item['price'] ?>">
                                $<?= number_format($item['price'] * $item['quantity'], 2) ?>
                            </p>
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
        <form method="POST" class="promo-form">
  <label for="promo">Promo Code</label>
  <input type="text" id="promo" name="promo_code" placeholder="Enter code"
         value="<?= htmlspecialchars($_POST['promo_code'] ?? '') ?>">
  <button type="submit">Apply</button>
</form>

<?php if (!empty($promo_error)): ?>
  <p class="promo-error"><?= htmlspecialchars($promo_error) ?></p>
<?php elseif (!empty($_SESSION['applied_promo_code'])): ?>
  <p class="promo-message">
    Promo "<strong><?= htmlspecialchars($_SESSION['applied_promo_code']) ?></strong>" applied!
  </p>
<?php endif; ?>


        <div class="summary-line"><span>Sub Total</span><span id="subtotal-amount">$<?= number_format($subtotal, 2) ?></span></div>
        <div class="summary-line"><span>Shipping: Standard</span><span>FREE</span></div>
        <div class="summary-line"><span>Sales tax</span><span>$9.00</span></div>
        <div class="summary-line"><span>Promo Discount</span><span>–$<?= number_format($promo_discount, 2) ?></span></div>
        <div class="summary-line total"><span>Order Total</span><span>$<?= number_format($order_total, 2) ?></span></div>

        <?php if (!empty($cart_items)): ?>
            <form action="stepx_checkout.php" method="POST">
                <button type="submit" class="checkout-btn">Checkout</button>
            </form>
        <?php endif; ?>
    </section>
</main>

<!-- Dark mode toggle -->
<div class="theme-buttons">
    <button onclick="setDarkMode()"><i class="fa fa-moon"></i></button>
    <button onclick="setLightMode()"><i class="fa fa-sun"></i></button>
</div>

<script>
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

    document.querySelectorAll('.increase, .decrease').forEach(button => {
        button.addEventListener('click', function () {
            const cartId = this.getAttribute('data-cart-id');
            const isIncrease = this.classList.contains('increase');
            const qtySpan = document.querySelector(`.qty[data-cart-id="${cartId}"]`);
            const priceEl = document.querySelector(`.price[data-cart-id="${cartId}"]`);
            const unitPrice = parseFloat(priceEl.dataset.unitPrice);

            fetch('update_quantity.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `cart_id=${cartId}&action=${isIncrease ? 'increase' : 'decrease'}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    let currentQty = parseInt(qtySpan.textContent.replace('QTY', '').trim());
                    currentQty = isIncrease ? currentQty + 1 : Math.max(1, currentQty - 1);
                    qtySpan.textContent = `QTY ${currentQty}`;
                    priceEl.textContent = `$${(unitPrice * currentQty).toFixed(2)}`;
                } else {
                    alert("Failed to update quantity.");
                }
            });
        });
    });
</script>

</body>
</html>
