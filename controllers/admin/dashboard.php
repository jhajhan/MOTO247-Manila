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

     function index($data) {
          require_once __DIR__ . '/reports_analytics.php';
          $totalSales = $this -> getTotalSales();
          $daily_proft = $this -> getDailyProfit($data);
          $monthly_profit = $tis -> getMonthlyProfit($data);
          $total_orders = $this -> getTotalOrders();
          $total_products_services = $this -> getTotalProductsServices();
          $sales_trend = $this -> salesTrend();
          $recent_orders = $this -> getRecentOrders();

          // Return the data as a JSON response
          $response = [
               'totalSales' => $totalSales,
               'dailyProfit' => $daily_proft,
               'monthlyProfit' => $monthly_profit,
               'totalOrders' => $total_orders,
               'totalProductsServices' => $total_products_services,
               'salesTrend' => $sales_trend,
               'recentOrders' => $recent_orders
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

     function getDailyProfit($data) {
          $date = $data['date'] ?? date('Y-m-d');

          global $conn;
          $query = 'SELECT SUM(o.total_amount) as total_sales
                    SUM(oi.quantity * p.price) as total_cogs
                    FROM orders o
                    JOIN order_item oi ON o.order_id = oi.order_id
                    JOIN product p ON oi.product_id = p.product_id
                    WHERE date_ordered = ?';

          $stmt = mysqli_prepare($conn,$query);
          $stmt->bind_param('s', $date);
          $stmt->execute();
          $result = $stmt->get_result();
          $data = mysqli_fetch_assoc($result);

          return (float)$data['total_sales'] - (float)$data['total_cogs'];
     }

     function getMonthlyProfit($data) {
          $month = $data['month'] ?? date('Y-m');

          global $conn;
          $query = 'SELECT SUM(o.total_amount) as total_sales
                    SUM(oi.quantity * p.price) as total_cogs
                    FROM orders o
                    JOIN order_item oi ON o.order_id = oi.order_id
                    JOIN product p ON oi.product_id = p.product_id
                    WHERE MONTH(date_ordered) = ?';

          $stmt = mysqli_prepare($conn,$query);
          $stmt->bind_param('s', $month);
          $stmt->execute();
          $result = $stmt->get_result();
          $data = mysqli_fetch_assoc($result);

          return (float)$data['total_sales'] - (float)$data['total_cogs'];
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