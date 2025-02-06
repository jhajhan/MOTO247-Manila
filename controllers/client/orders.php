<?php

require_once(__DIR__ . '/../../config/db.php');

class Orders {
    
    function getOrders($sessionManager) {

        $user_id = $sessionManager->get('user_id');

        global $conn;

        $query = 'SELECT o.order_id, o.user_id, c.full_name AS customer_name, c.phone_number, o.date_ordered AS date, 
                             o.payment_method, o.payment_status, o.status, o.total_amount, c.address, o.delivery_option,
                             p.name AS product_name, oi.quantity AS quantity
                      FROM `order` o 
                      JOIN user c ON o.user_id = c.user_id
                      JOIN order_item oi ON oi.order_id = o.order_id
                      JOIN product p ON p.prod_id = oi.prod_id
                      WHERE o.user_id = ?';

        $stmt = mysqli_prepare($conn, $query);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $orders = [];
        
        while ($row = mysqli_fetch_assoc($result)) {
            $order_id = $row['order_id'];
        
            // If the order_id doesn't exist in the array, create a new entry
            if (!isset($orders[$order_id])) {
                $orders[$order_id] = [
                    'order_id' => $row['order_id'],
                    'user_id' => $row['user_id'],
                    'customer_name' => $row['customer_name'],
                    'phone_number' => $row['phone_number'],
                    'address' => $row['address'],
                    'delivery_option' => $row['delivery_option'],
                    'date' => $row['date'],
                    'payment_method' => $row['payment_method'],
                    'payment_status' => $row['payment_status'],
                    'status' => $row['status'],
                    'total' => $row['total_amount'],
                    'products' => [] // Initialize the products array
                ];
            }
        
            // Add product details to the products array
            $orders[$order_id]['products'][] = [
                'product_name' => $row['product_name'],
                'quantity' => $row['quantity']
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($orders);

    }
}

?>
