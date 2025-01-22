<?php
    require_once __DIR__ . '/../../config/db.php';

    generateCSV();

    
    function generateReport () {
        global $conn;
        $query = "SELECT o.order_id, c.name AS customer_name, o.date_ordered AS date, o.payment_method, o.payment_status, o.status, o.total_amount, p.name AS product_name, oi.quantity AS quantity  -- Quantity from the order_items table
                FROM orders o 
                JOIN customers c ON o.customer_id = c.customer_id
                JOIN order_items oi ON oi.order_id = o.order_id
                JOIN products p ON p.product_id = oi.product_id
                ORDER BY o.date_ordered, oi.order_item_id;";
                
                $result = mysqli_query($conn, $query);
                $report = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $report[] = $row;
                }
                return $report;
    }

    function generateCSV () {
        $report = generateReport();
        $filename = 'report.csv';
        $fp = fopen('php://output', 'w');
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . 'report.csv');
        foreach ($report as $row) {
            fputcsv($fp, $row);
        }
        fclose($fp);
    }
?>