<?php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // Get the requested URI
$method = $_SERVER['REQUEST_METHOD'];

// Helper function for instantiating and calling controllers
function handleController($controllerPath, $controllerClass, $method, $params = null) {
    require_once __DIR__ . "/../controllers/{$controllerPath}.php";
    $controller = new $controllerClass();
    
    if ($params != null) {
        $controller->$method($params);
    } else {
        $controller->$method();
    }
}

switch ($uri) {
    // Client Routes
    case '/':
        require_once __DIR__ . '/../views/client/home.php';
        break;


    case '/products':
        handleController('admin/product_service', 'Product_Service', 'getProducts');
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
        require_once __DIR__ . '/../views/admin/admin.html';
        break;


    case '/admin/dashboard':

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            handleController('admin/dashboard', 'Dashboard', 'index'); 
        } else {
            require_once __DIR__ . '/../views/admin/admin.html';
        }
        break;

    case '/admin/product-service': 

        
        if ($method == 'GET') {    
        // Check if this is an AJAX request
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $data = json_decode(file_get_contents('php://input'), true);
                handleController('admin/product_service', 'Product_Service', 'index');
            } else {
                // If it's a normal browser request, load the admin panel HTML
                require_once __DIR__ . '/../views/admin/admin.html';
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

        $data = json_decode(file_get_contents('php://input'), true);

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            if ($data) {
                handleController('admin/reports_analytics', 'Reports_Analytics', 'getAnalytics', $data);
            } else {
                handleController('admin/reports_analytics', 'Reports_Analytics', 'index');
            }                       
        } else {
            require_once __DIR__ . '/../views/admin/admin.html';
        }
        
        break;


    case '/admin/sales':
  
        
        if ($method == 'GET') {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                handleController('admin/sales', 'Sales', 'index');
            } else {
                require_once __DIR__ . '/../views/admin/admin.html';
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

        if ($method == 'GET') {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                handleController('admin/settings', 'Settings', 'index');
            } else {
                require_once __DIR__ . '/../views/admin/admin.html';
            }
        } else if ($method == 'PUT') {
            $data = json_decode(file_get_contents('php://input'), true);
           if ($data['action'] == 'update_profile') {
            handleController('admin/settings', 'Settings', 'updateProfileDetails', $data);
           } else if($data['action'] == 'update_general_info') {
            handleController('admin/settings', 'Settings', 'updateStoreDetails', $data);
           } else if($data['action'] == 'update_payment_info') {
            handleController('admin/settings', 'Settings', 'updatePaymentDetails', $data);
           }
        } else if ($method == 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            handleController('admin/settings', 'Settings', 'addAdmin', $data);
        }

        break;

    case '/upload-product-image':
        require_once __DIR__ . '/../controllers/admin/upload_product_image.php';
        break;

    // 404 Error for Unknown Routes
    default:
        http_response_code(404);
        echo "404 - Page Not Found";
        break;
}
