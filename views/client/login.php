<?php
session_start();
if(isset($_SESSION['authenticated'])){

    $_SESSION['status'] = "You are already logged in";
    header("Location: dashboard.php");
    exit(0);
}


include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
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
                        <h5>Login</h5>
                    </div>
                    <div class="card-body">
                        <form action="logincode.php" method="POST">
                            <div class="form-group mb-3">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control" minlength="8" required>
                                
                            </div>
                            <div class="form-group mb-3">
                                <button type="submit" name="login_now_btn" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
