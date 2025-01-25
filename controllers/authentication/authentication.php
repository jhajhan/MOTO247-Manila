<?php

    require_once 'session/auth_session.php';
    require __DIR__ . '/../../config/db.php';

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

    

            $query = "INSERT INTO users (email, username, password, full_name, role, created_at, token) VALUES ('?','?','?','?','?','?','?')";
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
            



        }

        function login ($data) {

        }

        function verifyEmail ($token) {
            

        }
    }

?>