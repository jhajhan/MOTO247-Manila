<?php
    require_once('../../config/db.php');

    class Sales {

        function index() {

            $payment_method = isset($_GET['payment_method']) && ($_GET['payment_method']) != 'All' ? $_GET['payment_method'] : '';
            $payment_status = isset($_GET['payment_status']) && ($_GET['payment_status']) != 'All' ? $_GET['payment_status'] : '';
            $status = isset($_GET['status']) && ($_GET['status']) != 'All' ? $_GET['status'] : '';


            $physical_sales = $this -> getPhysicalSales($payment_method, $payment_status, $status);

            $response = [
                'physical_sales' => $physical_sales
            ];

            header('Content-Type: application/json');
            echo json_encode($response);
        }
        
        function getPhysicalSales ($payment_method, $payment_status, $status) {
            global $conn;
            $query = "SELECT o.order_id, c.name AS customer_name, o.date_ordered AS date, o.payment_method, o.payment_status, o.status, o.total_amount, p.name AS product_name, oi.quantity AS quantity  -- Quantity from the order_items table
                    FROM orders o 
                    JOIN customers c ON o.customer_id = c.customer_id
                    JOIN order_items oi ON oi.order_id = o.order_id
                    JOIN products p ON p.product_id = oi.product_id";

                    if ($payment_method != '') {
                        $query .= " WHERE o.payment_method = ?";
                    }

                    if ($payment_status != '') {
                        if ($payment_method != '') {
                            $query .= " AND o.payment_status = ?";
                        } else {
                            $query .= " WHERE o.payment_status = ?";
                        }
                    }

                    if ($status != '') {
                        if ($payment_method != '' || $payment_status != '') {
                            $query .= " AND o.status = ?";
                        } else {
                            $query .= " WHERE o.status = ?";
                        }
                    }

                    $params = [];
                    $types ='';

                    if ($payment_method != '') {
                        $params[] = $payment_method;
                        $types .= 's';
                    }

                    if ($payment_status != '') {
                        $params[] = $payment_status;
                        $types .= 's';
                    }

                    if ($status != '') {
                        $params[] = $status;
                        $types .= 's';
                    }

                   
            if ($payment_method != '' || $payment_status != '' || $status != '') {
                        $query .= "WHERE o.order_type = 'physical'
                        ORDER BY o.date_ordered, oi.order_item_id";
                    } 

                    $stmt = $conn->prepare($query);
                    
                    if ($types != '') {
                        $stmt->bind_param($types, ...$params);
                    }

                    $stmt->execute();
                    $result = $stmt->get_result();

                    $physical_sales = [];


                    while ($row = mysqli_fetch_assoc($result)) {
                        $physical_sales[] = $row;
                    }
                    return $physical_sales;
            }

            
        function getOnlineSales () { // apply filter din dine
            global $conn;
            $query = "SELECT o.order_id, c.name AS customer_name, o.date_ordered AS date, o.payment_method, o.payment_status, o.status, o.total_amount, p.name AS product_name, oi.quantity AS quantity  -- Quantity from the order_items table
                    FROM orders o 
                    JOIN customers c ON o.customer_id = c.customer_id
                    JOIN order_items oi ON oi.order_id = o.order_id
                    JOIN products p ON p.product_id = oi.product_id
                    WHERE o.order_type = 'online'
                    ORDER BY o.date_ordered, oi.order_item_id;";
                    
                    $result = mysqli_query($conn, $query);
                    $online_sales = [];
                    while ($row = mysqli_fetch_assoc($result)) {
                        $online_sales[] = $row;
                    }
                    return $online_sales;
        }
    
        function getOnlineOrdersByStatus () {
            global $conn;
            $status = '';
            $query = "SELECT Status, COUNT(*) as NumberOfOrders FROM orders WHERE order_type = 'online' AND status = ?";
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

            $query = "UPDATE orders SET payment_method = ?, payment_status = ?, status = ? WHERE order_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('si', $status, $order_id);
            $stmt->execute();
        }
    
        function addSale ($data) {
            global $conn;

            $customer_name = $data['customer_name'];
            $products = $data['products'];
            $date = $data['date'];
            $payment_method = $data['payment_method'];
            $payment_status = $data['payment_status'];
            $status = $data['status'];
            $total_amount = $data['total_amount'];

            $customer_query = "INSERT INTO customers (name) VALUES (?)";
            $stmt = $conn->prepare($customer_query);
            $stmt->bind_param('s', $customer_name);
            $stmt->execute();
            $customer_id = $stmt->insert_id;

            $order_query = "INSERT INTO orders (customer_id, date, payment_method, payment_status, status, total_amount) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($order_query);
            $stmt->bind_param('isssi', $customer_id, $date, $payment_method, $payment_status, $status, $total_amount);
            $stmt->execute();

            $order_id = $stmt->insert_id;

            foreach ($products as $product) {
                $product_id = $product['product_id'];
                $quantity = $product['quantity'];
                $price = $product['price'];

                $order_item_query = "INSERT INTO order_item (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($order_item_query);
                $stmt->bind_param('iiid', $order_id, $product_id, $quantity, $price);
                $stmt->execute();
            }
            
        }
    
    
        function getTopProducts () {
            global $conn;
            $query = "SELECT prod_id, name, COUNT(*) as numOfOrders FROM orders WHERE payment_status = 'paid GROUP BY prod_id ORDER BY COUNT(*) DESC LIMIT 5";
            $result = mysqli_query($conn, $query);
            $top_products = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $top_products[] = $row;
            }
            return $top_products;
    
        }
    }

    
    
?>