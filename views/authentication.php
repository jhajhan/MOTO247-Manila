<?php
 // sira nakaka inis
// If the user is not authenticated, redirect to the login page
if (!isset($_SESSION['authenticated'])) {
    $_SESSION['status'] = "You need to login first";
    header('Location: login.php');
    exit(0);
}

// If the user is authenticated, no action is needed and they can proceed
?>
