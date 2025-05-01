<?php
// stepx_product_detail.php

include 'stepx_db.php'; // Your database connection

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $sql = "SELECT * FROM products WHERE id = $product_id"; // ✅ id instead of product_id
    $result = $conn->query($sql);

    if ($row = $result->fetch_assoc()) {
        echo json_encode($row); // ✅ send the entire row (with name, description, price, image_path)
    } else {
        echo json_encode(["error" => "Product not found."]);
    }
} else {
    echo json_encode(["error" => "No product ID provided."]);
}
?>
