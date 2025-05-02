<?php
session_start();
include 'stepx_db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // ✅ Add to Cart functionality
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'User not logged in']);
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity'] ?? 1);

    // Check if product already exists in cart
    $check = $conn->prepare("SELECT id, quantity FROM cart_items WHERE user_id = ? AND product_id = ?");
    $check->bind_param("ii", $user_id, $product_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $check->bind_result($cart_id, $existing_qty);
        $check->fetch();
        $check->close();

        $new_qty = $existing_qty + $quantity;
        $update = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
        $update->bind_param("ii", $new_qty, $cart_id);
        $update->execute();
        $update->close();
    } else {
        $check->close();
        $insert = $conn->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $insert->bind_param("iii", $user_id, $product_id, $quantity);
        $insert->execute();
        $insert->close();
    }

    echo json_encode(['success' => true, 'message' => 'Added to cart']);
    exit();
}

// ✅ Handle product fetch for GET requests
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode($row);
    } else {
        echo json_encode(["error" => "Product not found."]);
    }

    $stmt->close();
} else if ($_SERVER["REQUEST_METHOD"] === "GET") {
    echo json_encode(["error" => "No product ID provided."]);
}
?>