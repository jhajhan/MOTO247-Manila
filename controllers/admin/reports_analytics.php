<?php
    require_once('../../config/db.php');

    class Reports_Analytics {

        function index($data = null) {
            
            $sales = 0;

            if ($data) {
                $aggregation = $data['aggregation'];
                $sales = $this->getSales($aggregation);
            } else {
                $sales = $this->getSales('daily');
            }

            $earnings = $this->getEarningsTrend();
            $order_status = $this->getOrderStatus();
            $sales_comparison = $this->getSalesComparison();
            $payment_comparison = $this->getPaymentComparison();
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

            return $response;


        }


        function getSales($aggregation) {
            switch ($aggregation) {
                case 'daily':
                    return $this->getDailySales();
                    break;
                case 'weekly':
                    return $this->getWeeklySales();
                    break;
                case 'monthly':
                    return $this->getMonthlySales();
                    break;
                default:
                    return $this->getDailySales();
                    break;
            }
        }

        function getDailySales() {
            
            $startOfWeek = date('Y-m-d', strtotime('monday this week'));
            $endOfWeek = date('Y-m-d', strtotime('sunday this week'));

            global $conn;
            $query = 'SELECT DATE_FORMAT(date_ordered, "%Y-%m-%d") as date_ordered, SUM(total_amount) as total_sales FROM orders WHERE date_ordered BETWEEN "'.$startOfWeek.'" AND "'.$endOfWeek.'" GROUP BY DATE_FORMAT(date_ordered, "%Y-%m-%d")';
            $result = mysqli_query($conn, $query);
            

            $weekdays = [];
            $currentDate = $startOfWeek;
            while ($currentDate <= $endOfWeek) {
                $weekdays[] = $currentDate;
                $currentDate = date('Y-m-d', strtotime($currentDate . ' + 1 day'));
            }

            $daily_sales = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $daily_sales[] = $row;
            }


            while($row = mysqli_fetch_assoc($result)) {
                $weekdays[$row['date_ordered']] = (float)$row['total_sales']; //
            }

            $response = [
                'weekdays' => $weekdays,
                'daily_sales' => $daily_sales
            ];

            return $response;

        
        }
    
        function getWeeklySales() {
            global $conn;
            $query = 'SELECT CASE WHEN DAY("date_ordered") BETWEEN 1 AND 7 THEN "Week 1"
            WHEN DAY("date_ordered") BETWEEN 8 AND 14 THEN "Week 2"
            WHEN DAY("date_ordered") BETWEEN 15 AND 21 THEN "Week 3"
            ELSE "Week 4" END as week, SUM(total_amount) as total_sales FROM orders WHERE YEAR("date-ordered") = YEAR(NOW()) AND MONTH("date-ordered") = MONTH(NOW()) AND status = "processed" GROUP BY week ORDER BY FIELD(week_of_month, "Week 1", "Week 2", "Week 3", "Week 4", "Week 5")';

            $result = mysqli_query($conn, $query);

            $weekly_sales = [
                'weeks' => ['Week 1', `Week 2`, `Week 3`, `Week 4`, 'Week 5'],
                'total_sales' => [0, 0, 0, 0, 0]
            ];

            if ($result -> num_rows > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $week_index = (int)substr($row['week_of_month'], 5) - 1;
                    $weekly_sales['total_sales'][$week_index] = (float)$row['total_sales'];
                }
            }

            return $weekly_sales;

        }

        function getMonthlySales() {
            global $conn;

            // Query to calculate monthly sales
            $query = "SELECT MONTH(sale_date) AS sale_month, SUM(total_amount) AS monthly_total
                    FROM sales
                    WHERE YEAR(sale_date) = YEAR(NOW())  
                    GROUP BY sale_month
                    ORDER BY sale_month";

            $result = $conn->query($query);

            // Initialize the response array with months (January to December) and zero sales
            $monthly_sales = [
                'months' => [
                    'January', 'February', 'March', 'April', 'May', 'June', 
                    'July', 'August', 'September', 'October', 'November', 'December'
                ],
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

            return $monthly_sales;
        }


        function getOrderStatus() {
            global $conn;
            $query = 'SELECT status, COUNT(*) as total_orders FROM orders GROUP BY status';
            $result = mysqli_query($conn, $query);
            $order_status = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $order_status[$row['status']] = (int)$row['total_orders'];
            }
            return $order_status;
        }


        function getSalesComparison () {
            global $conn;
            $query = 'SELECT type, SUM(total_amount) FROM orders WHERE payment_status = "paid" GROUP BY "type"';
            $result = mysqli_query($conn, $query);
            $sales_comparison = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $sales_comparison[$row['type']] = (float)$row['SUM(total_amount)'];
            }
            return $sales_comparison;
    
        }
    
        function getPaymentComparison() {
            global $conn;
            $query = 'SELECT payment_method, SUM(total_amount) FROM orders WHERE payment_status = "paid" GROUP BY "payment_method"';
            $result = mysqli_query($conn, $query);
            $payment_comparison = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $payment_comparison[$row['payment_method']] = (float)$row['SUM(total_amount)'];
            }
            return $payment_comparison;
        }

        function getEarningsTrend() {
            global $conn;
            $query = 'SELECT DATE_FORMAT(date_ordered, "%Y-%m-%d") as date_ordered, SUM(total_amount) as total_sales FROM orders WHERE date_ordered BETWEEN DATE_SUB(NOW(), INTERVAL 1 MONTH) AND NOW() GROUP BY DATE_FORMAT(date_ordered, "%Y-%m-%d")';
            $result = mysqli_query($conn, $query);
            $earnings_trend = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $earnings_trend = $row;
            }
            return $earnings_trend;

        } //monthly

        function getTopProducts() {
            global $conn;
            $query = 'SELECT product_name, SUM(quantity) as total_quantity FROM order_items GROUP BY product_name ORDER BY total_quantity DESC LIMIT 5';
            $result = mysqli_query($conn, $query);
            $top_products = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $top_products[$row['product_name']] = (int)$row['total_quantity'];
            }
            return $top_products;
        }

        function getTopServices() {
            global $conn;
            $query = 'SELECT service_name, SUM(quantity) as total_quantity FROM order_item GROUP BY service_name ORDER BY total_quantity DESC LIMIT 5';
            $result = mysqli_query($conn, $query);
            $top_services = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $top_services[$row['service_name']] = (int)$row['total_quantity'];
            }
            return $top_services;
        }





    }

    


?>