<?php
    require_once(__DIR__ . '/../../config/db.php');
    
    class Product_Service {



        function index() {
            global $conn;

            $type = isset($_GET['type']) && ($_GET['type']) != 'all' ? strtolower($_GET['type']) : '';

            // echo $type;

            $stock = isset($_GET['stock']) && ($_GET['stock']) != 'all' ? $_GET['stock'] : '';
            $min_price = isset($_GET['min_price']) ? $_GET['min_price'] : '';
            $max_price = isset($_GET['max_price']) ? $_GET['max_price'] : '';



            $products_services = $this -> getProductsAndServices($type, $stock, $min_price, $max_price);

            $response = [
                'products_services' => $products_services
            ];

            header('Content-Type: application/json');
            echo json_encode($response);    
        }


        function getProductsAndServices ($type, $stock, $min_price, $max_price) {
            global $conn;

            if (!$conn) {
                throw new Exception("Database connection is not initialized.");
            }

            $query = 'SELECT * FROM product';



            if ($type != '') {
                $query .= " WHERE type = ?";
            }

            if ($stock != '') {
                if ($type != '') {
                    if ($stock == 'inStock'){
                        $query .= " AND stock > ?";
                    } else {
                         $query .= " AND stock = ?";
                    }

                } else {
                    if ($stock == 'inStock') {
                        $query .= " WHERE stock > ?";
                    } else {
                        $query .= " WHERE stock = ?";
                    }
                }
            }

            if ($min_price != '') {
                if ($type != '' || $stock != '') {
                    $query .= " AND Price >= ?";
                } else {
                    $query .= " WHERE price >= ?";
                }
            }

            if ($max_price != '') {
                if ($type != '' || $stock != '' || $min_price != '') {
                    $query .= " AND Price <= ?";
                } else {
                    $query .= " WHERE price <= ?";
                }
            }

            $params = [];
            $types = '';

            if ($type != '') {
                $params[] = $type;
                $types .= 's';
            }

            if ($stock != '') {
                $params[] = '0';
                $types .= 'd';
            }

            if ($min_price != '') {
                $params[] = $min_price;
                $types .= 'd';
            }

            if ($max_price != '') {
                $params[] = $max_price;
                $types .= 'd';
            }

            // echo $query;
            
            $stmt = $conn->prepare($query);

            
            if ($types != '') {
                $stmt->bind_param($types, ...$params);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            $products = [];

            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
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
        public function addProduct($data) {
            global $conn;

            // Retrieve data from the AJAX request
            $name = $data['name'];
            $price = $data['price'];
            $unit_price = $data['unit_price'];
            $type = $data['type'];
            $stock = $data['stock'];
            $description = $data['description'];
            $img = $data['img'];

            $query = "INSERT INTO product (name, price, unit_price, type, stock, description, image) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            $stmt->bind_param('sddsdss', $name, $price, $unit_price, $type, $stock, $description, $img);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Product added successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to add product']);
            }
        }

        // Edit an existing product/service
        public function editProduct($data) {
            global $conn;

            // Retrieve data from the AJAX request
            $id = $data['id'];
            $name = $data['name'];
            $price = $data['price'];
            $type = $data['type'];
            $stock = $data['stock'];
            $description = $data['description'];
            $img = $data['img'];


            $query = "UPDATE product SET name= ?, price= ?, type= ?, stock = ?, description= ?, image = ? WHERE prod_id= ?";
            $stmt = mysqli_prepare($conn, $query);
            $stmt->bind_param('sdsissi', $name, $price, $type, $stock, $description, $img, $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Product updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update product']);
            }
        }

        // Delete a product/service
        public function deleteProduct($data) {
            global $conn;

            // Retrieve product ID from the AJAX request
            $id = $data['id'];

            $query = "DELETE FROM product WHERE prod_id='$id'";
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