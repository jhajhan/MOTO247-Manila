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
            $account_name = $data['gcash_account'];
            $account_number = $data['gcash_no'];
            // $qr = $data['qr'];

            global $conn;
            $query = 'UPDATE store_info SET account_name = ?, account_no = ? WHERE store_id = 1';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ss', $account_name, $account_number);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo 'success';
            } else {
                echo 'failed';
            }
        }
    }
    
    
?>