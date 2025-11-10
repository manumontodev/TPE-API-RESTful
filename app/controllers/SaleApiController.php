<?php
require_once 'app/models/SaleModel.php';
require_once 'app/models/SellerModel.php'; 

class SaleApiController {
    private $model;
    private $modelSeller;

    function __construct($page = 1, $limit = 3) {
        $this->modelSeller = new SellerModel(); 
        $this->model = new SaleModel();
    }

//api/venta
public function getAllSales($req, $res)
{
    try {
        // Ordenamiento 
        $allowedSortFields = ['id_venta', 'producto', 'precio', 'id_vendedor', 'fecha'];

        // Campo y orden de ordenamiento enviados por el usuario
        $sortField = $_GET['sortField'] ?? 'id_venta';  // Por defecto ordena por id_venta
        $sortOrder = (isset($_GET['sortOrder']) && $_GET['sortOrder'] === 'desc') ? 'desc' : 'asc';

        // Validar campo permitido
        if (!in_array($sortField, $allowedSortFields)) {
            return $res->json("Campo de ordenamiento no permitido: $sortField", 400);
        }

        // Paginacion
        $page = isset($_GET['page']) ? (int)$_GET['page'] : ($this->page ?? 1);
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : ($this->limit ?? 5);

        if ($page < 1) $page = 1;
        if ($limit < 1) $limit = 5;

        $offset = ($page - 1) * $limit;

        // Filtros opcionales 
        $filters = [];
        $params = [];

        if (isset($_GET['min_price'])) {
            $minPrice = filter_var($_GET['min_price'], FILTER_VALIDATE_FLOAT);
            if ($minPrice !== false) {
                $filters[] = "precio >= :min_price";
                $params[':min_price'] = $minPrice;
            }
        }

        if (isset($_GET['max_price'])) {
            $maxPrice = filter_var($_GET['max_price'], FILTER_VALIDATE_FLOAT);
            if ($maxPrice !== false) {
                $filters[] = "precio <= :max_price";
                $params[':max_price'] = $maxPrice;
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

        // consulta al modelo
        $sales = $this->model->getAll($sortField, $sortOrder, $filters, $limit, $offset, $params);
        $totalSales = $this->model->countSales($filters, $params);

        foreach ($sales as &$sale) {
            $seller = $this->modelSeller->getSellerById($sale->id_vendedor);
            $sale->vendedor = $seller ? $seller->nombre : 'Desconocido';
        }

        // resultado
        $response = [
            'ventas' => $sales,
            'pagina' => $page,
            'limite' => $limit,
            'total_ventas' => $totalSales,
            'total_paginas' => ceil($totalSales / $limit), //funcion de php para redondear para arriba
            'ordenado_por' => $sortField,
            'orden' => strtoupper($sortOrder)
        ];

        return $res->json($response, 200);

    } catch (Exception $e) {
        return $res->json(['error' => 'Error interno del servidor.'], 500);
    }
}




    
    //api/venta/:id (GET)
    public function showSale($req, $res)
    {
        $id = $req->params->id; 

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
    public function addSale($req, $res){  
         //valido datos
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($producto)) {
                return $res->json('El campo "inmueble" es obligatorio.', 400);
            }
   
            if (empty($precio)) {
               return $res->json('El campo "precio" es obligatorio.', 400);
           }
   
           if (empty($id_vendedor)) {
               return $res->json('El campo "id_vendedor" es obligatorio.', 400);
           }
   
            if (empty($fecha)) {
                return $res->json('El campo "fecha" es obligatorio.', 400);
            }
   
           // Validar que el precio sea un número positivo
           if (!is_numeric($precio) || $precio <= 0) {
               return $res->json('El precio debe ser un número positivo.', 400);
           }
   
           // Validar que el ID del vendedor sea un número positivo
           if (!is_numeric($id_vendedor) || $id_vendedor <= 0) {
               return $res->json('El ID del vendedor no es válido.', 400);
           }
   
           // Verifica si el vendedor existe
           if (!$this->modelSeller->getSellerById($id_vendedor)) {
               return $res->json('No hay vendedores disponibles con ese ID.', 404);
           }

        }
         //obtengo datos
         $producto = $req->body->producto;
         $precio = $req->body->precio;
         $id_vendedor = $req->body->id_vendedor;
         $fecha = $req->body->fecha;

        //inserto datos
        $id = $this->model->insert($producto, $precio, $id_vendedor, $fecha);

        if (!$id) {
            return $res->json("Error al insertar venta", 500);
        }
    
        $sale = $this->model->getSaleById($id);
        return $res->json($sale, 201);
    }


    //api/venta/:id (PUT)
    public function updateSale($req, $res)
    {
        $id = $req->params->id_venta;
        // verifico que exista
        $sale = $this->model->getSaleById($id);

        if (!$sale) {
            return $res->json("La venta con el id=$id no existe", 404);
        }

        // Validación de campos vacíos
        if (empty($req->body->producto) || empty($req->body->precio) || empty($req->body->fecha) ) {
            return $res->json('Todos los campos son obligatorios.', 400);
        }

        $producto = $req->body->producto;
        $precio = $req->body->precio;
        $fecha = $req->body->fecha;


        // Actualiza la venta
        $updated = $this->model->updateSale($id, $producto, $precio, $fecha);

        // Verificar si la actualización fue exitosa
        if ($updated === false) {
            return $res->json("Hubo un problema al actualizar la venta. Intente nuevamente.", 500);
        }
        // obtengo la venta modificada y la devuelvo en la respuesta
        $sale = $this->model->getSaleById($id);
        $res->json($sale, 200);
    }
    
    //api/venta/delete(:id)
    public function deleteSale($req, $res)
    {
        $id = $req->params->id_venta;
        $sale = $this->model->getSaleById($id);
        if (!$sale) {
            return $res->json("El producto con el id=$id no existe", 404);
        }
        $this->model->deleteSale($id);
        $res->json("Venta eliminada", 200);
    }

    

}
