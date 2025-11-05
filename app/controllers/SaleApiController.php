<?php
require_once 'app/models/SaleModel.php';
require_once 'app/models/SellerModel.php'; 

class SaleApiController {
    private $model;
    private $modelSeller;
    private $page;
    private $limit;

    function __construct($page = 1, $limit = 3) {
        $this->modelSeller = new SellerModel(); 
        $this->model = new SaleModel();
        // Se inician las propiedades de clase para la paginación
        $this->page = $page;
        $this->limit = $limit;
    }

//api/venta
public function getAllSales($req, $res)
{
    // Parámetros permitidos que acepta la API. Se colocan manualmente como una forma de proteger la API
    $allowedParams = ['sortField', 'sortOrder', 'page', 'limit', 'min_price', 'max_price', 'id_vendedor', 'resource', 'id_venta'];

    // Obtener parámetros de la solicitud
    $queryParams = array_keys($_GET);

    // Verificar si hay parámetros no permitidos
    foreach ($queryParams as $param) {
        if (!in_array($param, $allowedParams)) {
            return $res->json('Parámetro no permitido: ' . $param, 400);
        }
    }
    // Ordenamiento
    $sortFields = ['precio', 'id_vendedor'];

    $userSortField = isset($_GET['sortField']) ? $_GET['sortField'] : null;

    // Validar que sortField sea válido
    if ($userSortField && !in_array($userSortField, $sortFields)) {
        return $res->json('Campo de ordenamiento no permitido: ' . $userSortField, 400);
    }

    // Usar el campo de ordenamiento por defecto si no se proporciona
    $userSortField = $userSortField ?: 'precio';
    // Obtiene el parámetro de orden del usuario (asc o desc) usando $_GET y usa 'asc' por defecto
    $userSortOrder = isset($_GET['sortOrder']) && $_GET['sortOrder'] === 'desc' ? 'desc' : 'asc';

    // Paginación
    $page = isset($_GET['page']) ? (int)$_GET['page'] : $this->page;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : $this->limit;
    
    // Validación de la página
    if ($page < 1) {
        $page = 1; // Fuerza a la primera página si se proporciona un valor menor
    }
    
    // Validación del límite
    if ($limit < 1) {
        $limit = 5; // Establece un límite predeterminado
    }
    
    // Cálculo del offset
    $offset = ($page - 1) * $limit;
    
    // Filtros
    $filters = [];
    $params = [];
    
    // Filtra por precio
    if (isset($_GET['min_price'])) {
        $minPrice = filter_var($_GET['min_price'], FILTER_VALIDATE_FLOAT); //filter_var verifica y sanitiza
        if ($minPrice !== false) {
            $filters[] = "precio >= :min_price"; // :min_price marcador de posicion que será usado después
            $params[':min_price'] = $minPrice; // Agrega parámetro
        }
    }
    
    if (isset($_GET['max_price'])) {
        $maxPrice = filter_var($_GET['max_price'], FILTER_VALIDATE_FLOAT);
        if ($maxPrice !== false) {
            $filters[] = "precio <= :max_price"; // Usa la columna "precio"
            $params[':max_price'] = $maxPrice; // Agrega parámetro
        }
    }
    if (isset($_GET['id_vendedor'])) {
        $idVendedor = filter_var($_GET['id_vendedor'], FILTER_VALIDATE_INT);
        if ($idVendedor === false) {
            return $res->json('ID de vendedor inválido. Debe ser un número entero.', 400);
        }
        $filters[] = "id_vendedor = :id_vendedor";
        $params[':id_vendedor'] = $idVendedor;
    }
    
    // Obtiene ventas con filtros y paginación
    try {
        $sales = $this->model->getAll($userSortField, $userSortOrder, $filters, $limit, $offset, $params);
        $totalSales = $this->model->countSales($filters, $params);
        if ($totalSales < 0) {
            $totalSales = 0; // En caso de que haya un error
        }
    
        // Para cada venta, obtiene el vendedor
        foreach ($sales as &$sale) {
            $seller = $this->modelSeller->getSellerById($sale->id_vendedor);
            $sale->vendedor = $seller ? $seller->nombre : 'Desconocido';
        }
    
        $response = [
            'ventas' => $sales,
            'pagina' => $page,
            'limite' => $limit,
            'total_ventas' => $totalSales,
            'total_paginas' => ceil($totalSales / $limit),
        ];
        $res->setStatusCode(200);
        $res->setBody($response);
        return $res->send();
        } catch (Exception $e) {
            $res->setStatusCode(500);
            $res->setBody(['error' => $e->getMessage()]);
            return $res->send();
        }
    }


    
    //api/venta/:id (GET)
    public function showSale($req, $res)
    {
        $id = $req->params->id_venta;

        // Validar que id_venta no esté vacío y sea un número entero positivo
        if (empty($id) || !is_numeric($id) || $id <= 0) {
            return $res->json('El ID de venta es inválido. Debe ser un número entero positivo.', 400);
        }
        $sale = $this->model->getSaleById($id);

        if (!$sale) {
            return $res->json('Venta no encontrada', 404);
        }

        return $res->json($sale, 200);
    }

    //api/venta(POST)
    public function addSale($req, $res)
    {
        // Validar campos obligatorios
        if (empty($req->body->producto) || empty($req->body->precio) || empty($req->body->id_vendedor) || empty($req->body->fecha)) {
            return $res->json('Todos los campos son obligatorios.', 400);
        }
    
        // Obtener datos del cuerpo
        $producto = $req->body->producto;
        $precio = $req->body->precio;
        $id_vendedor = $req->body->id_vendedor;
        $fecha = $req->body->fecha;
    
        // Validaciones
        if (empty($producto)) {
            return $res->json('El campo "producto" es obligatorio.', 400);
        }
    
        if (!is_numeric($precio) || $precio <= 0) {
            return $res->json('El precio debe ser un número positivo.', 400);
        }
    
        if (!is_numeric($id_vendedor) || $id_vendedor <= 0) {
            return $res->json('El ID del vendedor no es válido.', 400);
        }
    
        if (empty($fecha)) {
            return $res->json('El campo "fecha" es obligatorio.', 400);
        }
    
        // Verificar que el vendedor exista
        if (!$this->modelSeller->getSellerById($id_vendedor)) {
            return $res->json('No hay vendedores disponibles con ese ID.', 404);
        }
    
        // Insertar venta
        $id = $this->model->insert($producto, $precio, $id_vendedor, $fecha);
    
        if (!$id) {
            return $res->json("Error al insertar venta", 500);
        }
    
        // Obtener la venta recién creada
        $sale = $this->model->getSaleById($id);
        return $res->json($sale, 201);
    }
    


    //api/venta/:id (PUT)
    public function updateSale($req, $res)
    {
        $id = $req->params->id_venta;
    
        //  Valida que el id sea valido
        if (empty($id) || !is_numeric($id) || $id <= 0) {
            return $res->json('El ID de venta es inválido. Debe ser un número entero positivo.', 400);
        }
    
        //  Verificar que exista la venta
        $sale = $this->model->getSaleById($id);
        if (!$sale) {
            return $res->json("La venta con el id=$id no existe", 404);
        }
    
        // Valida campos obligatorios
        if (empty($req->body->producto) || empty($req->body->precio) || empty($req->body->fecha)) {
            return $res->json('Todos los campos son obligatorios.', 400);
        }
    
        $producto = $req->body->producto;
        $precio = $req->body->precio;
        $fecha = $req->body->fecha;
    
        // Validar tipos de datos
        if (!is_numeric($precio) || $precio <= 0) {
            return $res->json('El precio debe ser un número positivo.', 400);
        }
    
        // Actualiza la venta
        $updated = $this->model->updateSale($id, $producto, $precio, $fecha);
    
        if ($updated === false) {
            return $res->json("Hubo un problema al actualizar la venta. Intente nuevamente.", 500);
        }
    
        $sale = $this->model->getSaleById($id);
        return $res->json($sale, 200);
    }
    
    
    //api/venta/delete(:id)
    public function deleteSale($req, $res)
    {
        $id = $req->params->id_venta;
        $sale = $this->model->getSaleById($id);
        if (!$sale) {
            return $res->json("La venta con el id=$id no existe", 404);
        }
        $this->model->deleteSale($id);
        $res->json("Venta eliminada", 200);
    }

    

}
