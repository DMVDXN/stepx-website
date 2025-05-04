<?php
session_start();
include 'stepx_db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart_id = intval($_POST['cart_id']);
    $change = ($_POST['action'] === 'increase') ? 1 : -1;

    // Update quantity
    $sql = "UPDATE cart SET quantity = GREATEST(1, quantity + ?) WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $change, $cart_id, $_SESSION['user_id']);
    $success = $stmt->execute();

    echo json_encode(['success' => $success]);
}
?>