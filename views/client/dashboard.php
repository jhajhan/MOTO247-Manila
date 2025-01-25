<?php 
session_start();
include('authentication.php'); // ✅ Correct path
include('F:\xampp\htdocs\MOTO247-Manila\views\includes\header.php'); 

$page_title = "Home Page";
include('F:\xampp\htdocs\MOTO247-Manila\views\includes\navbar.php'); 
?>

<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- Message Prompt -->
                <?php
                if(isset($_SESSION['status']))
                {
                    ?>
                    <div class="alert alert-success">
                        <h5><?= $_SESSION['status'];?></h5>
                    </div>
                    <?php
                    unset($_SESSION['status']);
                }
                ?>

                <div class="card shadow">
                    <div class="card-header">
                        <h5>Dashboard</h5>
                    </div>
                    <div class="card-body">
                        <h4 class="card-title">Welcome to the Dashboard</h4>
                        <hr>

                        <?php if(isset($_SESSION['auth_user'])) { ?>
                            <h6>Hello <?= $_SESSION['auth_user']['username']; ?></h6>
                        <?php } else { ?>
                            <h6>Hello, Guest</h6>
                        <?php } ?>
                
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('F:\xampp\htdocs\MOTO247-Manila\views\includes\footer.php'); // ✅ Corrected path ?>
