<?php
require_once 'app/models/SellerModel.php';

class SellerApiController
{
    public $sellerModel;


    function __construct()
    {
        $this->sellerModel = new SellerModel();
    }

    private function validarDatos($request)
    {
        $error = null;
        // Valida datos obligatorios
        if (empty($request->body->nombre) || empty($request->body->telefono) || empty($request->body->email))
            $error = 'Missing required data';

        // Valida el email
        elseif (!filter_var($request->body->email, FILTER_VALIDATE_EMAIL))
            $error = 'Invalid email format';

        return $error;
    }

    public function insert($request, $res)
    {
        $error = $this->validarDatos($request);

        if (empty($error)):
            // obtiene los datos del body
            $nombre = $request->body->nombre;
            $telefono = $request->body->telefono;
            $email = $request->body->email;

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

    public function update($request, $res)
    {
        $error = $this->validarDatos($request);

        if ($error)
            return $res->json($error, 400);
        
        // obtiene la id del vendedor
        $id = $request->params->id;

        // obtiene los datos del body
        $nombre = $request->body->nombre;
        $telefono = $request->body->telefono;
        $email = $request->body->email;

        // actualiza los datos
        $this->sellerModel->update($id, $nombre, $telefono, $email);
        $seller = $this->sellerModel->getSellerById($id);
        return $res->json($seller, 200);
    }

    function delete($request, $res)
    {
        $id = $request->params->id;
        $seller = $this->sellerModel->getSellerById($id);

        if (!$seller)
            return $res->json(["Seller not found" => "Seller with id=$id doesn't exist in the databse"], 404);

        $this->sellerModel->delete($id);
        return $res->json('Seller deleted', 204);
    }


    public function getAll($req, $res)
    {
        $sellers = $this->sellerModel->getSellers();
        return $res->json($sellers);
    }

    public function get($request, $res)
    {
        $id = $request->params->id;
        if (!$id)
            return $res->json(["Seller ID no specified", 400]);
        $seller = $this->sellerModel->getSellerById($id);

        // verifica que exista el vendedor
        if (!$seller)
            return $res->json(["Seller not found" => "Seller with id=$id doesn't exist in the databse"], 404);

        return $res->json($seller);
    }

    public function methodNotAllowed($request, $res)
    {
        // devolveria 405 si el method no es valido por ej si manda delete a /vendedores
        return $res->json(['message' => 'Method not allowed'], 405);
    }
}