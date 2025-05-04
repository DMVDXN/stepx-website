<!--remove_from_cart.php-->
<?php
include 'stepx_db.php';
session_start();

$cart_id = intval($_POST['cart_id']);

if ($cart_id > 0) {
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
}

header("Location: stepx_cart.php");
exit;
