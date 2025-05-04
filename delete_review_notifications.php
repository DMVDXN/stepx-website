<!--delete_review_notifications.php-->
<?php
session_start();
include 'stepx_db.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id || !isset($_POST['review_id'])) {
    header("Location: stepx_login.html");
    exit();
}

$review_id = intval($_POST['review_id']);

$stmt = $conn->prepare("DELETE FROM reviews WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $review_id, $user_id);
$stmt->execute();

header("Location: stepx_notifications.php");
exit();
?>
