<?php
    require_once(__DIR__ . '/../../config/db.php');

    class Product_Service {



        function index() {
            
        }


        function getProductsAndServices () {
            global $conn;
            $query = 'SELECT * FROM product';
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            $products = [];

            while ($row = $result->fetch_assoc()) {
                $products = $row;
            }

            return $products;
        }

        function getProducts () {
            global $conn;
            $query = 'SELECT * FROM product WHERE Type = "part"';
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result(); //associative 
            $parts = [];
            while ($row = $result->fetch_assoc()) {
                $parts[] = $row; 
            }
            
            require_once  __DIR__ . '/../../views/client/products.php';

        }
    
        function getServices() {
            global $conn;
            $query = 'SELECT * FROM products WHERE Type = "services"';
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            $services = [];
            while ($row = $result->fetch_assoc()) {
                $services[] = $row;
            }

            return $services;
        }
    
        // Add a new product/service
        public function addProduct() {
            global $conn;

            // Retrieve data from the AJAX request
            $name = $_POST['name'];
            $price = $_POST['price'];
            $type = $_POST['type'];

            $query = "INSERT INTO products (name, price, type) VALUES ('$name', '$price', '$type')";
            $result = mysqli_query($conn, $query);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Product added successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to add product']);
            }
        }

        // Edit an existing product/service
        public function editProduct() {
            global $conn;

            // Retrieve data from the AJAX request
            $id = $_POST['id'];
            $name = $_POST['name'];
            $price = $_POST['price'];
            $type = $_POST['type'];

            $query = "UPDATE products SET name='$name', price='$price', type='$type' WHERE id='$id'";
            $result = mysqli_query($conn, $query);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Product updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update product']);
            }
        }

        // Delete a product/service
        public function deleteProduct() {
            global $conn;

            // Retrieve product ID from the AJAX request
            $id = $_POST['id'];

            $query = "DELETE FROM products WHERE id='$id'";
            $result = mysqli_query($conn, $query);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Product deleted successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete product']);
            }
        }
        
    
        function getStockAlert (){
            global $conn;
            $query = 'SELECT * FROM products WHERE stock < 10';
            $result = mysqli_query($conn, $query);
            $stock_alert = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $stock_alert[] = $row;
            }
            return $stock_alert;
        }
    
        function getStock () {
            global $conn;
            $query = 'SELECT * FROM products WHERE stock > 0';
            $result = mysqli_query($conn, $query);
            $stock = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $stock[] = $row;
            }
            return $stock;
        }
    }
?>