<?php
include 'stepx_db.php';

$stmt = $conn->prepare("SELECT name, price, image_path FROM products");
$stmt->execute();
$stmt->bind_result($name, $price, $image_path);

$hasResults = false;

while ($stmt->fetch()):
    $hasResults = true;
?>
    <div class="product">
        <img src="<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo htmlspecialchars($name); ?>">
        <i class="fa fa-heart like-icon"></i>
        <div class="product-info">
            <h2><?php echo htmlspecialchars($name); ?></h2>
            <p>$<?php echo htmlspecialchars($price); ?></p>
        </div>
    </div>
<?php
endwhile;

if (!$hasResults) {
    echo "<p>No products found.</p>";
}

$stmt->close();
$conn->close();
?>
