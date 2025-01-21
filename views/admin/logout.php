<?php
session_start();
unset($_SESSION['authenticated']);
unset($_SESSION['auth_user']);
$_SESSION['status'] = "You have been logged out";
// session_unset(); // Unset all session variables
// session_destroy(); // Destroy the session
header('Location: login.php');
exit(0);

?>