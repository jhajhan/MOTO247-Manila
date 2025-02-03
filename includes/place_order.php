<?php

// Start the session
session_start();

// Include the database connection
include('F:\xampp\htdocs\MOTO247-Manila\config\db.php');

// Include user functions
require('F:\xampp\htdocs\MOTO247-Manila\includes\user_function.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is authenticated
if(isset($_SESSION['authenticated'])){

    // Check if the place order button was clicked
    if(isset($_POST['placeOrderBtn']))
    {
        // Sanitize and retrieve form inputs
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
        $notes = mysqli_real_escape_string($conn, $_POST['notes']);

        // Check if any required fields are empty
        if($name == "" || $email == "" || $phone == "" || $address == "" || $payment_method == "") {         
            $_SESSION['message'] = "Please fill in all the required fields.";
            header('Location: ../views/checkout.php');
            exit();
        }

        // Retrieve the user ID from the session
        $userId = $_SESSION['auth_user']['user_id'];

        // Query to get cart items for the user
        $query = "SELECT c.id AS cid, c.prod_id, c.prod_qty, p.prod_id AS pid, p.name, p.image, p.price 
                  FROM cart c, product p 
                  WHERE c.prod_id = p.prod_id AND c.user_id = '$userId'
                  ORDER BY c.id DESC";

        $query_run = mysqli_query($conn, $query);

        // Calculate the total amount for the order
        $cartItems = getCartItems();
        $total_amount = 0;
        foreach ($cartItems as $citem) {
            $total_amount += $citem['price'] * $citem['prod_qty'];
        }

        // Generate a tracking number for the order
        $tracking_no = rand(100000, 999999).substr($phone, 2);

        // Insert the order details into the user_orders table
        $query = "INSERT INTO `order` (tracking_no, user_id, name, email, phone, 
                  address, payment_method, total_amount, notes) 
                  VALUES ('$tracking_no', '$userId', '$name', '$email', '$phone', 
                  '$address', '$payment_method', '$total_amount', '$notes')";
        $insert_query_run = mysqli_query($conn, $query);

        // If the order was successfully inserted
        if($insert_query_run){
            $order_id = mysqli_insert_id($conn);

            // Insert each cart item into the order_item table
            foreach($query_run as $citem)
            {
                $prod_id = $citem['prod_id'];
                $prod_qty = $citem['prod_qty'];
                $price = $citem['price'];
                $insert_items_query = "INSERT INTO order_item (order_id, product_id, quantity, price) 
                                       VALUES ('$order_id','$prod_id','$prod_qty','$price')";
                $insert_query_run = mysqli_query($conn, $insert_items_query);
            }

            // Clear the cart after placing the order
            $deleteCart_query = "DELETE FROM cart WHERE user_id = '$userId'";
            $deleteCart_query_run = mysqli_query($conn, $deleteCart_query);


            // Set a success message and redirect to the orders page
            $_SESSION['message'] = "Order placed successfully. Your tracking number is $tracking_no";
            header("Location: ../views/orders.php");
            exit();
        }
    }
}
else{
    // If the user is not authenticated, redirect to the login page
    header("Location: ../login.php");
    exit();
}
?>