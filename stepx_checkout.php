<!--stepx_checkout.php-->
<?php
session_start();
include 'stepx_db.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header("Location: stepx_login.html");
    exit();
}

// Clear the cart from the database
$delete = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
$delete->bind_param("i", $user_id);
$delete->execute();
$delete->close();

// Optional: clear cart session
unset($_SESSION['cart']);

// ✅ Redirect to the success page
header("Location: stepx_success.php");
exit();
?>
