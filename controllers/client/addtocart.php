<?php

require_once(__DIR__ . '/../../config/db.php');

// Define the CartManager class
class CartManager {
    // Manage cart actions (add, update, delete)
    public function manageCart($data, $authSession, $sessionManager) {
        if ($authSession->isLogged()) {
            if (isset($data['action'])) {
                $action = $data['action']; // Use the action passed to the method

                global $conn;

                switch ($action) {
                    case "add":
                        $prod_id = $data['productId'];
                        $prod_qty = 1;
                        $user_id = $sessionManager->get('user_id'); // Get user ID from the session
                        header('Content-Type: application/json');

                        // Prepare SQL query with prepared statements
                        $query = "SELECT * FROM cart WHERE user_id = ? AND prod_id = ?";
                        $stmt = mysqli_prepare($conn, $query);
                        $stmt->bind_param('ii', $user_id, $prod_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {  // Check if the item exists
                            $response = ["message" => "Item already exists"];
                        } else {
                            // Insert the item into the cart
                            $insert_query = "INSERT INTO cart (user_id, prod_id, prod_qty) VALUES (?, ?, ?)";
                            $stmt = mysqli_prepare($conn, $insert_query);
                            $stmt->bind_param('iii', $user_id, $prod_id, $prod_qty);
                            $stmt->execute();

                            if ($stmt->affected_rows > 0) {
                                $response = ["message" => "Success"];
                            } else {
                                $response = ["message" => "Error"];
                            }
                            $stmt->close();
                        }
                        echo json_encode($response);
                        break;

                    case "update":
                        header('Content-Type: application/json');
                        $response = [];

                        if (isset($data['cart_id']) && isset($data['new_qty'])) {
                            $cart_id = $data['cart_id'];
                            $cart_qty = $data['new_qty'];
                            $user_id = $sessionManager->get('user_id'); // Get user ID from the session

                            // Check if the item is already in the cart
                            $query = "SELECT * FROM cart WHERE user_id = ? AND id = ?";
                            $stmt = mysqli_prepare($conn, $query);
                            $stmt->bind_param('ii', $user_id, $cart_id);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                $update_query = "UPDATE cart SET prod_qty = ? WHERE id = ? AND user_id = ?";
                                $stmt = mysqli_prepare($conn, $update_query);
                                $stmt->bind_param('iii', $cart_qty, $cart_id, $user_id);

                                if ($stmt->execute()) {
                                    $response = ["message" => 'Success'];
                                } else {
                                    $response = ["message" => 'Error updating cart item'];
                                }
                            } else {
                                $response = ["message" => 'Item not found.'];
                            }
                        } else {
                            $response = ["message" => 'Missing cart_id or cart_qty'];
                        }

                        echo json_encode($response);
                        break;

                    case "delete":
                        header('Content-Type: application/json');
                        $response = [];

                        if (isset($data['cart_id'])) {
                            $cart_id = $data['cart_id'];
                            $user_id = $sessionManager->get('user_id'); // Get user ID from the session

                            // Check if the item exists in the cart
                            $query = "SELECT * FROM cart WHERE id = ? AND user_id = ?";
                            $stmt = mysqli_prepare($conn, $query);
                            $stmt->bind_param('ii', $cart_id, $user_id);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                $delete_query = "DELETE FROM cart WHERE id = ?";
                                $stmt = mysqli_prepare($conn, $delete_query);
                                $stmt->bind_param('i', $cart_id);

                                if ($stmt->execute()) {
                                    $response = ['message' => 'Success']; // Success
                                } else {
                                    $response = ['message' => 'Error deleting item'];
                                }
                            } else {
                                $response = ['message' => 'Item not found in cart.'];
                            }
                        } else {
                            $response = ['message' => 'Cart ID not provided.'];
                        }

                        echo json_encode($response);
                        break;

                    default:
                        echo json_encode(['message' => 'Invalid action']); // Return error for invalid action
                        break;
                }
            } else {
                echo json_encode(["message" => "Action not set"]); // Handle missing action
            }
        } else {
            header('Content-Type: application/json');
            $response = ['message' => 'You must log in first', 'notLogged' => true];
            echo json_encode($response); // Not authenticated (User is not logged in)
        }
    }

    // Get cart items for the user
    public function getCartItems($authSession, $sessionManager) {
        global $conn;
        header('Content-Type: application/json');
        ob_clean();  // Clean any existing output
        flush();     // Clear buffer
    
        $response = []; // Initialize response array
    
        if ($authSession->isLogged()) {
            $user_id = $sessionManager->get('user_id'); // Get user ID from session
            
            // Sanitize user ID (Prevent SQL Injection)
            $user_id = mysqli_real_escape_string($conn, $user_id);
    
            // Query to fetch the cart items
            $query = "SELECT c.id, c.prod_id, c.prod_qty, p.name, p.price, p.image 
                      FROM cart c
                      JOIN product p ON c.prod_id = p.prod_id
                      WHERE c.user_id = '$user_id'";
    
            $result = mysqli_query($conn, $query);
            
            // Initialize cart items array
            $cartItems = [];
    
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $cartItems[] = $row;
                }
                $response = ['message' => 'Success', 'cart_items' => $cartItems];
            } else {
                $response = ['message' => 'No items in the cart!', 'cart_items' => []];
            }
        } else {
            $response = ['message' => 'Log-in first', 'cart_items' => []];
        }
    
        echo json_encode($response);
        exit(); // Ensure no further execution
    }

    function updateCartProduct($data) {
        $cart_id = $data['cartId'];
        $is_selected = $data['isSelected'];

        global $conn;
        $query = "UPDATE cart SET is_selected = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        $stmt->bind_param('ii', $cart_id, $is_selected);
        $stmt->execute();
    }

    function index($data, $authSession, $sessionManager) {
        if ($data['action'] == 'getCart') {
            return $this->getCartItems($authSession, $sessionManager);
        } else {
            return $this->manageCart($data, $authSession, $sessionManager);
        }
    }
}

// Example of calling the index function

?>
