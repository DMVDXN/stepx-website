<!--stepx_cart.php-->
<?php
session_start();
    $conn = new mysqli("localhost", "root", "root", "stepx_db");

$cart_items = $_SESSION['cart'] ?? [];
$products = [];

if (!empty($cart_items)) {
    $ids = implode(",", array_map('intval', $cart_items));
    $result = $conn->query("SELECT * FROM products WHERE id IN ($ids)");

    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart - StepX</title>
    <link rel="stylesheet" href="stepx_cart.css">
</head>
<body>
    <h1>Your Shopping Cart</h1>

    <?php if (empty($products)) : ?>
        <p>Your cart is empty.</p>
    <?php else : ?>
        <ul>
            <?php foreach ($products as $product): ?>
                <li>
                    <img src="<?= htmlspecialchars($product['image_path']) ?>" width="100">
                    <strong><?= htmlspecialchars($product['name']) ?></strong> - $<?= $product['price'] ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>
