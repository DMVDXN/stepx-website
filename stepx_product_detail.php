<?php
session_start();
include 'stepx_db.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // TEMP: simulate user
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['favorite'])) {
  $product_id = intval($_POST['favorite']);
  $user_id = $_SESSION['user_id'];

  // Check if already favorited
  $check = $conn->prepare("SELECT * FROM favorites WHERE user_id = ? AND product_id = ?");
  $check->bind_param("ii", $user_id, $product_id);
  $check->execute();
  $check->store_result();

  if ($check->num_rows === 0) {
      // Not favorited yet — insert it
      $insert = $conn->prepare("INSERT INTO favorites (user_id, product_id) VALUES (?, ?)");
      $insert->bind_param("ii", $user_id, $product_id);
      $insert->execute();
  }
}

// Handle Add to Cart POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
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

// Check if this product is already favorited by the user
$is_favorited = false;
$check_fav = $conn->prepare("SELECT 1 FROM favorites WHERE user_id = ? AND product_id = ?");
$check_fav->bind_param("ii", $user_id, $product_id);
$check_fav->execute();
$check_fav->store_result();
if ($check_fav->num_rows > 0) {
    $is_favorited = true;
}
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

<div style="height: 50px;"></div>

<div class="product-detail-page">
  <div class="left-column">
    <img class="product-image" src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="Product Image" />
    <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
  </div>

  <div class="right-column">
  <div class="product-header">
  <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>
</div>

<div class="size-price-row">
  <label>Select Size</label>
  <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>
</div>

<div class="sizes-container">
  <?php
    $sizes = ["M7/W8.5", "M7.5/W9", "M8/W9.5", "M8.5/W10", "M9/W10.5", "M9.5/W11", "M10/W11.5", "M10.5/W12", "M11/W12.5", "M11.5/W13", "M12/W13.5", "M13/W14.5", "M14/W15.5", "M15/W16.5", "M17/W18.5"];
    foreach ($sizes as $size) {
        echo "<button type='button' class='size-option'>$size</button>";
    }
  ?>
</div>

<form id="add-to-cart-form">
  <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
  <input type="hidden" name="quantity" value="1" />
  <button type="submit" class="add-to-cart-btn">Add to Cart</button>
</form>

<button class="favorite-btn" data-product-id="<?php echo $product['id']; ?>">
    <span><?php echo $is_favorited ? 'Favorited' : 'Favorite'; ?></span>
    <i class="fa<?php echo $is_favorited ? 's' : 'r'; ?> fa-heart"></i>
</button>

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

</script>


<script>
document.getElementById('add-to-cart-form').addEventListener('submit', function (e) {
  e.preventDefault(); // Prevent page reload

  const form = e.target;
  const formData = new FormData(form);

  fetch(window.location.href, {
    method: 'POST',
    body: formData
  })
  .then(res => res.text())
  .then(() => {
    const button = form.querySelector('.add-to-cart-btn');
    button.classList.add('added-to-cart');
    button.textContent = 'Added to Cart ✔';

    setTimeout(() => {
      button.classList.remove('added-to-cart');
      button.textContent = 'Add to Cart';
    }, 2000);
  })
  .catch(err => console.error('Add to cart failed:', err));
});
</script>



<script>
// Toggle favorite heart icon
document.querySelector('.favorite-btn').addEventListener('click', function () {
  const heartIcon = this.querySelector('i');
  const textSpan = this.querySelector('span');

  heartIcon.classList.toggle('fas');
  heartIcon.classList.toggle('far');
  this.classList.toggle('favorited');

  // 🔁 Toggle the button text
  if (this.classList.contains('favorited')) {
    textSpan.textContent = 'Favorited';
  } else {
    textSpan.textContent = 'Favorite';
  }
});

</script>

<script>
document.querySelector('.favorite-btn').addEventListener('click', function (e) {
    e.preventDefault();

    const button = this;
    const productId = button.getAttribute('data-product-id');

    fetch('toggle_favorite.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'product_id=' + encodeURIComponent(productId)
    })
    .then(response => response.json())
    .then(data => {
        if (data.favorited) {
            button.classList.add('favorited');
            button.querySelector('span').textContent = 'Favorited';
            button.querySelector('i').classList.remove('far');
            button.querySelector('i').classList.add('fas');
        } else {
            button.classList.remove('favorited');
            button.querySelector('span').textContent = 'Favorite';
            button.querySelector('i').classList.remove('fas');
            button.querySelector('i').classList.add('far');
        }
    });
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
