<?php

session_start();
include('F:\xampp\htdocs\MOTO247-Manila\config\db.php'); // Fix: Ensure DB connection

if(isset($_SESSION['authenticated'])) {
    if(isset($_POST['scope'])) {
        
        $scope = $_POST['scope']; // Fix: Use $_POST, not $_SESSION
        switch ($scope) {
            case "add":
                if (isset($_POST['prod_id']) && isset($_POST['prod_qty'])) {
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
            }
                break;
            case "update":
                    if (isset($_POST['prod_id']) && isset($_POST['prod_qty'])) {
                        $prod_id = $_POST['prod_id'];
                        $prod_qty = $_POST['prod_qty'];
                        $user_id = $_SESSION['auth_user']['user_id'];
                
                        // Debugging: Check received data
                        error_log("Update Request - Product ID: $prod_id, Quantity: $prod_qty, User ID: $user_id");
                
                        // Check if the item is already in the cart
                        $check_existing_cart = "SELECT * FROM cart WHERE user_id = '$user_id' AND prod_id = '$prod_id'";
                        $check_existing_cart_run = mysqli_query($conn, $check_existing_cart);
                
                        if (mysqli_num_rows($check_existing_cart_run) > 0) {
                            $update_query = "UPDATE cart SET prod_qty = '$prod_qty' WHERE prod_id = '$prod_id' AND user_id = '$user_id'";
                            $update_query_run = mysqli_query($conn, $update_query);
                
                            // if ($update_query_run) {
                            //     // echo 200; // Success
                            // } else {
                            //     echo "SQL Error: " . mysqli_error($conn); // Debugging SQL Errors
                            // }
                        } else {
                            echo "Item not found in cart"; // Fix: Handle case where item is not found
                        }
                    } else {
                        echo "Missing prod_id or prod_qty"; // Handle missing data
                    }
                    break;
                    case "delete":
                        if (isset($_POST['cart_id'])) {
                            $cart_id = $_POST['cart_id'];
                          
                            $user_id = $_SESSION['auth_user']['user_id'];
                    
                            // Debugging: Check received data
                            error_log("Update Request - Cart ID: $cart_id, User ID: $user_id");
                    
                            // Check if the item is already in the cart
                            $check_existing_cart = "SELECT * FROM cart WHERE id = '$cart_id' AND user_id = '$user_id'";
                            $check_existing_cart_run = mysqli_query($conn, $check_existing_cart);
                    
                            if (mysqli_num_rows($check_existing_cart_run) > 0) {
                                $delete_query = "DELETE FROM cart WHERE id = $cart_id";
                                $delete_query_run = mysqli_query($conn, $delete_query);
                    
                                if ($delete_query_run) {
                                    echo 200; // Success
                                } else {
                                    echo "SQL Error: " . mysqli_error($conn); // Debugging SQL Errors
                                }
                            } else {
                                echo "Item not found in cart"; // Handle case where item is not found
                            }
                        } else {
                            echo "Cart ID not provided"; // Handle case where cart_id is not provided
                        }
                        break;
                    
                    default: 
                        echo 500;
                        break;
        
        }
    } else {
        echo "Scope not set"; // Fix: Return error if 'scope' is missing
    }
    } 
    else {
    echo 401; // Not authenticated
}

?>
