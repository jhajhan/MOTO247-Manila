<?php
    require_once(__DIR__ . '/../../config/db.php');

    require_once('dashboard.php');
    class Reports_Analytics {

        function index($data = null) {
            
            $dashboard = new Dashboard();

            $sales = 0;

            if ($data) {
                $aggregation = $data['aggregation'];
                $sales = $this->getSales($aggregation);
            } 

            $earnings = $dashboard->salesTrend();
            $order_status = $this->getOrderStatus();
            $sales_comparison = $this->getSalesComparison();
            $payment_comparison = $dashboard->paymentMethod();
            $top_products = $this->getTopProducts();
            $top_services = $this->getTopServices();

            $response = [
                'sales' => $sales,
                'earnings' => $earnings,
                'order_status' => $order_status,
                'sales_comparison' => $sales_comparison,
                'payment_comparison' => $payment_comparison,
                'top_products' => $top_products,
                'top_services' => $top_services
            ];

            header('Content-Type: application/json');
            echo json_encode($response); 

        }


        function getSales($aggregation) {
            switch ($aggregation) {
                case 'daily':
                    return $this->getDailySales();
                case 'weekly':
                    return $this->getWeeklySales();
                case 'monthly':
                    return $this->getMonthlySales();
                default:
                    return $this->getDailySales();
            }
        }
        
        function getDailySales() {
            $startOfWeek = date('Y-m-d', strtotime('monday this week'));
            $endOfWeek = date('Y-m-d', strtotime('sunday this week'));
            
            global $conn;
            $query = 'SELECT DATE_FORMAT(date_ordered, "%Y-%m-%d") as date_ordered, SUM(total_amount) as total_sales 
                      FROM `order`
                      WHERE date_ordered BETWEEN "'.$startOfWeek.'" AND "'.$endOfWeek.'" 
                      GROUP BY DATE_FORMAT(date_ordered, "%Y-%m-%d")';
            
            $result = mysqli_query($conn, $query);
            
            // Initialize weekdays array with zero sales
            $weekdays = [];
            $currentDate = $startOfWeek;
            while ($currentDate <= $endOfWeek) {
                $weekdays[$currentDate] = 0; // Default to zero sales
                $currentDate = date('Y-m-d', strtotime($currentDate . ' + 1 day'));
            }
            
            // Fetch sales data and update sales for the corresponding days
            while ($row = mysqli_fetch_assoc($result)) {
                $weekdays[$row['date_ordered']] = (float)$row['total_sales'];
            }
            
            // Convert array into structured data for charting
            $daily_sales = [];
            foreach ($weekdays as $date => $sales) {
                // Convert date to weekday name (Monday, Tuesday, etc.)
                $weekday = date('l', strtotime($date)); // 'l' gives the full textual representation of the day
                $daily_sales[] = [
                    'label' => $weekday,  // Weekday label for the bar chart
                    'sales' => $sales     // Sales amount for the bar chart
                ];
            }
            
            return [
                'labels' => array_column($daily_sales, 'label'),  // Weekday names for the x-axis
                'sales' => array_column($daily_sales, 'sales')    // Sales for the y-axis
            ];
        }
        
        function getWeeklySales() {
            global $conn;
            $query = 'SELECT 
                        CASE 
                            WHEN DAY(date_ordered) BETWEEN 1 AND 7 THEN "Week 1"
                            WHEN DAY(date_ordered) BETWEEN 8 AND 14 THEN "Week 2"
                            WHEN DAY(date_ordered) BETWEEN 15 AND 21 THEN "Week 3"
                            WHEN DAY(date_ordered) BETWEEN 22 AND 28 THEN "Week 4"
                            ELSE "Week 5" 
                        END as week, 
                        SUM(total_amount) as total_sales 
                      FROM `order`
                      WHERE YEAR(date_ordered) = YEAR(NOW()) 
                      AND MONTH(date_ordered) = MONTH(NOW()) 
                      AND status = "completed" 
                      GROUP BY week 
                      ORDER BY FIELD(week, "Week 1", "Week 2", "Week 3", "Week 4", "Week 5")';
        
            $result = mysqli_query($conn, $query);
        
            // Initialize weekly sales data with default values (0 sales for each week)
            $weekly_sales = [
                'weeks' => ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5'],
                'total_sales' => [0, 0, 0, 0, 0]  // Default sales values are 0
            ];
        
            // Check if the query returned any results
            if ($result->num_rows > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    // Extract the week number from 'Week X' and map it to the array index (0-4)
                    $week_index = (int)substr($row['week'], 5) - 1; // Extract week number and convert to array index
                    // Update total_sales for that specific week
                    $weekly_sales['total_sales'][$week_index] = (float)$row['total_sales'];
                }
            }
        
            return [
                'labels' => $weekly_sales['weeks'],  // Week names for the x-axis
                'sales' => $weekly_sales['total_sales'] // Sales for the y-axis
            ];
        }
        
        function getMonthlySales() {
            global $conn;
            $query = "SELECT MONTH(date_ordered) AS sale_month, SUM(total_amount) AS monthly_total
                      FROM `order`
                      WHERE YEAR(date_ordered) = YEAR(NOW())  
                      GROUP BY MONTH(date_ordered) 
                      ORDER BY sale_month";
        
            $result = $conn->query($query);
        
            // Initialize the response array with months (January to December) and zero sales
            $monthly_sales = [
                'months' => ['January', 'February', 'March', 'April', 'May', 'June', 
                             'July', 'August', 'September', 'October', 'November', 'December'],
                'sales' => array_fill(0, 12, 0) // Initialize all sales to 0 for each month
            ];
        
            // Populate the monthly_sales array with actual data
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Map the month (1-12) to the array index (0-11)
                    $month_index = (int)$row['sale_month'] - 1;
                    $monthly_sales['sales'][$month_index] = (float)$row['monthly_total'];
                }
            }
        
            return [
                'labels' => $monthly_sales['months'],  // Months for the x-axis
                'sales' => $monthly_sales['sales']     // Sales for the y-axis
            ];
        }
        
    

        function getOrderStatus() {
            global $conn;
            $query = 'SELECT status, COUNT(*) as total_orders FROM `order` GROUP BY status';
            $result = mysqli_query($conn, $query);
            
            $order_status = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $order_status[] = [
                    'status' => $row['status'], 
                    'total_orders' => (int)$row['total_orders']
                ];
            }
            return $order_status;
        }


        function getSalesComparison() {
            global $conn;
        
            // Query to get total sales per payment type for each month
            $query = 'SELECT type, MONTH(date_ordered) AS month, SUM(total_amount) AS total_sales
                      FROM `order`
                      WHERE payment_status = "paid"
                      GROUP BY type, MONTH(date_ordered)
                      ORDER BY MONTH(date_ordered), type';
        
            $result = mysqli_query($conn, $query);
        
            // Initialize an array to store sales by month for each type
            $sales_comparison = [];
        
            // Fetch each row from the result and structure it
            while ($row = mysqli_fetch_assoc($result)) {
                $month = (int)$row['month']; // Get the month number (1 to 12)
        
                // If this type isn't already in the array, initialize it
                if (!isset($sales_comparison[$row['type']])) {
                    $sales_comparison[$row['type']] = array_fill(0, 12, 0); // 12 months initialized to 0 sales
                }
        
                // Store the sales for the specific month (index 0 corresponds to January)
                $sales_comparison[$row['type']][$month - 1] = (float)$row['total_sales'];
            }
        
            // Prepare the data for the chart in the required format
            $comparison_data = [];
            foreach ($sales_comparison as $type => $sales) {
                $comparison_data[] = [
                    'type' => $type,
                    'sales' => $sales  // Sales data for each month (12 months)
                ];
            }
        
            return $comparison_data;
        }
        

        function getTopProducts() {
            global $conn;
            $query = 'SELECT name, SUM(quantity) as total_quantity FROM order_item JOIN product ON product.prod_id = order_item.prod_id WHERE type = "product" GROUP BY name ORDER BY total_quantity DESC LIMIT 5';
            $result = mysqli_query($conn, $query);
            $top_products = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $top_products[] = [
                    'name' => $row['name'],
                    'quantity' => (int)$row['total_quantity']
                ];
            }
            return $top_products;
        }
    
        function getTopServices() {
            global $conn;
            $query = 'SELECT name, SUM(quantity) as total_quantity FROM order_item JOIN product ON product.prod_id = order_item.prod_id WHERE type = "service" GROUP BY name ORDER BY total_quantity DESC LIMIT 5';
            $result = mysqli_query($conn, $query);
            $top_services = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $top_services[] = [
                    'name' => $row['name'],
                    'quantity' => (int)$row['total_quantity']
                ];
            }
            return $top_services;
        }





    }

    


?>