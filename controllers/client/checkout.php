<?php

class Checkout {
    
    function checkout($data, $sessionManager) {

        $user_id = $sessionManager->get('user_id');
        $cart = $data['cart'];
        $total_amount = $data['total_amount'];
        $payment_method = $data['payment_method'];
        $date = date('Y-m-d');
        $payment_status = 'pending';
        $status = 'pending';
        $type = 'online';

        global $conn;


        $order_query = "INSERT INTO `order` (user_id, date_ordered, payment_method, payment_status, status, type, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($order_query);
        $stmt->bind_param('isssssi', $user_id, $date, $payment_method, $payment_status, $status, $type, $total_amount);
        $stmt->execute();
        $order_id = $stmt->insert_id; // Get the ID of the newly inserted order
        
        // Step 3: Insert order items (products)
        foreach ($cart as $index => $product) {
            $product_id = $product['id'];
            $product_qty = $product['prod_qty'];

            $total_per_product = $product_qty * $product['price']; // Get total amount per product
    
            // Insert the order item with the quantity, price, and total per product
            $order_item_query = "INSERT INTO order_item (order_id, prod_id, quantity, total_per_product) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($order_item_query);
            $stmt->bind_param('iiid', $order_id, $product_id, $product_qty, $total_per_product);
            $stmt->execute();
        }
;
    }
}

?>