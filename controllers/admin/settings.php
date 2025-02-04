<?php
    require_once(__DIR__ . '/../../config/db.php');

    class Settings {

        function index() {
            $store_info = $this->getStoreInfo();
            $admins = $this->getAdmins();

            $response = [
                'store_info' => $store_info,
                'admins' => $admins

            ];

            header('Content-Type: application/json');
            echo json_encode($response);
        }

        function updateProfileDetails ($data) {
            $name = $data['name'];
            $email = $data['email'];
            $old_password = $data['old_password'];
            $new_password = $data['new_password'];
            $confirm_password = $data['confirm_password'];
            

            $id = $_SESSION['user_id'];

            global $conn;
            $query = 'UPDATE user SET name = ?, email = ?, password = ? WHERE id = ?';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sssi', $name, $email, $password, $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo 'success';
            } else {
                echo 'failed';
            }

        }

        function getStoreInfo() {
            global $conn;

            $query = 'SELECT * FROM store_info';
            $result = mysqli_query($conn, $query);

            return mysqli_fetch_assoc($result);

        }

        function getAdmins() {
            global $conn;

            $query = 'SELECT * FROM user WHERE role = "admin"';
            $result = mysqli_query($conn, $query);

            $admins = [];

            while($row = mysqli_fetch_assoc($result)) {
                $admins[] = $row;
            }

            return $admins;
        }
        function addAdmin($data) {
            $name = $data['name'];
            $username = $data['username'];
            $email = $data['email'];
            $password = password_hash($data['password'], PASSWORD_BCRYPT);
            $role = 'admin';
            $verified = 1;
            $created_at = date('Y-m-d');

            global $conn;

            $query = 'INSERT INTO user(full_name, username, email, password, role, verified, created_at) VALUES(?, ?, ?, ?, ?, ?, ?)';
            $stmt = mysqli_prepare($conn, $query);
            $stmt->bind_param('sssssis', $name, $username, $email, $password, $role, $verified, $created_at);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['success' => 'Admin added!']);
            }


        }

        function removeAdmin($data) {
            $id = $data['id'];

            global $conn;
            $query = 'DELETE FROM user WHERE user_id = ?';
            $stmt = mysqli_prepare($conn, $query);
            $stmt->bind_param('i', $id);

            header('Content-Type: application/json');

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo json_encode(['message' => 'Admin removed successfully!']);
                } else {
                    echo json_encode(['message' => 'No admin was removed.']);
                }
            } else {
                echo json_encode(['message' => 'SQL Error: ' . $stmt->error]);
            }
        }

        function updateStoreDetails ($data) {
            $store_name = $data['business_name'];
            $store_address = $data['business_address'];
            $store_contact = $data['business_contact'];
            $business_hours = $data['business_hours'];
          //  $logo = $data['logo'];
            $store_id = 1;

            global $conn;

            $query = 'UPDATE store_info SET store_name = ?, store_address = ?, store_contact = ?, business_hours = ? WHERE store_id = ?';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ssssi', $store_name, $store_address, $store_contact, $business_hours, $store_id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo 'success';
            } else {
                echo 'failed';
            }
            
        }

        function updatePaymentDetails($data) {
            global $conn;
        
            $account_name = $data['gcash_account'];
            $account_number = $data['gcash_no'];
            $qr = $data['gcash_qr'];
        
            // Fix: Add missing comma in SQL query
            $query = 'UPDATE store_info SET account_name = ?, account_no = ?, account_qr = ? WHERE store_id = 1';
        
            // Prepare statement
            $stmt = $conn->prepare($query);
            if (!$stmt) {
                return 'Error: ' . $conn->error;  // Return SQL error
            }
        
            // Bind parameters
            $stmt->bind_param('sss', $account_name, $account_number, $qr);
        
            // Execute query
            if ($stmt->execute()) {
                return ($stmt->affected_rows > 0) ? 'success' : 'no changes made';
            } else {
                return 'failed: ' . $stmt->error;  // Return error message
            }
        }
        
    }
    
    
?>