<?php 
session_start();
$page_title = "Registration";
include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class = "alert">
                   <?php if(isset($_SESSION['status']))
                        {
                            echo "<h4>".$_SESSION['status']."</h4>";
                            unset($_SESSION['status']);
                        }
                    ?>
                </div>
                <div class="card shadow">
                    <div class="card-header">
                        <h5>Registration Form</h5>
                    </div>
                    <div class="card-body">
                        <form action="code.php" method="POST">
                            <div class="form-group mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="phone">Phone Number</label>
                                <input type="text" name="phone" id="phone" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control" minlength="8" required>
                                <small class="text-muted">Password must be at least 8 characters long</small>
                            </div>
                            <!-- <div class="form-group mb-3">
                                <label for="c_password">Confirm Password</label>
                                <input type="password" name="c_password" id="c_password" class="form-control" required>
                            </div> -->
                            <div class="form-group mb-3" id="register">
                                <button type="submit" name="register_btn" class="btn btn-primary">Register</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
