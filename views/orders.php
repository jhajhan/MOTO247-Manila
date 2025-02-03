<?php
session_start();
include('F:\xampp\htdocs\MOTO247-Manila\includes\user_function.php');
include('F:\xampp\htdocs\MOTO247-Manila\views\includes\header.php');
include('F:\xampp\htdocs\MOTO247-Manila\views\includes\navbar.php');

if (!isset($_SESSION['auth_user']['user_id'])) {
    $_SESSION['message'] = "You need to log in first to access the cart.";        
    header("Location: login.php"); // Redirect to login page
    exit(); // Stop further execution
}

?>


<div class="py-3 bg-primary">
    <div class="container">
        <h1 class="text-white">Orders</h1>
    </div>
</div>

<!-- Cart table section, outside the blue header -->
<div class="py-5">
    <div class="container">
        <div class="card card-body shadow">
            <div class="row">
                <div class="col-md-12">
                </div>
        </div>
    </div>
</div>

<?php
include('F:\xampp\htdocs\MOTO247-Manila\views\includes\footer.php'); 
?>

