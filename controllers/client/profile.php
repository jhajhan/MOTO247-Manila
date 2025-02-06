<?php
require_once(__DIR__ . '/../../config/db.php');

class Profile {

    function getProfileInfo ($sessionManager) {
        $user_id = $sessionManager->get('user_id');

        global $conn;
        $query = 'SELECT * FROM user WHERE user_id = ?';
        $stmt = mysqli_prepare($conn, $query);
        $stmt->bind_param('i', $user_id);

        $stmt->execute();
        $result = $stmt->get_result();
        $response = mysqli_fetch_assoc($result);

        header('Content-Type: application/json');
        echo json_encode($response); 
    }

   

    function editProfileInfo ($data, $sessionManager) {
        $user_id = $sessionManager->get('user_id');
        $username = $data['username'];
        $phone_number = $data['phone_no'];
        $email = $data['email'];
        $address = $data['address'];
        $img = $data['img'];

        global $conn;
        $query = 'UPDATE user SET username = ?, phone_number = ?, email = ?, address = ?  WHERE user_id = ?';
        $stmt = mysqli_prepare($conn, $query);
        $stmt->bind_param('ssssi',  $username, $phone_number, $email, $address, $user_id);
        $stmt->execute();
     

        $response = [];
        if (mysqli_affected_rows($conn) > 0) {
            $response = ['message' => 'Profile info updated'];
        } else {
            $response = ['message' => 'There was an error updating profile info.'];
        }

        header('Content-Type: application/json');
        echo json_encode($response); 

        
    }

    function editPassword($sessionManager) {
        global $conn;
    
        // Set response header for JSON
        header("Content-Type: application/json");
    
        // Decode the JSON data from the request
        $data = json_decode(file_get_contents("php://input"), true);
    
        $user_id = $sessionManager->get('user_id');
        $old_password = $data['old_password'];
        $new_password = $data['new_password'];
        $confirm_password = $data['confirm_password'];
    
        // Check if new passwords match
        if ($new_password !== $confirm_password) {
            echo json_encode(['status' => 'error', 'message' => 'New passwords do not match.']);
            return;  // Stops further execution
        }
    
        // Retrieve the current hashed password from the database
        $query = 'SELECT password FROM user WHERE user_id = ?';
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
    
        if (!$user) {
            echo json_encode(['status' => 'error', 'message' => 'User not found.']);
            return;
        }
    
        // Verify old password
        if (!password_verify($old_password, $user['password'])) {
            echo json_encode(['status' => 'error', 'message' => 'Incorrect old password.']);
            return;
        }
    
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
        // Update the password in the database
        $update_query = 'UPDATE user SET password = ? WHERE user_id = ?';
        $update_stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($update_stmt, 'si', $hashed_password, $user_id);
        mysqli_stmt_execute($update_stmt);
    
        // Check if update was successful
        if (mysqli_affected_rows($conn) > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Password updated successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No changes made.']);
        }
    }
    
}    