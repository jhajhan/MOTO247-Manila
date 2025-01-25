<?php
     
     require_once __DIR__ .  '/../../config/db.php';

     use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
    
    
     class Authentication {

        function register() {
            global $conn;

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $data = json_decode(file_get_contents('php://input'), true);


                $username = htmlspecialchars($data['username']);
                $full_name = htmlspecialchars($data['full_name']);
                $email = htmlspecialchars($data['email']);
                $password = password_hash($data['password'], PASSWORD_DEFAULT);
                $role = $_POST['role'] ?? 'user';
                $token = bin2hex(random_bytes(50));
                $created_at = date('Y-m-d H:i:s');

                $query = 'INSERT INTO user (username, full_name, email, password, role, token, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)';
                $stmt = $conn->prepare($query);
                $stmt->bind_param('sssssss', $username, $full_name, $email, $password, $role, $token, $created_at);
                
                if ($stmt->execute()) {
                   // $this->sendVerificationEmail($email, $token);
                    return true;
                } else {
                    return false;
                }

            }
        }

        function sendVerificationEmail($email, $token) {
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();                                      // Send using SMTP
                $mail->Host = 'smtp.gmail.com';                         // Set the SMTP server to use (Gmail SMTP server, replace if using other SMTP provider)
                $mail->SMTPAuth = true;                                // Enable SMTP authentication
                $mail->Username = 'yoopopo17@gmail.com';              // SMTP username (your Gmail address, replace if using another SMTP
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;     // Enable TLS encryption
                $mail->Port = 587;                                     // TCP port to connect to (Gmail's SMTP port)
        
                // Recipients
                $mail->setFrom('yoopopo17@gmail.com', 'Yow');    // Sender's email address
                $mail->addAddress($email);                              // Recipient's email address
        
                // Content
                $mail->isHTML(true);                                    // Set email format to HTML
                $mail->Subject = $subject = 'Account Verification';    // Email subject
                $mail->Body    = 'Click the link below to verify your account: <a href="' . $_SERVER['HTTP_HOST'] . '/verify?token=' . $token . '">Verify Account</a>';  // Email body
        
                // Send the email
                if ($mail->send()) {
                    echo 'Verification email sent!';
                } else {
                    echo 'Failed to send email.';
                }
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }

        function verifyEmail($token) {
            global $conn;

            $query = 'SELECT * FROM user WHERE token = ?';
            $stmt = $this->$conn->prepare($query);
            $stmt->bind_param('s', $token);
            $stmt->execute();

            if ($stmt->get_result()->num_rows > 0) {
                $query = 'UPDATE user SET verified = 1 WHERE token = ?';
                $stmt = $this->$conn->prepare($query);
                $stmt->bind_param('s', $token);
                $stmt->execute();
                 echo 'Account verified';
            } else {
                echo 'Invalid token';
            }


        }

        function login () {
            global $conn;

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $username = $_POST['username'];
                $password = $_POST['password'];

                $query = 'SELECT * FROM user WHERE username = ?';
                $stmt = $conn->prepare($query);
                $stmt->bind_param('s', $username);
                $stmt->execute();

                $result = $stmt->get_result();
                $user = $result->fetch_assoc();

                echo $user['password'];

                if ($user && password_verify($password, $user['password'])) {
                    if ($user['verified']) {
                        $session = new SessionManager();
                        $session->start();
                        $session->set('user_id', $user['id']);
                        $session->set('user_type', $user['user_type']);
                        
                        if ($user['user_type'] == 'admin') {
                            header('Location: /admin/dashboard');
                        } else {
                            header('Location: /');
                        }

                    } else {
                        echo 'Account not verified';
                    }
                } else {
                    echo 'Invalid email or password';
                }
            }
        }
     }
?>