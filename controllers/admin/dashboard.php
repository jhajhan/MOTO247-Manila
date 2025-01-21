<?php 
    require_once('../../config/db.php');

   // may validation dapat dine if naka-login si admin or not
//    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
//      // If the user is not logged in as an admin, redirect to the login page
//      header('Location: ../../login.php');
//      exit();
//      // dapat ma reredirect si customer sa customer dashboard tas admin sa admin dashboard
//  }
   
   function getTotalSales() {
        global $conn;
        $query = "SELECT SUM(total_amount) as total_sales FROM orders"; 
        $result = mysqli_query($conn, $query);
        $total = mysqli_fetch_assoc($result); //associative array 
        return $total['total_sales'];      
   }

   function getTotalOrders() {
        global $conn;
        $query = "SELECT Status, COUNT(*) as NumberOfOrders FROM orders GROUP BY status";
        $result = mysqli_query($conn, $query);
        $total = mysqli_fetch_assoc($result); //associative array 
        return $total;     
   }

   function getTotalProducts() {
        global $conn;
        $query = "SELECT Type, COUNT(*) as NumberOfProducts FROM products GROUP BY type";
        $result = mysqli_query($conn, $query);
        $total = mysqli_fetch_assoc($result);
        return $total;
   }

   function salesTrend (){ //line graph
        global $conn;
        $query = "SELECT DATE_FORMAT(date_ordered, '%Y-%m') as date_ordered, SUM(total_amount) as total_sales FROM orders GROUP BY DATE_FORMAT(date_ordered, '%Y-%m')";
        $result = mysqli_query($conn, $query);
        $sales = [];
         
        while ($row = mysqli_fetch_assoc($result)) {
          $sales[] = $row;
        }
   }

   function paymentMethod() { //pie chart
        global $conn;
        $query = "SELECT payment_method, COUNT(*) as NumberOfOrders FROM orders GROUP BY payment_method";
        $result = mysqli_query($conn, $query);
        $payment = [];

        while ($row = mysqli_fetch_assoc($result)) {
          $payment[] = $row;
        }
        return $payment;
   }

   function getRecentOrders() {
        global $conn;
        $query = "SELECT * FROM orders ORDER BY date_ordered DESC LIMIT 5";
        $result = mysqli_query($conn, $query);
        $orders = [];

        while ($row = mysqli_fetch_assoc($result)) {
          $orders[] = $row;
        }
        return $orders;
   }







?>