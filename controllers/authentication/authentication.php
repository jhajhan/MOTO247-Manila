<?php

    require_once __DIR__ . '/../../config/db.php';
    require_once __DIR__ . '/../../session/session_manager.php';
    require_once __DIR__ . '/../../session/auth_session.php';


    class Authentication {
        function register($data) {
            global $conn;


            $email = htmlspecialchars($data['email']);
            $username = htmlspecialchars($data['username']);
            $password = password_hash($data['password'], PASSWORD_BCRYPT);
            $full_name = htmlspecialchars($data['full_name']);
            $role = htmlspecialchars($data['role']) ?? 'user';
            $created_at = date('Y-m-d H:i:s');
            $token = bin2hex(random_bytes(50));

    

            $query = "INSERT INTO user (email, username, password, full_name, role, created_at, token) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sssssss', $email, $username, $password, $full_name, $role, $created_at, $token);
            
            if ($stmt->execute()) {
             //   $this->sendVerificationEmail($email, $token);
                echo json_encode(['message' => 'User registered successfully']);
            } else {
                echo json_encode(['message' => 'Failed to register user']);
            }


            
        }

        function sendVerificationEmail ($email, $token) {
            // using smtp
            



        }

        function login ($data) {

            global $conn;

            $email = htmlspecialchars($data['email']);
            $password = $data['password'];

            $query = "SELECT * FROM user WHERE email = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

        
            if ($user) {
                if ($user['verified'] == 1 && password_verify($password, $user['password'])) {
                    $session = new SessionManager();
                    $session->start();
                    $session->set('user_id', $user['id']);
                    $session->set('user_type', $user['role']);
                    echo json_encode(['message' => 'User logged in successfully']);

                } else {
                    echo json_encode(['message' => 'Invalid email or password']);
                }
            }
        }

        function verifyEmail ($token) {

            global $conn;

            $query = "SELECT id FROM users WHERE verification_token = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Update the user's verification status
                $updateQuery = "UPDATE users SET is_verified = 1, verification_token = NULL WHERE verification_token = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param("s", $token);
                $updateStmt->execute();

                echo "Email verified! You can now log in.";
            } else {
                echo "Invalid or expired token.";
            }

        }

        function logout() {
            $auth = new AuthSession();
            $auth->logout();
            echo json_encode(['message' => 'User logged out successfully']);

        }
    }

?>