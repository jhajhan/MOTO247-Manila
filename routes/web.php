<?php

require_once __DIR__ . '/../session/session_manager.php';
require_once __DIR__ . '/../session/auth_session.php';

// Initialize SessionManager to start the session
$sessionManager = new SessionManager();
$sessionManager->start();

// Initialize AuthSession for checking user roles (e.g., Admin)
$authSession = new AuthSession();

// Restrict Admin Access
function restrictAdminAccess($authSession) {
    if (!$authSession->isAdmin()) {
        header("Location: /"); // Redirect non-admins to the homepage (or login)
        exit();
    }
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // Get the requested URI
$method = $_SERVER['REQUEST_METHOD'];

// Helper function for instantiating and calling controllers
function handleController($controllerPath, $controllerClass, $method, ...$params) {
    require_once __DIR__ . "/../controllers/{$controllerPath}.php";
    $controller = new $controllerClass();

    if (!empty($params)) {
        $controller->$method(...$params);
    } else {
        $controller->$method();
    }
}


switch ($uri) {
    // Client Routes
    case '/':
        if ($method == 'GET') {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                handleController('client/product_service', 'Product_Service', 'index');
            } else { 
                require_once __DIR__ . '/../views/client/index.html';
            }
        }
        break;
    


    case '/products':
        handleController('client/product_service', 'Product_Service', 'getProducts');
        break;

    case '/services':
        handleController('client/product_service', 'Product_Service', 'getServices');
        break;

    case '/add-to-cart':
        require_once __DIR__ . '/../views/client/sproduct.html';

    case '/manage-cart':
        $data = json_decode(file_get_contents('php://input'), true);
        handleController('client/addtocart', 'CartManager', 'index', $data, $authSession, $sessionManager);
        break;

    case '/update-cart-product':
        $data = json_decode(file_get_contents('php://input'), true);
        handleController('client/addtocart', 'CartManager', 'updateCartProduct', $data);

    case '/cart':

        if ($method == 'GET') {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                handleController('client/addtocart', 'CartManager', 'getCartItems', $authSession, $sessionManager);
        } }

    case '/profile':
        if ($method == 'GET') {

        } else if ($method == 'PUT') {
            $data = json_decode(file_get_contents('php://input'), true);

            if ($data['action'] == 'edit_details') {
                handleController('client/profile', 'Profile', 'editProfileInfo', $data, $sessionManager);
            } else {
                handleController('client/profile', 'Profile', 'editPassword', $data, $sessionManager);
            }
            
        }

        break;


    // Authentication Routes

    case '/register':
        if ($method == 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            handleController('authentication/authentication', 'Authentication', 'register', $data);
        }
        break;

    case '/login':
        if ($method == 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            handleController('authentication/authentication', 'Authentication', 'login', $data);
        } else {
            require_once __DIR__ . '/../views/client/account.html';
        }
        break;

    case '/logout':
        handleController('authentication/authentication', 'Authentication', 'logout');
        break;

    case '/verify-email':
        if ($method == 'GET') {
            if (isset($_GET['token'])) {
                $token = $_GET['token'];
                handleController('authentication/authentication', 'Authentication', 'verifyEmail', $token);
            }
        }

        break;

    // Admin Routes

    case '/admin':
        // restrictAdminAccess($authSession);
        require_once __DIR__ . '/../views/admin/admin.php';
        break;


    case '/admin/dashboard':
        // restrictAdminAccess($authSession);
        if ($method == 'GET') {
            // Check if the request is an AJAX request
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                // Get the date and month from the query parameters
                $date = isset($_GET['date']) ? $_GET['date'] : '';
                $month = isset($_GET['month']) ? $_GET['month'] : '';
    
                // Prepare the data for the response
                $data = [
                    'date' => $date,
                    'month' => $month,
                ];
    
                // Handle the controller logic
                handleController('admin/dashboard', 'Dashboard', 'index', $data);
            } else {
                // If it's not an AJAX request, load the HTML view
                require_once __DIR__ . '/../views/admin/admin.php';
            }
        }
        break;
        
    case '/admin/product-service': 
 
        // restrictAdminAccess($authSession);
        if ($method == 'GET') {    
        // Check if this is an AJAX request
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $data = json_decode(file_get_contents('php://input'), true);
                handleController('admin/product_service', 'Product_Service', 'index');
            } else {
                // If it's a normal browser request, load the admin panel HTML
                require_once __DIR__ . '/../views/admin/admin.php';
            }
           
        } else if ($method == 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            handleController('admin/product_service', 'Product_Service', 'addProduct', $data);
        } else if ($method == 'PUT') {
            $data = json_decode(file_get_contents('php://input'), true);
            handleController('admin/product_service', 'Product_Service', 'editProduct', $data);
        } else if ($method == 'DELETE') {
            $data = json_decode(file_get_contents('php://input'), true);
            handleController('admin/product_service', 'Product_Service', 'deleteProduct', $data);
        }
        
        break;


    case '/admin/reports-analytics':

        // restrictAdminAccess($authSession);
        $aggregation = isset($_GET['aggregation']) ? $_GET['aggregation'] : '';
                
                // Prepare the data for the response
                $data = [
                    'aggregation' => $aggregation
                ];

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            if ($data) {
                handleController('admin/reports_analytics', 'Reports_Analytics', 'index', $data);
            } else {
                handleController('admin/reports_analytics', 'Reports_Analytics', 'index');
            }                       
        } else {
            require_once __DIR__ . '/../views/admin/admin.php';
        }
        
        break;


    case '/admin/sales':
  
        // restrictAdminAccess($authSession);
        if ($method == 'GET') {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                handleController('admin/sales', 'Sales', 'index');
            } else {
                require_once __DIR__ . '/../views/admin/admin.php';
            }
            
        } else if ($method == 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            handleController('admin/sales', 'Sales', 'addSale', $data);
        } else if ($method == 'PUT') {
            $data = json_decode(file_get_contents('php://input'), true);
            handleController('admin/sales', 'Sales', 'editOrder', $data);
        } else if ($method == 'DELETE') {
            $data = json_decode(file_get_contents('php://input'), true);
            handleController('admin/sales', 'Sales', 'deleteSale', $data);
        }

        break;

    case '/admin/settings':

        // restrictAdminAccess($authSession);
        if ($method == 'GET') {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                handleController('admin/settings', 'Settings', 'index', $sessionManager);
            } else {
                require_once __DIR__ . '/../views/admin/admin.php';
            }
        } else if ($method == 'PUT') {
            $data = json_decode(file_get_contents('php://input'), true);
           if ($data['action'] == 'update_profile') {
            handleController('admin/settings', 'Settings', 'updateProfileDetails', $data, $sessionManager);
           } else if($data['action'] == 'update_general_info') {
            handleController('admin/settings', 'Settings', 'updateStoreDetails', $data);
           } else if($data['action'] == 'update_payment_info') {
            handleController('admin/settings', 'Settings', 'updatePaymentDetails', $data);
           }
        } else if ($method == 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            handleController('admin/settings', 'Settings', 'addAdmin', $data);
        } else if ($method == 'DELETE') {
            $data = json_decode(file_get_contents('php://input'), true);
            handleController('admin/settings', 'Settings', 'removeAdmin', $data);
        }

        break;

    case '/admin/backup':
        // restrictAdminAccess($authSession);
        require_once __DIR__ . '/../config/backup.php';
        break;

    case '/upload-image':
        require_once __DIR__ . '/../controllers/admin/upload_product_image.php';
        break;

    case '/generate-report':
        require_once __DIR__ . '/../controllers/admin/generate_report.php';
        break;

    // 404 Error for Unknown Routes
    default:
        http_response_code(404);
        echo "404 - Page Not Found";
        break;
}
