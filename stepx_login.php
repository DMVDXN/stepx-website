<!--stepx_login.php-->
<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "root"; // MAMP default
$dbname = "stepx_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $sql = "SELECT id, first_name, last_name, email, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $first_name, $last_name, $email, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
            $_SESSION['email'] = $email;

            header("Location: stepx_index_home.php");
            exit();
        } else {
            echo "Invalid credentials.";
        }
    } else {
        echo "No account found with that email.";
    }

    $stmt->close();
}

$conn->close();
?>


<!--stepx_login.html-->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>StepX - Login</title>
  <link rel="stylesheet" href="Loginstyles.css" />
</head>
<body>
  <div class="logo-box">
    <div class="logo">StepX</div>
  </div>

  <div class="form-box">
    <div class="tabs">
      <a href="stepx_signup.html" class="tab">Sign up</a>
      <a href="#" class="tab active">Log in</a>
    </div>

    <form action="stepx_login.php" method="POST" class="form">
      <h2>Log In</h2>

      <label for="email">Email Address</label>
      <input type="text" id="email" name="email" placeholder="Email Address*" required>

      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="Password*" required>

      <div class="error-message">
        <?php if (isset($_GET['error'])) echo htmlspecialchars($_GET['error']); ?>
      </div>

      <button type="submit" class="btn">Log In</button>
    </form>
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