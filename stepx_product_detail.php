<!--stepx_product_detail.php-->
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

// Check if the user has already left a review for this product
$existing_review = null;
if (isset($_SESSION['user_id'])) {
    $check_review = $conn->query("SELECT * FROM reviews WHERE user_id = $user_id AND product_id = $product_id");
    if ($check_review && $check_review->num_rows > 0) {
        $existing_review = $check_review->fetch_assoc();
    }
}

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
  <input type="hidden" name="size" id="selected-size" required>
  <button type="submit" class="add-to-cart-btn">Add to Cart</button>
</form>

<button class="favorite-btn" data-product-id="<?php echo $product['id']; ?>">
    <span><?php echo $is_favorited ? 'Favorited' : 'Favorite'; ?></span>
    <i class="fa<?php echo $is_favorited ? 's' : 'r'; ?> fa-heart"></i>
</button>

  </div>
</div>



<!-- Review Section -->
<div class="review-section">
  <h2>Leave a Review</h2>
  <?php if (isset($_SESSION['user_id'])): ?>
    <form action="submit_review.php" method="POST" class="review-form">
        <input type="hidden" name="product_id" value="<?= $product_id ?>">

        <label for="rating">Your Rating:</label>
        <div class="star-rating">
            <?php for ($i = 5; $i >= 1; $i--): ?>
                <input type="radio" name="rating" id="star<?= $i ?>" value="<?= $i ?>"
                       <?= isset($existing_review) && $existing_review['rating'] == $i ? 'checked' : '' ?> required>
                <label for="star<?= $i ?>">&#9733;</label>
            <?php endfor; ?>
        </div>

        <label for="comment">Your Review:</label>
        <textarea name="comment" rows="4" required><?= $existing_review['comment'] ?? '' ?></textarea>
        <br>
        <button type="submit"><?= $existing_review ? 'Update Review' : 'Submit Review' ?></button>

        <?php if ($existing_review): ?>
            <a href="delete_review.php?product_id=<?= $product_id ?>"
               onclick="return confirm('Are you sure you want to delete your review?');"
               class="delete-review-btn">Delete Review</a>
        <?php endif; ?>
    </form>
  <?php else: ?>
    <p><a href="StepX_login.php">Log in</a> to leave a review.</p>
  <?php endif; ?>
</div>

<div class="review-list">
  <h3 class="review-subtitle">Customer Reviews</h3>

  <?php
  $sql_reviews = "SELECT r.rating, r.comment, r.created_at, u.first_name
                  FROM reviews r
                  JOIN users u ON r.user_id = u.id
                  WHERE r.product_id = $product_id
                  ORDER BY r.created_at DESC";
  $result_reviews = $conn->query($sql_reviews);

  if ($result_reviews->num_rows > 0):
      while ($row = $result_reviews->fetch_assoc()):
  ?>
    <div class="review-card">
      <div class="review-header">
        <div class="review-user"><?= htmlspecialchars($row['first_name']) ?></div>
        <div class="review-stars">
          <?php for ($i = 1; $i <= 5; $i++): ?>
            <span class="star<?= $i <= $row['rating'] ? ' filled' : '' ?>">&#9733;</span>
          <?php endfor; ?>
        </div>
      </div>
      <div class="review-body">
        <p class="review-comment"><?= htmlspecialchars($row['comment']) ?></p>
        <span class="review-date"><?= date("F j, Y", strtotime($row['created_at'])) ?></span>
      </div>
    </div>
  <?php endwhile; else: ?>
    <p class="no-reviews">No reviews yet. Be the first to leave one!</p>
  <?php endif; ?>
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
document.getElementById('add-to-cart-form').addEventListener('submit', function (e) {
  e.preventDefault(); // Prevent page reload

  const form = e.target;
  const formData = new FormData(form);

  fetch('stepx_cart_add.php', {
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
  document.querySelectorAll('.size-option').forEach(btn => {
    btn.addEventListener('click', () => {
      // Unselect all
      document.querySelectorAll('.size-option').forEach(b => b.classList.remove('selected'));
      
      // Select this one
      btn.classList.add('selected');

      // Set hidden input
      document.getElementById('selected-size').value = btn.textContent.trim();
    });
  });
</script>


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