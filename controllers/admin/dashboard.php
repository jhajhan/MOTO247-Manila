<?php 
    require_once(__DIR__ . '/../../config/db.php');

   // may validation dapat dine if naka-login si admin or not
//    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
//      // If the user is not logged in as an admin, redirect to the login page
//      header('Location: ../../login.php');
//      exit();
//      // dapat ma reredirect si customer sa customer dashboard tas admin sa admin dashboard
//  }
   
   class Dashboard {

     function index($data) {
          // require_once __DIR__ . '/reports_analytics.php';
          $totalSales = $this -> getTotalSales($data);
          $daily_proft = $this -> getDailyProfit($data);
          $monthly_profit = $this -> getMonthlyProfit($data);
          $total_orders = $this -> getTotalOrders();
          $total_products_services = $this -> getTotalProductsServices();
          $payment_method = $this -> paymentMethod();
          $sales_trend = $this -> salesTrend();
          $recent_orders = $this -> getRecentOrders();

          // Return the data as a JSON response
          $response = [
               'totalSales' => $totalSales,
               'dailyProfit' => $daily_proft,
               'monthlyProfit' => $monthly_profit,
               'totalOrders' => $total_orders,
               'totalProductsServices' => $total_products_services,
               'paymentMethod' => $payment_method,
               'salesTrend' => $sales_trend,
               'recentOrders' => $recent_orders
               ];

          // Send JSON response
          header('Content-Type: application/json');
          echo json_encode($response);
     }

     function getTotalSales($data) {
          global $conn;

          $date = $data['date'];

          $query = "SELECT SUM(total_amount) as total_sales FROM `order` WHERE payment_status = 'paid' AND date_ordered = ?";
          $stmt = mysqli_prepare($conn, $query);

          if (!$stmt) {
               die("Query preparation failed: " . mysqli_error($conn));
          }

          $stmt->bind_param('s', $date);
          $stmt->execute();

          $result = $stmt->get_result();
          $row = $result->fetch_assoc();

          return $row['total_sales'] ?? 0; // Return total_sales or 0 if NULL      
     }

     function getDailyProfit($data) {
          // $date = $data['date'] ?? date('Y-m-d');
          $date = '2025-01-01';

          global $conn;
          $query = 'SELECT SUM(oi.total_per_product) as total_sales,
                    SUM(oi.quantity * p.unit_price) as total_cogs
                    FROM `order` o
                    JOIN order_item oi ON o.order_id = oi.order_id
                    JOIN product p ON oi.prod_id = p.prod_id
                    WHERE date_ordered = ?';

          $stmt = mysqli_prepare($conn,$query);
          $stmt->bind_param('s', $date);
          $stmt->execute();
          $result = $stmt->get_result();
          $data = mysqli_fetch_assoc($result);

          // echo $data['total_cogs'];

          $response = [
               'total_sales' => $data['total_sales'],
               'total_cogs' => $data['total_cogs'],
               'total_profit' => (float)$data['total_sales'] - (float)$data['total_cogs']
          ];

          return $response;
     }

     function getMonthlyProfit($data) {
          // $month = $data['month'] ?? date('m');
          $month = $data['month'];

          global $conn;
          $query = 'SELECT SUM(oi.total_per_product) as total_sales,
                    SUM(oi.quantity * p.unit_price) as total_cogs
                    FROM `order` o
                    JOIN order_item oi ON o.order_id = oi.order_id
                    JOIN product p ON oi.prod_id = p.prod_id
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
          $query = "SELECT COUNT(*) as no_orders FROM `order`";
          $result = mysqli_query($conn, $query);
          $total = mysqli_fetch_assoc($result); //associative array 
          return $total;     
     }
  
     function getTotalProductsServices() {
          global $conn;
          $query = "SELECT COUNT(*) as no_products_services FROM product";
          $result = mysqli_query($conn, $query);
          $products_services = [];
          
          $total = mysqli_fetch_assoc($result); //associative array 
          return $total;  
     }
  
     function salesTrend ($year = null){ //line graph
          global $conn;

          if ($year === null) {
               $year = date('Y'); // Get the current year
           }
       
          $query = "SELECT 
                    DATE_FORMAT(o.date_ordered, '%Y-%m') AS month,
                    SUM(oi.total_per_product) AS total_sales,
                    SUM(oi.quantity * p.unit_price) AS total_cogs,
                    (SUM(oi.total_per_product) - SUM(oi.quantity * p.unit_price)) AS gross_profit
                    FROM `order` o
                    JOIN order_item oi ON o.order_id = oi.order_id
                    JOIN product p ON oi.prod_id = p.prod_id
                    GROUP BY month
                    ORDER BY month ASC";  // Ensure months are in order (1 to 12)

          


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
                    FROM `order`
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
          // Adjust the query to return a comma-separated string of product names
          $query = "SELECT c.full_name, o.total_amount, o.payment_status, o.status, 
               GROUP_CONCAT(p.name ORDER BY oi.prod_id) AS products
               FROM `order` o
               JOIN order_item oi ON o.order_id = oi.order_id
               JOIN product p ON oi.prod_id = p.prod_id
               JOIN user c ON o.user_id = c.user_id
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