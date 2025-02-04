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

// Service Functions

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

//Cart Functions

function getCartItems() {
    global $conn;

    // Ensure the user is authenticated before proceeding
    // if (!isset($_SESSION['auth_user']['user_id'])) {
    //     $_SESSION['message'] = "You need to log in first to access the cart.";        
    //     header("Location: login.php"); // Redirect to login page
    //     exit(); // Stop further execution
    // }

    $user_id = $_SESSION['auth_user']['user_id']; // Get the logged-in user's ID

    // SQL query to retrieve cart items along with product details
    $query = "SELECT c.id AS cid, c.prod_id, c.prod_qty, p.prod_id AS pid, p.name, p.image, p.price 
              FROM cart c, product p 
              WHERE c.prod_id = p.prod_id AND c.user_id = '$user_id'
              ORDER BY c.id DESC";

    return $query_run = mysqli_query($conn, $query);
    // Prepare the SQL statement to prevent SQL injection
    // $stmt = mysqli_prepare($conn, $query);

    // // Bind the user_id parameter to the query (expects an integer)
    // mysqli_stmt_bind_param($stmt, "i", $user_id);

    // // Execute the query
    // mysqli_stmt_execute($stmt);

    // Return the result set from the executed query
//     return mysqli_stmt_get_result($stmt);
}


function getOrders() 
{
    global $conn;
    $userId = $_SESSION['auth_user']['user_id'];

    // Use backticks around the table name 'order'
    $query = "SELECT * FROM `order` WHERE user_id = '$userId' ORDER BY order_id DESC";
    $query_run = mysqli_query($conn, $query);

    // Check if the query was successful
    if ($query_run) {
        return $query_run;
    } else {
        // Handle the error, e.g., log it or return false
        error_log("Error in getOrders: " . mysqli_error($conn));
        return false;
    }
}



function redirect($url, $message) {
    $_SESSION['message'] = $message;
    header('Location: '.$url);
    exit(0);
}


function checkTrackingNum($tracking_no){
    global $conn;
    $userId = $_SESSION['auth_user']['user_id'];

    $query ="SELECT * FROM `order` WHERE tracking_no = '$tracking_no' AND  user_id = '$userId'";
    return mysqli_query($conn, $query);
}


?>