<?php
require_once './libs/router/router.php';
require_once './app/controllers/SellerApiController.php';
require_once './app/controllers/SaleApiController.php';

/* para usar mas tarde con autenticacion:

require_once './app/controllers/AuthController.php';
require_once './libs/jwt/jwt.middleware.php';
require_once './app/middlewares/guard-api.middleware.php';
$router->addMiddleware(new JWTMiddleware());
$router->addMiddleware(new GuardMiddleware());

*/

// Permitir solicitudes desde cualquier origen (solo para probar frontend)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Manejar request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}



// nota mental chequear que todas los endpoints sean RESTful
$router = new Router();

// Login
$router->addRoute('auth/login',     'GET',     'AuthApiController',    'login');
$router->addRoute('auth/logout',     'GET',     'AuthApiController',    'logout');

// ventas
$router->addRoute('ventas',         'GET',      'SaleApiController',    'showSales');
$router->addRoute('ventas',     'POST',      'SaleApiController',    'addSale');
$router->addRoute('ventas/:id',     'GET',      'SaleApiController',    'showSaleDetail');
$router->addRoute('ventas/:id',     'PUT',      'SaleApiController',    'updateSale');
$router->addRoute('ventas/:id',     'DELETE',      'SaleApiController',    'deleteSale');


// vendedores
$router->addRoute('vendedores',     'GET',   'SellerApiController',    'getAll');
$router->addRoute('vendedores',     'POST',   'SellerApiController',    'insert');
$router->addRoute('vendedores/:id',     'GET',      'SellerApiController',    'get');
$router->addRoute('vendedores/:id',     'PUT',      'SellerApiController',    'update');
$router->addRoute('vendedores/:id',     'DELETE',      'SellerApiController',    'delete');


$router->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);