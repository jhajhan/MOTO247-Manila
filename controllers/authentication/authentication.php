<?php

    require_once __DIR__ . '/../../config/db.php';
    require_once __DIR__ . '/../../session/session_manager.php';
    require_once __DIR__ . '/../../session/auth_session.php';
    require __DIR__ . '/../../vendor/autoload.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    use Dotenv\Dotenv;

    $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
    $dotenv->load();




    class Authentication {
        function register($data) {
            global $conn; // db connection


            $email = htmlspecialchars($data['email']);
            $username = htmlspecialchars($data['username']);
            $password = password_hash($data['password'], PASSWORD_BCRYPT);
            $full_name = htmlspecialchars($data['full_name']) ?? null;
            $role = htmlspecialchars($data['role']) ?? 'user';
            $created_at = date('Y-m-d H:i:s');
            $token = bin2hex(random_bytes(50));

    

            $query = "INSERT INTO user (email, username, password, full_name, role, created_at, token) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sssssss', $email, $username, $password, $full_name, $role, $created_at, $token);
            
            if ($stmt->execute()) {
              $this->sendVerificationEmail($email, $token);
                echo json_encode(['message' => 'User registered successfully']);
            } else {
                echo json_encode(['message' => 'Failed to register user']);
            }


            
        }

        function sendVerificationEmail ($email, $token) {
            // using smtp
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = $_ENV['EMAIL_USER'];
                $mail->Password = $_ENV['EMAIL_PASS'];
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                //Recipients
                $mail->setFrom('moto247manila@gmail.com');
                $mail->addAddress($email);
                

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Account Verification';
                $mail->Body = "Click <a href='http://localhost:8000/verify-email?token=$token'>here</a> to verify your account.";

                $mail->send();
                echo 'Verification email sent!';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }

        }

        function login($data) {
            ob_start(); // Start output buffering
        
            global $conn;
            $email = htmlspecialchars($data['email']);
            $password = $data['password'];
        
            $query = "SELECT * FROM user WHERE email = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
        
            header('Content-Type: application/json');

            $response = [];

            if ($user) {
                if ($user['verified'] == 1 && password_verify($password, $user['password'])) {
                    $session = new SessionManager();
                    $session->start();
                    $session->set('user_id', $user['user_id']);
                    $session->set('user_type', $user['role']);

                    $response['status'] = 'success';
                    $response['redirect'] = ($user['role'] === 'admin') ? '/admin' : '/';
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Invalid email or password';
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = 'User not found';
            }

            echo json_encode($response);
            exit();

            
        }
        

        function verifyEmail ($token) {

            echo 'Yow';

            global $conn;

            $query = "SELECT user_id FROM user WHERE token = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $token);
            echo $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {

                // Update the user's verification status
                $updateQuery = "UPDATE user SET verified = 1, token = NULL WHERE token = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param("s", $token);
                $updateStmt->execute();

                echo "Email verified! You can now log in.";
                header('/login');
            } else {
                echo "Invalid or expired token.";
            }

        }

        function logout() {
            try {
                // Initialize session handling
                $auth = new AuthSession();
                
                // Check if the user is authenticated before attempting to logout
                if (!$auth->isLogged()) {
                    // If the user is not logged in, return an error response
                    header('Content-Type: application/json');
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'User is not logged in.'
                    ]);
                    exit();
                }
        
                // Perform the logout operation
                $auth->logout();
        
                // Ensure headers are set before output
                header('Content-Type: application/json');
                
                // Return JSON response with success status and redirect URL
                echo json_encode([
                    'status' => 'success',
                    'redirect' => '/'
                ]);
                exit();
        
            } catch (Exception $e) {
                // If any exception occurs, catch it and send an error response
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'An error occurred during logout: ' . $e->getMessage()
                ]);
                exit();
            }
        }
        
    }

?>