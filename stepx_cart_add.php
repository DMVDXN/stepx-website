<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id'];
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity'] ?? 1);

    $conn = new mysqli("localhost", "root", "root", "stepx_db");
    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'message' => 'DB error']);
        exit();
    }

    // Optional: check if already in cart and update quantity instead
    $stmt = $conn->prepare("SELECT id, quantity FROM cart_items WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Update quantity
        $stmt->bind_result($cart_id, $existing_qty);
        $stmt->fetch();
        $stmt->close();

        $new_qty = $existing_qty + $quantity;
        $update = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
        $update->bind_param("ii", $new_qty, $cart_id);
        $update->execute();
        $update->close();
    } else {
        $stmt->close();
        $insert = $conn->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $insert->bind_param("iii", $user_id, $product_id, $quantity);
        $insert->execute();
        $insert->close();
    }

    $conn->close();
    echo json_encode(['success' => true, 'message' => 'Added to cart']);
}
