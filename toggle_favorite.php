<!--toggle_favorite.php-->
<?php
session_start();
header('Content-Type: application/json');
include 'stepx_db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = intval($_POST['product_id']);

    // Check if already favorited
    $check = $conn->prepare("SELECT id FROM favorites WHERE user_id = ? AND product_id = ?");
    $check->bind_param("ii", $user_id, $product_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        // Already favorited → remove it
        $delete = $conn->prepare("DELETE FROM favorites WHERE user_id = ? AND product_id = ?");
        $delete->bind_param("ii", $user_id, $product_id);
        $delete->execute();
        echo json_encode(['favorited' => false]);
    } else {
        // Not favorited → insert it
        $insert = $conn->prepare("INSERT INTO favorites (user_id, product_id) VALUES (?, ?)");
        $insert->bind_param("ii", $user_id, $product_id);
        $insert->execute();
        echo json_encode(['favorited' => true]);
    }

} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>
