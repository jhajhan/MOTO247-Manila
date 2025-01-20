<?php 
session_start();
include('authentication.php');
include('includes/header.php'); 
$page_title ="Home Page"?>
<?php include('includes/navbar.php'); ?>


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
                        <h6>Hello <?= $_SESSION['auth_user']['username'];?></h5>
                
            </div>
        </div>
    </div>
</div>


<?php include('includes/footer.php'); ?>


