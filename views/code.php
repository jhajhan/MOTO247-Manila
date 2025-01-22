<?php
session_start();
include('F:\xampp\htdocs\MOTO247-Manila\config\db.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Function to send email verification
function sendmail_verify($username, $email, $verify_token)
{

    require __DIR__ . '/../vendor/autoload.php';


    $mail = new PHPMailer(true);
    try {
        // // Server settings
         $mail->isSMTP();
        // $mail->Host = getenv('SMTP_HOST');  // SMTP server from .env
        // $mail->SMTPAuth = true;  // Enable SMTP authentication
        // $mail->Username = getenv('SMTP_USERNAME');  // SMTP username from .env
        // $mail->Password = getenv('SMTP_PASSWORD');  // SMTP password from .env
        // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // StartTLS encryption
        // $mail->Port = getenv('SMTP_PORT');  // TCP port to connect to; use 587 for STARTTLS

        $mail->SMTPAuth   = true;
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;  
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through                                   
        $mail->Username   = 'rjhoncarlos30@gmail.com';                     //SMTP username
        $mail->Password   = 'wedr geug bmcz qrie';     
        //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port       = 587;   

        // Recipients
        $mail->setFrom('rjhoncarlos30@gmail.com', $username);
        $mail->addAddress($email);  // Add a recipient
        // $mail->addReplyTo('rjhoncarlos30@gmail.com', 'Information');

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Email Verification';
        $email_template = "
        <h2>Thank you for registering</h2>
        <p>Click the link below to verify your email</p>
        <br/><br/>
        <a href='http://localhost/PHP_COURSE/verify-email.php?token=$verify_token'>Verify Email</a>";
        $mail->Body = $email_template;

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Load Composer's autoloader
require __DIR__ . '/../vendor/autoload.php';



// Check if the form is submitted
if (isset($_POST['register_btn'])) {
    $username = $_POST['username'];
    $full_name = $_POST['full_name'];
    $contact_no = $_POST['contact_no'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $address = $_POST['address'];
    $verify_token = md5(rand()); // Generate a verification token
    // $confirm_password = $_POST['confirm_password']; // Get the confirm password

    // // Validate password and confirm password
    // if ($password !== $confirm_password) {
    //     $_SESSION['status'] = "Passwords do not match";
    //     header('Location: register.php');
    //     exit();
    // }

    // Check password strength (optional)
    if (strlen($password) < 8) {
        $_SESSION['status'] = "Password must be at least 8 characters long.";
        header('Location: register.php');
        exit();
    }

    //debugger to check if email is sent
    // sendmail_verify($full_name, $email, $verify_token);
    // echo "Email sent successfully";

    // Hash the password
    // $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if the email or username already exists in the database
        $check_email_query = "SELECT email, username FROM user WHERE email = '$email' OR username = '$username' LIMIT 1";
        $check_existing = mysqli_query($conn, $check_email_query);

if (mysqli_num_rows($check_existing) > 0) {
    // Fetch the result to check which one exists
    $existing_user = mysqli_fetch_assoc($check_existing);

    if ($existing_user['email'] == $email) {
        $_SESSION['status'] = "Email already exists.";
    } elseif ($existing_user['username'] == $username) {
        $_SESSION['status'] = "Username already exists.";
    }
    header('Location: register.php');
    exit();
} else {
    // Insert user data into the database
    $query = "INSERT INTO user (username, password, full_name, contact_no, email, address, verify_token) 
              VALUES ('$username', '$password', '$full_name', '$contact_no', '$email', '$address', '$verify_token')";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        sendmail_verify($full_name, $email, $verify_token);
        $_SESSION['status'] = "Registration successful. Please verify your email.";
        header('Location: register.php');
    } else {
        $_SESSION['status'] = "Registration failed. Please try again.";
        header('Location: register.php');
    }
}




    
 }
?>
