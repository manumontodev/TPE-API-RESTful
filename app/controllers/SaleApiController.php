<?php
require_once 'app/models/SaleModel.php';
require_once 'app/models/SellerModel.php';


class SaleApiController
{
    private $model;
    private $modelSeller;

    public function __construct()
    {

        $this->modelSeller = new SellerModel();
        $this->model = new SaleModel();
    }

    // api/ventas (GET)

    public function getAllSales($req, $res)
    {
        try {
            // opciones para ordenamiento
            $allowedSortFields = ['id_venta', 'producto', 'precio', 'id_vendedor', 'fecha'];

            $sortField = strtolower($req->query->sortField ?? 'id_venta');
            $sortOrder = strtolower($req->query->sortOrder ?? 'asc');

            // validar orden asc/desc
            $sortOrder = $sortOrder === 'desc' ? 'desc' : 'asc';

            // validar campo permitido
            if (!in_array($sortField, $allowedSortFields)) {
                return $res->json("Campo de ordenamiento no permitido: $sortField", 400);
            }

            // Validar page
            $page = filter_var($req->query->page ?? null, FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 1]
            ]);

            // si no es válido -> valor por defecto = 1
            $page = $page !== false ? $page : 1;


            // Validar limit
            $limit = filter_var($req->query->limit ?? null, FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 1]
            ]);

            // Si no es válido, valor por defecto = 5
            $limit = $limit !== false ? $limit : 5;


            // Calcular offset
            $offset = ($page - 1) * $limit;

            // Filtros opcionales
            $filters = [];
            $params = [];

            if (isset($req->query->min_price)) {
                $minPrice = filter_var($req->query->min_price, FILTER_VALIDATE_FLOAT);
                if ($minPrice !== false) {
                    $filters[] = "precio >= :min_price";
                    $params[':min_price'] = $minPrice;
                }
            }

            if (isset($req->query->max_price)) {
                $maxPrice = filter_var($req->query->max_price, FILTER_VALIDATE_FLOAT);
                if ($maxPrice !== false) {
                    $filters[] = "precio <= :max_price";
                    $params[':max_price'] = $maxPrice;
                }
            }

            if (isset($req->query->id_vendedor)) {
                $idVendedor = filter_var($req->query->id_vendedor, FILTER_VALIDATE_INT);
                if ($idVendedor === false) {
                    return $res->json('ID de vendedor inválido. Debe ser un número entero.', 400);
                }
                $filters[] = "id_vendedor = :id_vendedor";
                $params[':id_vendedor'] = $idVendedor;
            }

            // Consulta al modelo
            $sales = $this->model->getAll($sortField, $sortOrder, $filters, $limit, $offset, $params);
            $totalSales = $this->model->countSales($filters, $params);

            // Agregar información del vendedor
            foreach ($sales as &$sale) {
                $seller = $this->modelSeller->getSellerById($sale->id_vendedor);
                $sale->vendedor = $seller ? $seller->nombre : 'Desconocido';
            }

            // Respuesta final
            $response = [
                'ventas' => $sales,
                'pagina' => $page,
                'limite' => $limit,
                'total_ventas' => $totalSales,
                'total_paginas' => ceil($totalSales / $limit),
                'ordenado_por' => $sortField,
                'orden' => strtoupper($sortOrder)
            ];

            return $res->json($response, 200);

        } catch (Exception $e) {
            return $res->json(['error' => 'Error interno del servidor.'], 500);
        }
    }


    // api/ventas/:id (GET)
    public function showSale($req, $res)
    {
        $id = $req->params->id;

        // Validar ID
        if (empty($id) || !is_numeric($id) || $id <= 0) {
            return $res->json('El ID de venta es inválido. Debe ser un número entero positivo.', 400);
        }

        // Buscar venta
        $sale = $this->model->getSaleById($id);

        if (!$sale) {
            return $res->json("Venta con id= $id no existe", 404);
        }

        return $res->json($sale, 200);
    }


    // api/ventas (POST)
    public function addSale($req, $res)
    {

        // Obtengo datos del body ANTES de validar
        $producto = $req->body->producto ?? null;
        $precio = $req->body->precio ?? null;
        $id_vendedor = $req->body->id_vendedor ?? null;
        $fecha = $req->body->fecha ?? null;

        // Valido campos requeridos
        if (empty($producto)) {
            return $res->json(['El campo "producto" es obligatorio.'], 400);
        }

        if (empty($precio)) {
            return $res->json(['El campo "precio" es obligatorio.'], 400);
        }

        if (empty($id_vendedor)) {
            return $res->json(['El campo "id_vendedor" es obligatorio.'], 400);
        }

        if (empty($fecha)) {
            return $res->json(['El campo "fecha" es obligatorio.'], 400);
        }

        if (!is_numeric($precio) || $precio <= 0) {
            return $res->json(['El precio debe ser un número positivo.'], 400);
        }

        if (!is_numeric($id_vendedor) || $id_vendedor <= 0) {
            return $res->json(['El ID del vendedor no es válido.'], 400);
        }

        if (!$this->modelSeller->getSellerById($id_vendedor)) {
            return $res->json(['No hay vendedores disponibles con ese ID.'], 404);
        }

        $id = $this->model->insert($producto, $precio, $id_vendedor, $fecha);

        // Si falla inserción
        if (!$id) {
            return $res->json(['Error al insertar venta'], 500);
        }

        $sale = $this->model->getSaleById($id);
        return $res->json($sale, 201);
    }




    // api/ventas/:id (PUT)
    public function updateSale($req, $res)
    {
        $id = $req->params->id;

        // Valido ID
        if (!is_numeric($id) || $id <= 0) {
            return $res->json("El ID es inválido.", 400);
        }

        // Verifico que existe la venta
        $sale = $this->model->getSaleById($id);

        if (!$sale) {
            return $res->json("La venta con el id=$id no existe", 404);
        }

        // Validación de campos vacíos
        if (empty($req->body->producto) || empty($req->body->precio) || empty($req->body->fecha)) {
            return $res->json('Todos los campos son obligatorios.', 400);
        }

        $producto = $req->body->producto;
        $precio = $req->body->precio;
        $fecha = $req->body->fecha;

        if (!is_numeric($precio) || $precio <= 0) {
            return $res->json('El precio debe ser un número positivo.', 400);
        }

        $updated = $this->model->updateSale($id, $producto, $precio, $fecha);

        if ($updated === false) {
            return $res->json("Hubo un problema al actualizar la venta. Intente nuevamente.", 500);
        }

        $sale = $this->model->getSaleById($id);
        return $res->json($sale, 200);
    }


    // api/ventas/:id (DELETE)
    public function deleteSale($req, $res){
        $id = $req->params->id;

        if (!is_numeric($id) || $id <= 0) {
            return $res->json("El ID es inválido.", 400);
        }

        $sale = $this->model->getSaleById($id);
        if (!$sale) {
            return $res->json("La venta con el id=$id no existe", 404);
        }

        $this->model->deleteSale($id);

        return $res->json("Venta eliminada", 200);
    }


}