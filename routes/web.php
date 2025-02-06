<?php

require_once __DIR__ . '/../session/session_manager.php';
require_once __DIR__ . '/../session/auth_session.php';

// Initialize SessionManager and AuthSession
$sessionManager = new SessionManager();
$sessionManager->start();
$authSession = new AuthSession();

// Helper function to handle controller calls
function handleController($controllerPath, $controllerClass, $method, ...$params) {
    require_once __DIR__ . "/../controllers/{$controllerPath}.php";
    $controller = new $controllerClass();
    $controller->$method(...$params);
}

// Helper function to check for AJAX requests
function isAjaxRequest() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); 
$method = $_SERVER['REQUEST_METHOD'];

switch ($uri) {
    // Client Routes
    case '/':
        if ($method == 'GET') {
            isAjaxRequest() ? handleController('client/product_service', 'Product_Service', 'index') : require_once __DIR__ . '/../views/client/index.html';
        }
        break;
    
    case '/check-login-status':
        handleController('authentication/check-login-status', 'Login', 'index', $authSession);
        break;

    case '/products':
        if ($method == 'GET') {
            isAjaxRequest() ? handleController('client/product_service', 'Product_Service', 'getProducts') : require_once __DIR__ . '/../views/client/products.html';
        }
        break;

    case '/services':
        if ($method == 'GET') {
            isAjaxRequest() ? handleController('client/product_service', 'Product_Service', 'getServices') : require_once __DIR__ . '/../views/client/services.html';
        }
        break;

    case '/about':
        if ($method == 'GET') {
            isAjaxRequest() ? handleController('client/product_service', 'Product_Service', 'getServices') : require_once __DIR__ . '/../views/client/about.html';
        }
        break;

    case '/contact':
        if ($method == 'GET') {
            isAjaxRequest() ? handleController('client/product_service', 'Product_Service', 'getServices') : require_once __DIR__ . '/../views/client/contact.html';
        }
        break;

    case '/add-to-cart':
        require_once __DIR__ . '/../views/client/sproduct.html';
        break;

    case '/manage-cart':
        $data = json_decode(file_get_contents('php://input'), true);
        handleController('client/addtocart', 'CartManager', 'index', $data, $authSession, $sessionManager);
        break;

    case '/update-cart-product':
        $data = json_decode(file_get_contents('php://input'), true);
        handleController('client/addtocart', 'CartManager', 'updateCartProduct', $data);
        break;

    case '/cart':
        if ($method == 'GET') {
            if (!$authSession->isLogged()) {
                header('Location: /login');
                exit();
            }
            isAjaxRequest() ? handleController('client/addtocart', 'CartManager', 'getCartItems', $authSession, $sessionManager) : require_once __DIR__ . '/../views/client/cart.html';
        }
        break;
    
    case '/place-order':
        $data = json_decode(file_get_contents('php://input'), true);
        handleController('client/checkout', 'Checkout', 'checkout', $sessionManager, $data);

        break;

    case '/profile':
        if ($method == 'GET') {
            isAjaxRequest() ? handleController('client/profile', 'Profile', 'getProfileInfo', $sessionManager) : require_once __DIR__ . '/../views/client/account.html';
        } elseif ($method == 'PUT') {
            $data = json_decode(file_get_contents('php://input'), true);
            $action = $data['action'] == 'edit_details' ? 'editProfileInfo' : 'editPassword';
            handleController('client/profile', 'Profile', $action, $data, $sessionManager);
        }
        break;

    case '/get-store-info':
        handleController('client/store', 'Store', 'getStoreInfo');
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
        if ($method == 'GET' && isset($_GET['token'])) {
            $token = $_GET['token'];
            handleController('authentication/authentication', 'Authentication', 'verifyEmail', $token);
        }
        break;

    // Admin Routes
    case '/admin':
        require_once __DIR__ . '/../views/admin/admin.php';
        break;

    case '/admin/dashboard':
        if ($method == 'GET') {
            $date = $_GET['date'] ?? '';
            $month = $_GET['month'] ?? '';
            $data = ['date' => $date, 'month' => $month];
            isAjaxRequest() ? handleController('admin/dashboard', 'Dashboard', 'index', $data) : require_once __DIR__ . '/../views/admin/admin.php';
        }
        break;

    case '/admin/product-service':
        if ($method == 'GET') {
            isAjaxRequest() ? handleController('admin/product_service', 'Product_Service', 'index') : require_once __DIR__ . '/../views/admin/admin.php';
        } elseif ($method == 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            handleController('admin/product_service', 'Product_Service', 'addProduct', $data);
        } elseif ($method == 'PUT') {
            $data = json_decode(file_get_contents('php://input'), true);
            handleController('admin/product_service', 'Product_Service', 'editProduct', $data);
        } elseif ($method == 'DELETE') {
            $data = json_decode(file_get_contents('php://input'), true);
            handleController('admin/product_service', 'Product_Service', 'deleteProduct', $data);
        }
        break;

    case '/admin/reports-analytics':
        $aggregation = $_GET['aggregation'] ?? '';
        $data = ['aggregation' => $aggregation];
        isAjaxRequest() ? handleController('admin/reports_analytics', 'Reports_Analytics', 'index', $data) : require_once __DIR__ . '/../views/admin/admin.php';
        break;

    case '/admin/sales':
        if ($method == 'GET') {
            isAjaxRequest() ? handleController('admin/sales', 'Sales', 'index') : require_once __DIR__ . '/../views/admin/admin.php';
        } elseif ($method == 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            handleController('admin/sales', 'Sales', 'addSale', $data);
        } elseif ($method == 'PUT') {
            $data = json_decode(file_get_contents('php://input'), true);
            handleController('admin/sales', 'Sales', 'editOrder', $data);
        } elseif ($method == 'DELETE') {
            $data = json_decode(file_get_contents('php://input'), true);
            handleController('admin/sales', 'Sales', 'deleteSale', $data);
        }
        break;

    case '/admin/settings':
        if ($method == 'GET') {
            isAjaxRequest() ? handleController('admin/settings', 'Settings', 'index', $sessionManager) : require_once __DIR__ . '/../views/admin/admin.php';
        } elseif ($method == 'PUT') {
            $data = json_decode(file_get_contents('php://input'), true);
            $action = $data['action'];
            $actionMap = [
                'update_profile' => 'updateProfileDetails',
                'update_general_info' => 'updateStoreDetails',
                'update_payment_info' => 'updatePaymentDetails'
            ];
            handleController('admin/settings', 'Settings', $actionMap[$action] ?? '', $data, $sessionManager);
        } elseif ($method == 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            handleController('admin/settings', 'Settings', 'addAdmin', $data);
        } elseif ($method == 'DELETE') {
            $data = json_decode(file_get_contents('php://input'), true);
            handleController('admin/settings', 'Settings', 'removeAdmin', $data);
        }
        break;

    case '/admin/backup':
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
