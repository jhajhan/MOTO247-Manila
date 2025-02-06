<?php

require_once(__DIR__ . '/../../config/db.php');

class Checkout {
    function checkout($sessionManager, $data) {
        global $conn;

        // Get JSON data from the POST request
        if (!$data) {
            echo json_encode(['error' => 'Invalid JSON data']);
            return;
        }

        // Check if all required fields are present
        if (empty($data['cart']) || empty($data['total_amount']) || empty($data['payment_method'])) {
            echo json_encode(['error' => 'Missing required fields (cart, total_amount, payment_method)']);
            return;
        }

        $user_id = $sessionManager->get('user_id');
        if (!$user_id) {
            echo json_encode(['error' => 'User not authenticated']);
            return;
        }

        $cart = $data['cart'];
        $total_amount = $data['total_amount'];
        $payment_method = $data['payment_method'];
        $date = date('Y-m-d');
        $payment_status = 'pending';
        $status = 'pending';
        $type = 'Online';

        // Insert the order
        $order_query = "INSERT INTO `order` (user_id, date_ordered, payment_method, payment_status, status, type, total_amount) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($order_query);
        if ($stmt === false) {
            echo json_encode(['error' => 'Error preparing order query']);
            return;
        }

        $stmt->bind_param('isssssi', $user_id, $date, $payment_method, $payment_status, $status, $type, $total_amount);
        if (!$stmt->execute()) {
            echo json_encode(['error' => 'Error inserting order: ' . $stmt->error]);
            return;
        }

        $order_id = $stmt->insert_id;

        // Insert order items
        foreach ($cart as $product) {
            $product_id = $product['prod_id'];
            $product_qty = $product['prod_qty'];
            $price = $product['price'];
            $total_per_product = $product_qty * $price;

            $order_item_query = "INSERT INTO order_item (order_id, prod_id, quantity, total_per_product) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($order_item_query);
            if ($stmt === false) {
                echo json_encode(['error' => 'Error preparing order item query']);
                return;
            }

            $stmt->bind_param('iiid', $order_id, $product_id, $product_qty, $total_per_product);
            if (!$stmt->execute()) {
                echo json_encode(['error' => 'Error inserting order item: ' . $stmt->error]);
                return;
            }
        }

        // Response to the client
        echo json_encode(['message' => 'Order placed successfully', 'order_id' => $order_id]);
    }
}
