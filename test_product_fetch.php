<?php
include 'stepx_db.php';

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(["error" => "Missing product ID"]);
    exit;
}

$product_id = intval($_GET['id']);

$sql = "SELECT * FROM products WHERE id = $product_id";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["error" => "SQL failed: " . $conn->error]);
    exit;
}

if ($row = $result->fetch_assoc()) {
    echo json_encode($row);
} else {
    echo json_encode(["error" => "No product found with ID = $product_id"]);
}
?>
