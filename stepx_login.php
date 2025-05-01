<?php
session_start(); // ✅ Needed to use sessions

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
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $sql = "SELECT id, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Redirect to homepage
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
