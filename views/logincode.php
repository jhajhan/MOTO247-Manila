<?php
session_start();
include('F:\xampp\htdocs\MOTO247-Manila\config\db.php');


if (isset($_POST['login_now_btn'])) {
    // Ensure email and password fields are not empty
    if (!empty(trim($_POST['email'])) && !empty(trim($_POST['password']))) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        // Query to check user credentials
        $login_query = "SELECT * FROM user WHERE email = '$email' AND password = '$password' LIMIT 1";
        $login_query_run = mysqli_query($conn, $login_query);

        if (mysqli_num_rows($login_query_run) > 0) {
            $row = mysqli_fetch_array($login_query_run);

            // Check if email is verified
            if ($row['verify_status'] == 1) {
                // Retrieve the role from the database
                $role = $row['role']; // Assuming the role column exists in your user table

                // Set session variables for the authenticated user
                $_SESSION['authenticated'] = true;
                $_SESSION['auth_user'] = [
                    'username' => $row['full_name'],
                    'contact' => $row['contact_no'],
                    'email' => $row['email']
                ];

                $_SESSION['role'] = $role; // Assign the role to the session

                // Redirect based on role
                if ($role === 'admin') {
                    header('Location: admin/admin_dashboard.php');
                    exit();
                } else {
                    // Redirect regular users
                    $_SESSION['status'] = "Login Successful";
                    header('Location: client/dashboard.php');
                    exit();
                }
            } else {
                // Email not verified
                $_SESSION['status'] = "Email not verified. Please verify your email first.";
                header('Location: login.php');
                exit();
            }
        } else {
            // Invalid email or password
            $_SESSION['status'] = "Email or Password is incorrect.";
            header('Location: login.php');
            exit();
        }
    } else {
        // Fields are incomplete
        $_SESSION['status'] = "Please complete all fields.";
        header('Location: login.php');
        exit();
    }
}
?>
