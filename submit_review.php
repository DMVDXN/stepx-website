<!--submit_review.php-->
<?php
session_start();
include 'stepx_db.php';

if (isset($_SESSION['user_id']) && $_POST['product_id'] && $_POST['rating'] && $_POST['comment']) {
    $user_id = $_SESSION['user_id'];
    $product_id = intval($_POST['product_id']);
    $rating = intval($_POST['rating']);
    $comment = $conn->real_escape_string($_POST['comment']);

    // Check if review exists
    $check_sql = "SELECT * FROM reviews WHERE user_id = $user_id AND product_id = $product_id";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // Update review
        $update_sql = "UPDATE reviews SET rating = $rating, comment = '$comment', created_at = CURRENT_TIMESTAMP
                       WHERE user_id = $user_id AND product_id = $product_id";
        $conn->query($update_sql);
    } else {
        // New review
        $insert_sql = "INSERT INTO reviews (user_id, product_id, rating, comment)
                       VALUES ($user_id, $product_id, $rating, '$comment')";
        $conn->query($insert_sql);
    }

    header("Location: stepx_product_detail.php?id=$product_id");
    exit();
} else {
    echo "Invalid input.";
}
?>