<!--stepx_signup.php-->
<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
session_start(); // Start session to store user info


$servername = "localhost";
$username = "root";
$password = "root"; // Default MAMP MySQL password
$dbname = "stepx_db";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    $sql = "INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $first_name, $last_name, $email, $password);

    try {
        if ($stmt->execute()) {
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
            $_SESSION['email'] = $email;
    
            header("Location: stepx_index_home.php");
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            echo "<p style='color:red;'>An account with that email already exists. Try logging in.</p>";
        } else {
            echo "Signup error: " . $e->getMessage();
        }
    }
    

    $stmt->close();
}
$conn->close();
?>


<!--stepx_signup.html-->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>StepX - Sign Up</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="Loginstyles.css">
</head>
<body>
  <div class="logo-box">
    <div class="logo">StepX</div>
  </div>

    <div class="form-box">
      <div class="tabs">
        <a href="stepx_signup.php" class="tab active">Sign Up</a>
        <a href="stepx_login.php" class="tab">Log In</a>
        
      </div>

      <form action="stepx_signup.php" method="POST">
        <h2>Sign Up</h2>
      
        <label for="first_name">First Name</label>
        <input type="text" id="first_name" name="first_name" placeholder="First Name*" required>
      
        <label for="last_name">Last Name</label>
        <input type="text" id="last_name" name="last_name" placeholder="Last Name*" required>
      
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="Email Address*" required>
      
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Password*" required>
      
        <button type="submit" class="btn">Sign Up</button>
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
