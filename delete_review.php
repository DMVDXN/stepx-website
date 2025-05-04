<?php
session_start();
include 'stepx_db.php';

if (isset($_SESSION['user_id'], $_GET['product_id'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = intval($_GET['product_id']);

    $sql = "DELETE FROM reviews WHERE user_id = $user_id AND product_id = $product_id";
    if ($conn->query($sql) === TRUE) {
        header("Location: stepx_product_detail.php?id=$product_id");
        exit();
    } else {
        echo "Error deleting review: " . $conn->error;
    }
} else {
    echo "Unauthorized or missing parameters.";
}
?>