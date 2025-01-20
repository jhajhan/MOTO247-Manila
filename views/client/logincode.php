<?php
session_start();
include('dbcon.php');


if (isset($_POST['login_now_btn'])) {
        //check kung may laman yung email at password
    if(!empty(trim($_POST['email'])) && !empty(trim($_POST['password']))){
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $password = mysqli_real_escape_string($con, $_POST['password']);
        
        $login_query = "SELECT * FROM user WHERE email = '$email' AND password='$password' LIMIT 1";
        $login_query_run = mysqli_query($con, $login_query);

        //checks nito kung tama ba yung email at password
        if(mysqli_num_rows($login_query_run)> 0)
        {
            $row =mysqli_fetch_array($login_query_run);
            // echo $row['verify_status']; // testing purposes

            if ($row['verify_status'] == 1) {
                $_SESSION['authenticated'] = TRUE;
                $_SESSION['auth_user'] = [
                    'username' => $row['name'],
                    // 'phone' => $row['phone'],
                    'email' => $row['email']//
                ];
                $_SESSION['status'] = "Login Successful";
                header('Location: dashboard.php');
                exit();
            }
            
            else{
                $_SESSION['status'] = "Email not verified, first verify your email";
                header('Location: login.php');
            }
        }
        else{
            $_SESSION['status'] = "Email or Password is incorrect";
            header('Location: login.php');
        }
    }
    else{

        $_SESSION['status'] = "Complete all fields";
        header('Location: login.php');
    }
    
    
    
        
}

?>