<?php
// endpoints con metodos no permitidos
$router->addRoute('vendedores',     'PUT',   'ErrorApiController',    'not_allowed');
$router->addRoute('vendedores',     'DELETE',   'ErrorApiController',    'not_allowed');
$router->addRoute('vendedores/:id',     'POST',      'ErrorApiController',    'not_allowed');
$router->addRoute('vendedores/:id/ventas',     'PUT',   'ErrorApiController',    'not_allowed');
$router->addRoute('vendedores/:id/ventas',     'DELETE',   'ErrorApiController',    'not_allowed');
$router->addRoute('vendedores/:id/ventas',     'POST',      'ErrorApiController',    'not_allowed');