<?php
    require_once('../../config/db.php');

    class Sales {

        function index() {

        }
        
        function getOnlineOrders() {
            global $conn;
            $query = "SELECT * FROM orders WHERE order_type = 'online'";
            $result = mysqli_query($conn, $query);
            $online_orders = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $online_orders[] = $row;
            }
            return $online_orders;
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
    
        function updateOrderStatus () {
            global $conn;
            $status = '';
            $query = 'UPDATE orders SET status = ? WHERE id = ?';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('si', $status, $id);
            $stmt->execute();
        }
    
        function getPaymentMethod () {
            global $conn;
            $query = "SELECT payment_method, COUNT(*) as NumberOfOrders FROM orders WHERE order_type = 'online' GROUP BY payment_method";
            $result = mysqli_query($conn, $query);
            $payment_method = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $payment_method[] = $row;
            }
            return $payment_method;
        }
    
        function addPhysicalSale () {
            global $conn;
            $query = "INSERT INTO orders (order_type, status, payment_method, total_amount, date_ordered) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ssds', $order_type, $status, $payment_method, $total_amount, $date_ordered);
            $stmt->execute();
        }
    
        function getPhysicalSales() {
            global $conn;
            $query = "SELECT * FROM orders WHERE order_type = 'physical'";
            $result = mysqli_query($conn, $query);
            $physical_sales = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $physical_sales[] = $row;
            }
            return $physical_sales;
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