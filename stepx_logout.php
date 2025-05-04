<!--stepx_logout.php-->
<?php
session_start();
session_unset(); // Clear all session variables
session_destroy(); // Destroy the session

// Redirect to login page
header("Location: stepx_login.php");
exit;
?>
