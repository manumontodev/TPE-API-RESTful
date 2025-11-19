<?php
//para chequear errores
// error_reporting(E_ALL);
//ini_set('display_errors', 1);               // No mostrar en pantalla (para producción)
//ini_set('log_errors', 1);
//ini_set('error_log', __DIR__ . '/php-error.log');

require_once 'libs/router/router.php';
require_once 'app/controllers/SellerApiController.php';
require_once 'app/controllers/SaleApiController.php';
require_once 'app/controllers/AuthApiController.php';
require_once 'app/controllers/ErrorApiController.php';
require_once 'libs/jwt/jwt.middleware.php';
require_once 'app/middlewares/GuardApiMiddleware.php';

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
$router->addMiddleware(new JWTMiddleware());

// ruta por defecto
$router->setDefaultRoute('ErrorApiController', 'not_found');

// manejo de metodos no permitidos
require_once './app/config/routes.php';

// Login
$router->addRoute('auth/login',     'GET',     'AuthApiController',    'login');
$router->addRoute('auth/logout',     'GET',     'AuthApiController',    'logout');

//  ventas
$router->addRoute('ventas',         'GET',      'SaleApiController',    'getAllSales');
$router->addRoute('ventas/:id',     'GET',      'SaleApiController',    'showSale');

//  vendedores
$router->addRoute('vendedores',     'GET',   'SellerApiController',    'getAll');
$router->addRoute('vendedores/:id',     'GET',      'SellerApiController',    'get');
$router->addRoute('vendedores/:id/ventas',     'GET',      'SellerApiController',    'getSalesById');


// metodos ABM 
$router->addMiddleware(new GuardApiMiddleware());

$router->addRoute('ventas',     'POST',      'SaleApiController',    'addSale');
$router->addRoute('ventas/:id',     'PUT',      'SaleApiController',    'updateSale');
$router->addRoute('ventas/:id',     'DELETE',      'SaleApiController',    'deleteSale');

$router->addRoute('vendedores',     'POST',   'SellerApiController',    'insert');
$router->addRoute('vendedores/:id',     'PUT',      'SellerApiController',    'update');
$router->addRoute('vendedores/:id',     'DELETE',      'SellerApiController',    'delete');


$router->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);