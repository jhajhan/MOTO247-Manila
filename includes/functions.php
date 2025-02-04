<?php

// session_start();
include('F:\xampp\htdocs\MOTO247-Manila\config\db.php');

function getAll() {
    global $conn;
    $query = "SELECT * FROM Product";  // Hardcoded 'products' table name
    $query_run = mysqli_query($conn, $query);
    return $query_run;  // Return the result of the query
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

?>
