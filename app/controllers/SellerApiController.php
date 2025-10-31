<?php
require_once 'app/models/SellerModel.php';

class SellerApiController
{
    public $sellerModel;
    private const MAX_SIZE = 4 * 1024 * 1024; // tamaño maximo de la imagen = 4 MB


    function __construct()
    {
        $this->sellerModel = new SellerModel();
    }

    private function validarDatos($request)
    {
        $error = false;
        // cuando la imagen es demasiado muy grande php rechaza el POST y lo envia vacio
        if (empty($request) && empty($_FILES))
            $error = 'Image size too big';

        // Valida datos obligatorios
        elseif (empty($request->body->nombre) || empty($request->body->telefono) || empty($request->body->email))
            $error = 'Missing required data';

        // Valida el email
        elseif (!filter_var($request->body->email, FILTER_VALIDATE_EMAIL))
            $error = 'Invalid email format';

        return $error;
    }

    private function validarImagen($request, $res)
    {
        if (empty($_FILES['imagen']['tmp_name']) || $_FILES['imagen']['error'] != UPLOAD_ERR_OK)
            return false;

        $mime = mime_content_type($_FILES['imagen']['tmp_name']);

        if (!in_array($mime, ['image/jpeg', 'image/png']))
            return false;

        if ($_FILES['imagen']['size'] > self::MAX_SIZE)
            return false;

        return true;
    }

    public function insert($request, $res)
    {
        $error = $this->validarDatos($request);

        if (!$error):
            // obtiene los datos del body
            $nombre = $request->body->nombre;
            $telefono = $request->body->telefono;
            $email = $request->body->email;
            $img = null;



            /* si viene una imagen, la valida
            if (!empty($_FILES['imagen']['tmp_name'])) {
                $validation = $this->validarImagen($request, $res);
                if (!$validation)
                    return $res->json(['error' => 'Invalid media type', 'message' => 'Only JPG/PNG and size limit allowed'], 400);
            }
            // si paso la sube al sv
            $img = $request->body->imagen;
            */
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

    // sube la img al servidor y devuelve la ruta
    private function uploadImg($img)
    {
        $target = "img/" . uniqid() . "." . strtolower(pathinfo($img['name'], PATHINFO_EXTENSION));
        move_uploaded_file($img['tmp_name'], $target);
        return $target;
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

        // verifico que exista el vendedor
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