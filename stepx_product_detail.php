<?php
session_start();
include 'stepx_db.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // TEMP: simulate user
}

$user_id = $_SESSION['user_id'];

// Handle Add to Cart POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity'] ?? 1);

    $check = $conn->prepare("SELECT id FROM cart WHERE user_id = ? AND product_id = ?");
    $check->bind_param("ii", $user_id, $product_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('Item is already in your cart.');</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $user_id, $product_id, $quantity);
        if ($stmt->execute()) {
            echo "<script>alert('Item added to cart!');</script>";
        } else {
            echo "<script>alert('Failed to add to cart.');</script>";
        }
    }
}

if (!isset($_GET['id'])) {
    die("No product ID provided.");
}

$product_id = intval($_GET['id']);
$sql = "SELECT * FROM products WHERE id = $product_id";
$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
    die("Product not found.");
}

$product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title><?php echo htmlspecialchars($product['name']); ?> - StepX</title>
  <link rel="stylesheet" href="stepx_product_detail.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<body>

<header class="top-bar">
  <div class="logo">StepX</div>

  <div class="search-container">
    <i class="fa fa-search search-icon"></i>
    <input type="text" placeholder="Search for brand, color, etc" />
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

<div class="product-detail-page">
  <div class="left-column">
    <img class="product-image" src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="Product Image" />
    <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
  </div>

  <div class="right-column">
    <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>
    <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>

    <form method="POST">
      <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
      <label>Select Size</label>
      <div class="sizes-container">
        <?php
        $sizes = ["M7/W8.5", "M7.5/W9", "M8/W9.5", "M8.5/W10", "M9/W10.5", "M9.5/W11", "M10/W11.5", "M10.5/W12", "M11/W12.5", "M11.5/W13", "M12/W13.5", "M13/W14.5", "M14/W15.5", "M15/W16.5", "M17/W18.5",];
        foreach ($sizes as $size) {
            echo "<button type='button' class='size-option'>$size</button>";
        }
        ?>
      </div>

      <input type="hidden" name="quantity" value="1" />
      <button type="submit" name="add_to_cart" class="add-to-cart-btn">Add to Cart</button>
    </form>

    <button class="favorite-btn">Favorite <i class="far fa-heart"></i></button>
  </div>
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
document.addEventListener('click', function(e) {
  if (e.target.classList.contains('size-option')) {
    document.querySelectorAll('.size-option').forEach(btn => btn.classList.remove('selected'));
    e.target.classList.add('selected');
  }
});

// Toggle favorite heart icon
document.querySelector('.favorite-btn').addEventListener('click', function() {
  const heartIcon = this.querySelector('i');
  heartIcon.classList.toggle('fas');
  heartIcon.classList.toggle('far');
});
</script>

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
