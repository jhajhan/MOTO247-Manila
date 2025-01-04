<?php
    require_once('../../config/db.php');

    function getParts () {
        global $conn;
        $query = 'SELECT * FROM products WHERE Type = "parts"';
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $parts = [];
        while ($row = $result->fetch_assoc()) {
            $parts[] = $row;
        }
        return $parts;
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

    function addProduct() {
        global $conn;
        $query = 'INSERT INTO products (name, description, price, stock, type) VALUES (?, ?, ?, ?, ?)';
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssdis', $name, $description, $price, $stock, $type);
        $stmt->execute();
    }

    function updateProduct() {
        global $conn;
        $query = 'UPDATE products SET name = ?, description = ?, price = ?, stock = ?, type = ? WHERE id = ?';
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssdisi', $name, $description, $price, $stock, $type, $id);
        $stmt->execute(); 
    }

    function deleteProduct() {
        global $conn;
        $query = 'DELETE FROM products WHERE id = ?';
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
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
?>