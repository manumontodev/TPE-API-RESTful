<?php
require_once 'app/models/SellerModel.php';
require_once 'app/models/SaleModel.php';

class SellerApiController
{
    public $sellerModel;
    public $saleModel;


    function __construct()
    {
        $this->sellerModel = new SellerModel();
    }

    private function validarDatos($req)
    {
        $error = false;
        // Valida datos obligatorios
        if (empty($req->body->nombre) || empty($req->body->telefono) || empty($req->body->email))
            $error = 'Missing required data';

        // Valida el email
        elseif (!filter_var($req->body->email, FILTER_VALIDATE_EMAIL))
            $error = 'Invalid email format';

        return $error;
    }

    public function insert($req, $res)
    {
        $error = $this->validarDatos($req);

        if (!$error):
            // obtiene los datos del body
            $nombre = $req->body->nombre;
            $telefono = $req->body->telefono;
            $email = $req->body->email;

            // inserta los datos
            $newId = $this->sellerModel->insert($nombre, $telefono, $email);

            // si fallo devuelve 500
            if (!$newId)
                return $res->json(['Algo fallo' => 'Error en el servidor'], 500);

            // devuelve datos insertados
            $newSeller = $this->sellerModel->getSellerById($newId);
            return $res->json($newSeller, 201);
        else:
            // si no valido los datos informa el error
            return $res->json($error, 400);
        endif;
    }

    public function update($req, $res)
    {
        $error = $this->validarDatos($req);

        if ($error)
            return $res->json($error, 400);

        // obtiene la id del vendedor
        $id = $req->params->id;

        // obtiene los datos del body
        $nombre = $req->body->nombre;
        $telefono = $req->body->telefono;
        $email = $req->body->email;

        // actualiza los datos
        $this->sellerModel->update($id, $nombre, $telefono, $email);
        $seller = $this->sellerModel->getSellerById($id);
        return $res->json($seller, 200);
    }

    function delete($req, $res)
    {
        $id = $req->params->id;
        $seller = $this->sellerModel->getSellerById($id);

        if (!$seller)
            return $res->json(["Seller not found" => "Seller with id=$id doesn't exist in the databse"], 404);

        $this->sellerModel->delete($id);
        return $res->json('Seller deleted', 204);
    }


    public function getAll($req, $res)
    {
        // toma los query params
        $sortBy = $req->query->sortBy ?? ''; // si vienen vacios asigno valores default
        $order = $req->query->order ?? '1';

        // todos los posibles ordenamientos x campo de la tabla
        $sortBy = match ($sortBy) { // match un switch que no lleva breaks/returns
            'name' => 'nombre',
            'email' => 'email',
            'phone' => 'telefono',
            default => '' // si llega otra cosa queda string vacío
        };

        $order = match ($order) {
            '0' => 'DESC',
            '1' => 'ASC',
            default => 'ASC' // si llega otra cosa ordena ASC
        };

        $sellers = $this->sellerModel->getSellers($sortBy, $order);
        return $res->json($sellers);
    }


    public function get($req, $res)
    {
        $id = $req->params->id;
        if (!$id)
            return $res->json(["Seller ID no specified", 400]);
        $seller = $this->sellerModel->getSellerById($id);

        // verifica que exista el vendedor
        if (!$seller)
            return $res->json(["Seller not found" => "Seller with id=$id doesn't exist in the databse"], 404);

        return $res->json($seller);
    }

    public function getSales($req, $res)
    {
        $id = $req->params->id;
        $seller = $this->sellerModel->getSellerById($id);
        // verifica que exista el vendedor
        if (!$seller)
            return $res->json(["Seller not found" => "Seller with id=$id doesn't exist in the databse"], 404);

        $this->saleModel = new SaleModel();

        $sales = $this->saleModel->getSalesBySellerId($id);
        return $res->json($sales);


    }

    public function methodNotAllowed($req, $res)
    {
        // devolveria 405 si el method no es valido por ej si manda delete a /vendedores
        return $res->json(['message' => 'Method not allowed'], 405);
    }
}