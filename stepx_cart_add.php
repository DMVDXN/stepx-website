<!--stepx_cart_add.php-->
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
    $size = $_POST['size'] ?? '';

    if (empty($size)) {
        echo json_encode(['success' => false, 'message' => 'Size is required']);
        exit();
    }

    $conn = new mysqli("localhost", "root", "root", "stepx_db");
    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'message' => 'DB connection error']);
        exit();
    }

    // Check if same product + size already exists
    $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ? AND size = ?");
    $stmt->bind_param("iis", $user_id, $product_id, $size);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($cart_id, $existing_qty);
        $stmt->fetch();
        $stmt->close();

        $new_qty = $existing_qty + $quantity;
        $update = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $update->bind_param("ii", $new_qty, $cart_id);
        $update->execute();
        $update->close();
    } else {
        $stmt->close();
        $insert = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, size) VALUES (?, ?, ?, ?)");
        $insert->bind_param("iiis", $user_id, $product_id, $quantity, $size);
        $insert->execute();
        $insert->close();
    }

    $conn->close();
    echo json_encode(['success' => true, 'message' => 'Added to cart']);
}

