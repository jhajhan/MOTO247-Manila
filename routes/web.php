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

    case '/register':
        require_once __DIR__ . '/../views/client/register.php';
        break;

    case '/products':
        handleController('admin/product_service', 'Product_Service', 'getProducts');
        break;

    // Admin Routes
    case '/admin/dashboard':
        handleController('admin/dashboard', 'Dashboard', 'index');
        break;

    case '/admin/product-service': 
        if ($method == 'GET') {
            $data = json_decode(file_get_contents('php://input'), true);
            handleController('admin/product_service', 'Product_Service', 'index');
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
        handleController('admin/reports_analytics', 'Reports_Analytics', 'index');
        break;

    case '/admin/sales':
        
        if ($method == 'GET') {
            handleController('admin/sales', 'Sales', 'index');
        } else if ($method == 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            handleController('admin/sales', 'Sales', 'addPhysicalSale', $data);
        } else if ($method == 'PUT') {
            $data = json_decode(file_get_contents('php://input'), true);
            handleController('admin/sales', 'Sales', 'updateOrderStatus', $data);
        } else if ($method == 'DELETE') {
            $data = json_decode(file_get_contents('php://input'), true);
            handleController('admin/sales', 'Sales', 'deleteOrder', $data);
        }

        break;

    case '/admin/settings':
        handleController('admin/settings', 'Settings', 'index');
        break;

    // 404 Error for Unknown Routes
    default:
        http_response_code(404);
        echo "404 - Page Not Found";
        break;
}
