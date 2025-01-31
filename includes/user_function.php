<?php


include('F:\xampp\htdocs\MOTO247-Manila\config\db.php');


function getAllActiveProducts() {
    global $conn;
    $query = "SELECT * FROM Product WHERE type = 'part' ";  // Hardcoded 'products' table name
    return $query_run = mysqli_query($conn, $query);
}

function getIDActiveProduct($id) {
    global $conn;
    $query = "SELECT * FROM Product WHERE prod_id = '$id' AND stock > 0" ;  // Hardcoded 'products' table name
    return $query_run = mysqli_query($conn, $query);
}

function getByIDProduct($id) {
    global $conn;
    $query = "SELECT * FROM Product WHERE prod_id = $id";  // Hardcoded 'products' table name
    return $query_run = mysqli_query($conn, $query);
}



function getAllActiveService() {
    global $conn;
    $query = "SELECT * FROM Product WHERE type = 'service' ";  // Hardcoded 'products' table name
    return $query_run = mysqli_query($conn, $query);
}

function getIDActiveService($id) {
    global $conn;
    $query = "SELECT * FROM Product WHERE prod_id = '$id' AND  type ='service'" ;  // Hardcoded 'products' table name
    return $query_run = mysqli_query($conn, $query);
}

function getByIDService($id) {
    global $conn;
    $query = "SELECT * FROM Product WHERE prod_id = $id";  // Hardcoded 'products' table name
    return $query_run = mysqli_query($conn, $query);
}

function redirect($url, $message) {
    $_SESSION['message'] = $message;
    header('Location: '.$url);
    exit(0);
}



?>