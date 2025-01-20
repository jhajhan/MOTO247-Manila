<?php
session_start();
include('dbcon.php');
if(isset($_GET['token']))
{
    $token = $_GET['token'];
    $verify_query ="SELECT verify_token, verify_status FROM user WHERE verify_token = '$token' LIMIT 1";
    $verify_query_run = mysqli_query($con, $verify_query);

    if(mysqli_num_rows($verify_query_run) > 0)
    {
        $row = mysqli_fetch_array($verify_query_run);
        //echo $row['verify_token'];
        if($row['verify_status']=="0"){

            $clicked_token = $row['verify_token'];
            $update_query = "UPDATE user SET verify_status = '1' WHERE verify_token = '$clicked_token' LIMIT 1";
            $update_query_run = mysqli_query($con, $update_query);

            if($update_query_run){
                $_SESSION['status'] = "Your email has been verified";
                header('Location: login.php');
                exit(0);
            }
            else{
                $_SESSION['status'] = "Email verification failed";
                header('Location: login.php');
                exit(0);
            }

        }
        else{
            $_SESSION['status'] = "Your email has already been verified";
            header('Location: login.php');
            exit(0);
        }

    }
    else{
        $_SESSION['status'] = "Token not found";
        header('Location: login.php');
    }

}
else{
    
    $_SESSION['status'] = "Not  Allowed to access this page";
    header("Location: login.php");
}


?>