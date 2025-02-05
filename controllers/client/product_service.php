<?php

require_once(__DIR__ . '/../../config/db.php');


class Product_Service {

    
    function index() {

        $top_products = $this->getTopProducts();
        $new_products = $this->getNewlyProducts();

        $response = [
            'top_products' => $top_products,
            'new_products'=> $new_products
        ];

        header('Content-Type: application/json');
        echo json_encode($response); 
    }
    function getProducts() {
        global $conn;
        $query = "SELECT * FROM product WHERE type = 'product'";  // Hardcoded 'products' table name
        $result = mysqli_query($conn, $query);

        $products = [];

        while($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }

        $response = [
            'products' => $products
        ];

        header('Content-Type: application/json');
        echo json_encode($response); 
        
    }

    function getServices() {
        global $conn;
        $query = "SELECT * FROM product WHERE type = 'service'";  // Hardcoded 'products' table name
        $result = mysqli_query($conn, $query);

        $services = [];

        while($row = mysqli_fetch_assoc($result)) {
            $services[] = $row;
        }

        $response = [
            'services' => $services
        ];

        header('Content-Type: application/json');
        echo json_encode($response); 
        
    }

    function getTopProducts() {
        global $conn;
        $query = 'SELECT name, description, image, price, SUM(quantity) as total_quantity FROM order_item JOIN product ON product.prod_id = order_item.prod_id WHERE type = "product" GROUP BY name ORDER BY total_quantity DESC LIMIT 4';
        $result = mysqli_query($conn, $query);
        $top_products = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $top_products[] = $row;
        }
        return $top_products;
    }

    function getNewlyProducts() {
        global $conn;
        $query = 'SELECT *
                    FROM product 
                    WHERE type = "product" 
                    ORDER BY created_at DESC 
                    LIMIT 4';

        $result = mysqli_query($conn, $query);
        $new_products = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $new_products[] = $row;
        }
        return $new_products;
    }
    
    function getByID($id) {
        global $conn;
        $query = "SELECT * FROM Product WHERE prod_id = $id";  // Hardcoded 'products' table name
        return $query_run = mysqli_query($conn, $query);
    }
    
    
    
    function getAllActive() {
        global $conn;
        $query = "SELECT * FROM Product WHERE stock > 0";  // Hardcoded 'products' table name
        return $query_run = mysqli_query($conn, $query);
    }
    
    function getIDActive($id) {
        global $conn;
        $query = "SELECT * FROM Product WHERE prod_id = '$id' AND stock > 0" ;  // Hardcoded 'products' table name
        return $query_run = mysqli_query($conn, $query);
    }
    
    function redirect($url, $message) {
        $_SESSION['message'] = $message;
        header('Location: '.$url);
        exit(0);
    }
    
}
?>
