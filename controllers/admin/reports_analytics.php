<?php
    require_once('../../config/db.php');

    class Reports_Analytics {

        function index() {
            
        }
        function getDailySales() {
            global $conn;
            $query = 'SELECT DATE_FORMAT(date_ordered, "%Y-%m-%d") as date_ordered, SUM(total_amount) as total_sales FROM orders GROUP BY DATE_FORMAT(date_ordered, "%Y-%m-%d")';
            $result = mysqli_query($conn, $query);
            $daily_sales = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $daily_sales[] = $row;
            }
            return $daily_sales;
        }
    
        function getMonthlySales() {
            global $conn;
            $query = 'SELECT DATE_FORMAT(date_ordered, "%Y-%m") as date_ordered, SUM(total_amount) as total_sales FROM orders GROUP BY DATE_FORMAT(date_ordered, "%Y-%m")';
            $result = mysqli_query($conn, $query);
            $monthly_sales = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $monthly_sales[] = $row;
            }
            return $monthly_sales;
        }
    
        function getAnnualSales() {
            global $conn;
            $query = 'SELECT DATE_FORMAT(date_orderd, "%Y") as date_ordered, SUM(total_amount) as total_orders FROM orders GROUP BY DATE_FORMAT(date_ordered, "%Y")';
            $result = mysqli_query($conn, $query);
            $annual_sales = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $annual_sales[] = $row;
            }
            return $annual_sales;
        }
    
        function getSalesComparison () {
            global $conn;
            $query = 'SELECT type, SUM(total_amount) FROM orders WHERE payment_status = "paid" GROUP BY "type"';
            $result = mysqli_query($conn, $query);
            $sales_comparison = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $sales_comparison = $row;
            }
            return $sales_comparison;
    
        }
    
        function getPaymentComparison() {
            global $conn;
            $query = 'SELECT payment_method, SUM(total_amount) FROM orders WHERE payment_status = "paid" GROUP BY "payment_method"';
            $result = mysqli_query($conn, $query);
            $payment_comparison = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $payment_comparison = $row;
            }
            return $payment_comparison;
        }
    }

    


?>