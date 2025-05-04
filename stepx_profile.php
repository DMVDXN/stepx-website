<!--stepx_profile.php-->
<?php
session_start();
include 'stepx_db.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header("Location: stepx_login.html");
    exit();
}

$success = $error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $first = trim($_POST['first_name']);
    $last = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $first, $last, $email, $hashed, $user_id);
    } else {
        $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $first, $last, $email, $user_id);
    }

    if ($stmt->execute()) {
        $_SESSION['first_name'] = $first;
        $_SESSION['last_name'] = $last;
        $_SESSION['email'] = $email;
        $success = "Profile updated successfully!";
    } else {
        $error = "Failed to update profile.";
    }
}

// Always get fresh user data from DB
$query = $conn->prepare("SELECT first_name, last_name, email FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$query->bind_result($first_name, $last_name, $email);
$query->fetch();
$query->close();
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

    <a href="stepx_cart.php">
        <i class="fa fa-shopping-cart"></i>
    </a>

    <a href="stepx_notifications.php">
    <i class="fa fa-bell"></i>
    </a>

    <div class="profile-hover">
  <a href="stepx_profile.php">
    <i class="fa fa-user" style="color: #0056e0;"></i>
  </a>
</div>

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

        <a href="stepx_logout.php" class="signout-btn">Sign out</a>
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