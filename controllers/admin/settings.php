<?php
    require_once('../../config/db.php');

    class Settings {

        function updateProfileDetails ($data) {
            $name = $data['name'];
            $email = $data['email'];
            $password = password_hash($data['password'], PASSWORD_DEFAULT);
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

        function updateStoreDetails ($data) {
            $store_name = $data['store_name'];
            $store_address = $data['store_address'];
            $store_contact = $data['store_contact'];
            $business_hours = $data['business_hours'];
            $logo = $data['logo'];
            $store_id = 1;

            global $conn;

            $query = 'UPDATE store SET store_name = ?, store_address = ?, store_contact = ?, business_hours = ?, logo = ? WHERE id = ?';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sssssi', $store_name, $store_address, $store_contact, $business_hours, $logo, $store_id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo 'success';
            } else {
                echo 'failed';
            }
            
        }

        function updatePaymentInfo($data) {
            $account_name = $data['acc_name'];
            $account_number = $data['acc_number'];
            $qr = $data['qr'];

            global $conn;
            $query = 'UPDATE payment_info SET account_name = ?, account_number = ?, qr = ? WHERE id = 1';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ssi', $account_name, $account_number, $qr);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo 'success';
            } else {
                echo 'failed';
            }
        }
    }
    
    
?>