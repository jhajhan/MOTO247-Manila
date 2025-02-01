<?php
    require_once(__DIR__ . '/../../config/db.php');

    class Sales {

        function index() {

            $payment_method = isset($_GET['payment_method']) && ($_GET['payment_method']) != 'All' ? $_GET['payment_method'] : '';
            $payment_status = isset($_GET['payment_status']) && ($_GET['payment_status']) != 'All' ? $_GET['payment_status'] : '';
            $status = isset($_GET['status']) && ($_GET['status']) != 'All' ? $_GET['status'] : '';

            $physical_sales = '';
            $online_sales = '';
            
            if (isset($_GET['sales_type'])) {
                if ($_GET['sales_type'] == 'physical') {
                    $physical_sales = $this -> getPhysicalSales($payment_method, $payment_status, $status);
                    $online_sales = $this -> getOnlineSales();
                } else {
                    $online_sales = $this -> getOnlineSales($payment_method, $payment_status, $status);
                    $physical_sales = $this -> getPhysicalSales();
                }
            } else {
                $physical_sales = $this -> getPhysicalSales();
                $online_sales = $this -> getOnlineSales();
            }
            

            $response = [
                'physical_sales' => $physical_sales,
                'online_sales' => $online_sales
            ];

            header('Content-Type: application/json');
            echo json_encode($response);
        }
        
        function getPhysicalSales($payment_method = null, $payment_status = null, $status = null) {
            global $conn;
        
            $query = "SELECT o.order_id, o.user_id, c.full_name AS customer_name, o.date_ordered AS date, 
                             o.payment_method, o.payment_status, o.status, o.total_amount, 
                             p.name AS product_name, oi.quantity AS quantity
                      FROM `order` o 
                      JOIN user c ON o.user_id = c.user_id
                      JOIN order_item oi ON oi.order_id = o.order_id
                      JOIN product p ON p.prod_id = oi.prod_id
                      WHERE o.type = 'physical'";  // Start with the condition for physical orders
        
            // Initialize the params array and types string
            $params = [];
            $types = '';
        
            // Add conditions for payment_method, payment_status, and status
            if ($payment_method != '') {
                $query .= " AND o.payment_method = ?";
                $params[] = $payment_method;
                $types .= 's';
            }
        
            if ($payment_status != '') {
                $query .= " AND o.payment_status = ?";
                $params[] = $payment_status;
                $types .= 's';
            }
        
            if ($status != '') {
                $query .= " AND o.status = ?";
                $params[] = $status;
                $types .= 's';
            }
        
            // Add the ORDER BY clause
            $query .= " ORDER BY o.date_ordered, oi.order_item_id";
        
            // Prepare and execute the statement
            $stmt = $conn->prepare($query);
        
            if ($types != '') {
                $stmt->bind_param($types, ...$params);
            }
        
            $stmt->execute();
            $result = $stmt->get_result();
        
            // Group orders by order_id
            $physical_sales = [];
        
            while ($row = mysqli_fetch_assoc($result)) {
                $order_id = $row['order_id'];
        
                // If the order_id doesn't exist in the array, create a new entry
                if (!isset($physical_sales[$order_id])) {
                    $physical_sales[$order_id] = [
                        'order_id' => $row['order_id'],
                        'user_id' => $row['user_id'],
                        'customer_name' => $row['customer_name'],
                        'date' => $row['date'],
                        'payment_method' => $row['payment_method'],
                        'payment_status' => $row['payment_status'],
                        'status' => $row['status'],
                        'total' => $row['total_amount'],
                        'products' => [] // Initialize the products array
                    ];
                }
        
                // Add product details to the products array
                $physical_sales[$order_id]['products'][] = [
                    'product_name' => $row['product_name'],
                    'quantity' => $row['quantity']
                ];
            }
        
            return array_values($physical_sales); // Re-index array for JSON response
        }
        
            
        function getOnlineSales($payment_method = null, $payment_status = null, $status = null) {
            global $conn;
        
            $query = "SELECT o.order_id, o.user_id, c.full_name AS customer_name, o.date_ordered AS date, 
                             o.payment_method, o.payment_status, o.status, o.total_amount, 
                             p.name AS product_name, oi.quantity AS quantity
                      FROM `order` o 
                      JOIN user c ON o.user_id = c.user_id
                      JOIN order_item oi ON oi.order_id = o.order_id
                      JOIN product p ON p.prod_id = oi.prod_id
                      WHERE o.type = 'online'";  // Start with the condition for physical orders
        
            // Initialize the params array and types string
            $params = [];
            $types = '';
        
            // Add conditions for payment_method, payment_status, and status
            if ($payment_method != '') {
                $query .= " AND o.payment_method = ?";
                $params[] = $payment_method;
                $types .= 's';
            }
        
            if ($payment_status != '') {
                $query .= " AND o.payment_status = ?";
                $params[] = $payment_status;
                $types .= 's';
            }
        
            if ($status != '') {
                $query .= " AND o.status = ?";
                $params[] = $status;
                $types .= 's';
            }
        
            // Add the ORDER BY clause
            $query .= " ORDER BY o.date_ordered, oi.order_item_id";
        
            // Prepare and execute the statement
            $stmt = $conn->prepare($query);
        
            if ($types != '') {
                $stmt->bind_param($types, ...$params);
            }
        
            $stmt->execute();
            $result = $stmt->get_result();
        
            // Group orders by order_id
            $physical_sales = [];
        
            while ($row = mysqli_fetch_assoc($result)) {
                $order_id = $row['order_id'];
        
                // If the order_id doesn't exist in the array, create a new entry
                if (!isset($physical_sales[$order_id])) {
                    $physical_sales[$order_id] = [
                        'order_id' => $row['order_id'],
                        'user_id' => $row['user_id'],
                        'customer_name' => $row['customer_name'],
                        'date' => $row['date'],
                        'payment_method' => $row['payment_method'],
                        'payment_status' => $row['payment_status'],
                        'status' => $row['status'],
                        'total' => $row['total_amount'],
                        'products' => [] // Initialize the products array
                    ];
                }
        
                // Add product details to the products array
                $physical_sales[$order_id]['products'][] = [
                    'product_name' => $row['product_name'],
                    'quantity' => $row['quantity']
                ];
            }
        
            return array_values($physical_sales); // Re-index array for JSON response
        }
        
    
        function getOnlineOrdersByStatus () {
            global $conn;
            $status = '';
            $query = "SELECT Status, COUNT(*) as NumberOfOrders FROM order WHERE order_type = 'online' AND status = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $status);
            $stmt->execute();
            $result = $stmt->get_result();
            $online_orders_status = [];
            while ($row = $result->fetch_assoc()) {
                $online_orders_status[] = $row;
            }
            return $online_orders_status;
        }
        function editOrder($data) {
            global $conn;
            
            $order_id = $data['order_id'];
            $payment_method = $data['payment_method'];
            $payment_status = $data['payment_status'];
            $status = $data['status'];
        
            // Use backticks if the table name is `order`
            $query = "UPDATE `order` SET payment_method = ?, payment_status = ?, status = ? WHERE order_id = ?";
            
            if ($stmt = $conn->prepare($query)) {
                $stmt->bind_param('sssi', $payment_method, $payment_status, $status, $order_id);
                $stmt->execute();
        
                // Check for errors
                if ($stmt->affected_rows > 0) {
                    return ['success' => true, 'message' => 'Order updated successfully.'];
                } else {
                    return ['success' => false, 'message' => 'No changes made or order not found.'];
                }
        
                $stmt->close();
            } else {
                return ['success' => false, 'message' => 'Error in query: ' . $conn->error];
            }
        }

        function deleteSale($data) {
            $id = $data['id'];

            global $conn;
            $query = 'DELETE FROM `order` WHERE order_id = ?';
            $stmt = mysqli_prepare($conn, $query);
            $stmt->bind_param('i', $id);
            $stmt->execute();
        }
        
        function addSale($data) {
            global $conn;
        
            $customer_name = $data['customer_name'];
            $phone_number = $data['phone_number']; // Assuming phone_number is part of the data
            $products = $data['products'];
            $quantities = $data['quantities'];
            $date = $data['date'];
            $payment_method = $data['payment_method'];
            $payment_status = $data['payment_status'];
            $status = $data['status'];
            $total_amounts_per_product = $data['total_amounts_per_product']; // List of total amounts per product
            $total_amount = $data['total_amount']; // Total order amount
            $type = $data['order_type'];
        
            // Step 1: Check if the customer exists
            $customer_query = "SELECT user_id FROM user WHERE full_name = ? AND phone_number = ?";
            $stmt = $conn->prepare($customer_query);
            $stmt->bind_param('ss', $customer_name, $phone_number);
            $stmt->execute();
            $stmt->store_result();

            $role = 'customer';
            $created_at = date('Y-m-d');
        
            if ($stmt->num_rows === 0) {
                // Customer doesn't exist, insert a new customer
                $insert_customer_query = "INSERT INTO user (full_name, phone_number, role, created_at) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($insert_customer_query);
                $stmt->bind_param('ssss', $customer_name, $phone_number, $role, $created_at);
                $stmt->execute();
                $customer_id = $stmt->insert_id; // Get the ID of the newly inserted customer
            } else {
                // Customer exists, get their ID
                $stmt->bind_result($customer_id);
                $stmt->fetch();
            }
        
            // Step 2: Insert the order
            $order_query = "INSERT INTO `order` (user_id, date_ordered, payment_method, payment_status, status, type, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($order_query);
            $stmt->bind_param('isssssi', $customer_id, $date, $payment_method, $payment_status, $status, $type, $total_amount);
            $stmt->execute();
            $order_id = $stmt->insert_id; // Get the ID of the newly inserted order
        
            // Step 3: Insert order items (products)
            foreach ($products as $index => $product) {
                $product_id = $product;
                $quantity = $quantities[$index];
             
                $total_per_product = $total_amounts_per_product[$index]; // Get total amount per product
        
                // Insert the order item with the quantity, price, and total per product
                $order_item_query = "INSERT INTO order_item (order_id, prod_id, quantity, total_per_product) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($order_item_query);
                $stmt->bind_param('iiid', $order_id, $product_id, $quantity, $total_per_product);
                $stmt->execute();
            }
        }
        
    
        function getTopProducts () {
            global $conn;
            $query = "SELECT prod_id, name, COUNT(*) as numOfOrders FROM order WHERE payment_status = 'paid GROUP BY prod_id ORDER BY COUNT(*) DESC LIMIT 5";
            $result = mysqli_query($conn, $query);
            $top_products = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $top_products[] = $row;
            }
            return $top_products;
    
        }
    }

    
    
?>