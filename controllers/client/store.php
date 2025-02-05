<?php

require_once(__DIR__ . '/../../config/db.php');

class Store {
    function getStoreInfo() {
        global $conn;

        // Check for a valid database connection
        if (!$conn) {
            echo json_encode(['error' => 'Database connection failed']);
            exit;
        }

        // Perform the query
        $query = "SELECT * FROM store_info";
        $result = mysqli_query($conn, $query);

        // Handle query failure
        if (!$result) {
            echo json_encode(['error' => 'Query failed: ' . mysqli_error($conn)]);
            exit;
        }

        // Fetch the result
        $store_info = mysqli_fetch_assoc($result);

        // Check if store info was found
        if (!$store_info) {
            echo json_encode(['error' => 'No store info found']);
            exit;
        }

        // Prepare and send the response
        $response = [
            'store_info' => $store_info
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
    }
}


?>