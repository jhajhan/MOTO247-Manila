<?php 
    require_once('../../config/db.php');

   // may validation dapat dine if naka-login si admin or not
   
   function getTotalSales() {
        global $conn;
        $query = "SELECT SUM(total_amount) as total_sales FROM orders"; // dapat may date range like monthly?
        $result = mysqli_query($conn, $query);
        $total = mysqli_num_rows($result);
        return $total;      
   }

   function getTotalOrders() {
        global $conn;
        $query = "SELECT Status, COUNT(*) as NumberOfOrders FROM orders GROUP BY Status";
        $result = mysqli_query($conn, $query);
        $total = mysqli_num_rows($result);
        return $total;        
   }

   function getTotalProducts() {
        global $conn;
        $query = "SELECT Type, COUNT(*) as NumberOfProducts FROM products GROUP BY Type";
        $result = mysqli_query($conn, $query);
        $total = mysqli_num_rows($result);
        return $total;
   }

   function salesTrend (){ //line graph
        global $conn;
        $query = "SELECT DATE_FORMAT(date_ordered, '%Y-%m') as date_ordered, SUM(total_amount) as total_sales FROM orders GROUP BY DATE_FORMAT(date_ordered, '%Y-%m')";
        $result = mysqli_query($conn, $query);
        $total = mysqli_num_rows($result);
        return $total;
   }

   function paymentMethod() { //pie chart
        global $conn;
        $query = "SELECT payment_method, COUNT(*) as NumberOfOrders FROM orders GROUP BY payment_method";
        $result = mysqli_query($conn, $query);
        $total = mysqli_num_rows($result);
        return $total;
   }





?>