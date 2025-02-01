<?php

session_start();
include('F:\xampp\htdocs\MOTO247-Manila\config\db.php'); // Fix: Ensure DB connection

if(isset($_SESSION['authenticated'])) {
    if(isset($_POST['scope'])) {
        
        $scope = $_POST['scope']; // Fix: Use $_POST, not $_SESSION
        switch ($scope) {
            case "add":
                $prod_id = $_POST['prod_id']; // Fix: Correct variable names
                $prod_qty = $_POST['prod_qty']; 
                $user_id = $_SESSION['auth_user']['user_id'];

                // Check if the item is already in the cart
                $check_existing_cart = "SELECT * FROM cart WHERE user_id = '$user_id' AND prod_id = '$prod_id'";
                $check_existing_cart_run = mysqli_query($conn, $check_existing_cart);

                if(mysqli_num_rows($check_existing_cart_run) > 0) {
                    echo "existing";
                } else {
                    // Insert the item into the cart
                    $insert_query = "INSERT INTO cart (user_id, prod_id, prod_qty) VALUES ('$user_id', '$prod_id', '$prod_qty')";
                    $insert_query_run = mysqli_query($conn, $insert_query);

                    if($insert_query_run) {
                        echo 201; // Success
                    } else {
                        echo "SQL Error: " . mysqli_error($conn); // Fix: Show MySQL errors for debugging
                    }
                }
                break;

            default: 
                echo 500; // Invalid scope
        }   
    } else {
        echo "Scope not set"; // Fix: Return error if 'scope' is missing
    }
} else {
    echo 401; // Not authenticated
}

?>
