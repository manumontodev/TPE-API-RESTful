<?php
require_once './libs/jwt/jwt.middleware.php';
require_once './libs/router/router.php';
require_once './app/middlewares/GuardMiddleware.php';
require_once './app/controllers/AuthController.php';
require_once './app/controllers/SellerController.php';
require_once './app/controllers/SaleController.php';

// nota mental chequear que todas los endpoints sean RESTful

$router = new Router();
// $router->addMiddleware(new GuardMiddleware());

$router->addMiddleware(new JWTMiddleware());

// Login
$router->addRoute('auth/login',     'GET',     'AuthApiController',    'login');
$router->addRoute('auth/logout',     'GET',     'AuthApiController',    'logout');

// ventas
$router->addRoute('ventas/',         'GET',      'SaleApiController',    'showSales');
$router->addRoute('ventas/',     'POST',      'SaleApiController',    'addSale');
$router->addRoute('ventas/:id',     'GET',      'SaleApiController',    'showSaleDetail');
$router->addRoute('ventas/:id',     'PUT',      'SaleApiController',    'updateSale');
$router->addRoute('ventas/:id',     'DELETE',      'SaleApiController',    'deleteSale');


// vendedores
$router->addRoute('vendedores/',     'GET',   'SellerApiController',    'showSellers');
$router->addRoute('vendedores/',     'POST',   'SellerApiController',    'insert');
$router->addRoute('vendedores/:id',     'GET',      'SellerApiController',    'showSeller');
$router->addRoute('vendedores/:id',     'PUT',      'SellerApiController',    'update');
$router->addRoute('vendedores/:id',     'DELETE',      'SellerApiController',    'delete');

$router->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);