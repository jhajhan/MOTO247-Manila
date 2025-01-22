<?php 
    require_once('../../config/db.php');

   // may validation dapat dine if naka-login si admin or not
//    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
//      // If the user is not logged in as an admin, redirect to the login page
//      header('Location: ../../login.php');
//      exit();
//      // dapat ma reredirect si customer sa customer dashboard tas admin sa admin dashboard
//  }
   
   class Dashboard {

     function index() {
          require_once __DIR__ . '/reports_analytics.php';
          $totalSales = $this -> getTotalSales();
          $gross_profit = $this -> getGrossProfit();
          $total_orders = $this -> getTotalOrders();
          $total_products_services = $this -> getTotalProductsServices();
          $sales_trend = $this -> salesTrend();
          $recent_orders = $this -> getRecentOrders();

          // Return the data as a JSON response
          $response = [
               'totalSales' => $totalSales,
               'grossProfit' => $grossProfit,
               'totalOrders' => $totalOrders,
               'totalProductsServices' => $totalProductsServices,
               'salesTrend' => $salesTrend,
               'recentOrders' => $recentOrders
               ];

          // Send JSON response
          header('Content-Type: application/json');
          echo json_encode($response);
     }

     function getTotalSales() {
          global $conn;
          $query = "SELECT SUM(total_amount) as total_sales FROM orders WHERE payment_status = 'paid'"; 
          $result = mysqli_query($conn, $query);
          $total = mysqli_fetch_assoc($result); //associative array 
          return $total['total_sales'];      
     }

     function getTotalCOGS () {
          global $conn;
          $query = "SELECT SUM(oi.quantity - p.cost_price) as total_cogs
                    FROM order_item oi
                    JOIN product p ON (p.product_id = oi.product_id)";
          $result = mysqli_query($conn, $query);
          $total = mysqli_fetch_assoc($result);
          return $total['total_cogs'];
     }

     function getGrossProfit () {
          $total_sales = $this -> getTotalSales();
          $total_cogs = $this -> getTotalCOGS();
          return $total_sales - $total_cogs;
     }
  
     function getTotalOrders() {
          global $conn;
          $query = "SELECT Status, COUNT(*) as NumberOfOrders FROM orders GROUP BY status";
          $result = mysqli_query($conn, $query);
          $total = mysqli_fetch_assoc($result); //associative array 
          return $total;     
     }
  
     function getTotalProductsServices() {
          global $conn;
          $query = "SELECT type, COUNT(*) as number FROM products GROUP BY type";
          $result = mysqli_query($conn, $query);
          $products_services = [];
          
          while ($row = mysqli_fetch_assoc($result)) {
               $products_services[] = $row;
          }

          return $products_services;
     }
  
     function salesTrend ($year = null){ //line graph
          global $conn;

          if ($year === null) {
               $year = date('Y'); // Get the current year
           }
       
           $query = "SELECT DATE_FORMAT(date_ordered, '%M') AS month_name, 
                            SUM(total_amount) AS total_sales
                     FROM orders 
                     WHERE payment_status = 'paid' 
                     AND YEAR(date_ordered) = $year  -- Filter by specific year
                     GROUP BY MONTH(date_ordered)  -- Group by month only
                     ORDER BY MONTH(date_ordered) ASC";  // Ensure months are in order (1 to 12)

          $result = mysqli_query($conn, $query);
          $sales = [];
           
          while ($row = mysqli_fetch_assoc($result)) {
            $sales[] = $row;
          }

          return $sales;
     }
  
     function paymentMethod() { //pie chart
          global $conn;
          $query = "SELECT payment_method, COUNT(*) as numberOfOrders 
                    FROM orders 
                    GROUP BY payment_method";

          $result = mysqli_query($conn, $query);
          $payment = [];
  
          while ($row = mysqli_fetch_assoc($result)) {
            $payment[] = $row;
          }
          return $payment;
     }
  
     function getRecentOrders() {
          global $conn;
          $query = "SELECT o.order_id, o.user_id, o.date_ordered, o.total_amount, o.payment_status, 
                              GROUP_CONCAT(p.name ORDER BY oi.product_id) AS products
                    FROM orders o
                    JOIN order_item oi ON o.order_id = oi.order_id
                    JOIN product p ON oi.product_id = p.product_id
                    GROUP BY o.order_id
                    ORDER BY o.date_ordered DESC
                    LIMIT 10";

          $result = mysqli_query($conn, $query);
          $orders = [];
  
          while ($row = mysqli_fetch_assoc($result)) {
            $orders[] = $row;
          }
          return $orders;
     }
  
  
   }





?>