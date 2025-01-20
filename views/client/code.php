<?php
session_start();
include('dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
 


function sendmail_verify($name, $email, $verify_token)
    {   
        $mail = new PHPMailer(true);
            try {
                //Server settings
       // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();    
        
        //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'rjhoncarlos30@gmail.com';                     //SMTP username
        $mail->Password   = 'wedr geug bmcz qrie';     
        //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('rjhoncarlos30@gmail.com', $name);
        $mail->addAddress($email);  //Add a recipient
        $mail->addReplyTo('rjhoncarlos30@gmail.com', 'Information');
       /* $mail->addAddress('ellen@example.com');               //Name is optional
        $mail->addCC('cc@example.com');
        $mail->addBCC('bcc@example.com');

        //Attachments
        $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
        */

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Email Verification';
       
       /* $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        */

        $email_template ="
        <h2>Thank you for registering</h2>
        <p>Click the link below to verify your email</p>
        <br/><br/>
        <a href='http://localhost/PHP_COURSE/verify-email.php?token=$verify_token'>Verify Email</a>";
        $mail->Body = $email_template;
        $mail->send();
        //echo 'Message has been sent';


    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

        

//Load Composer's autoloader
require 'vendor/autoload.php';

    if(isset($_POST['register_btn'])){
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $verify_token = md5(rand());
        $confirm_password = $_POST['confirm_password']; // Get the confirm password
        // para mapost sya sa database

      // Pa help ako sa pag lagay ng validation ng pag confirm  password

    // Check password strength
    $password_error = "";
    if (strlen($password) < 8) {
        $password_error = "Password must be at least 8 characters long.";
    }
        // } elseif (!preg_match("/[A-Z]/", $password)) {
    //     $password_error = "Password must contain at least one uppercase letter.";
    // } elseif (!preg_match("/[a-z]/", $password)) {
    //     $password_error = "Password must contain at least one lowercase letter.";
    // } elseif (!preg_match("/[0-9]/", $password)) {
    //     $password_error = "Password must contain at least one number.";
    // }

    if ($password_error != "") {
        $_SESSION['status'] = $password_error;
        header('Location: register.php');
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $check_email_query = "SELECT email FROM user WHERE email = '$email' LIMIT 1";
    $check_email = mysqli_query($con, $check_email_query);



        // //To check kung nagamit na ung email
        $check_email_query =  "SELECT email FROM user WHERE email = '$email' LIMIT 1";
        $check_email = mysqli_query($con, $check_email_query);
   
        if(mysqli_num_rows($check_email) > 0)
        {
            $_SESSION['status'] = "Email already exists";
            header('Location: register.php');
        }   
        else{
        // insert user data into database
        $query = "INSERT INTO user (name, phone, email, password, verify_token) VALUES ('$name', '$phone', '$email', '$password' , '$verify_token')";
        $query_run = mysqli_query($con, $query);    

         if($query_run){
            sendmail_verify("$name","$email", "$verify_token");
            $_SESSION['status'] = "Registeration Successful";
            header('Location: register.php');
        }
        else{
            $_SESSION['status'] = "Registeration Failed";
            header('Location: register.php');
        }
   
   
   
   
        }
    }
?>