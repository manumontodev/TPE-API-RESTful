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
            return $res->json(['Creado' => $newSeller], 201);
        else:
            // si no valido los datos informa el error
            return $res->json($error, 400);
        endif;
    }

    public function update($id, $req, $res)
    {
        if (!empty($page))
            $page = "?page=$page";
        // $url = BASE_URL . "vendedores/editar/$id$page";
        if (!empty($_GET['from'])) // si viene del perfil de algun vendedor
            // $url = BASE_URL . "vendedor/$id&from=" . $_GET['from'];
            if ($this->validarPost($req, $res)) {
                $nombre = $_POST['nombre'];
                $telefono = $_POST['telefono'];
                $email = $_POST['email'];
                $validation = $this->validarImagen($req, $res);
                $img = null;

                if ($validation)
                    $img = $this->uploadImg($_FILES['imagen']);
            }
        $result = $this->sellerModel->update($id, $nombre, $telefono, $email, $img);

        /*        if ($result)
                    $_SESSION['flash'] = ["success", "bi bi-check-circle-fill me-2", "Operación completada", "Los datos del vendedor se actualizaron correctamente"];
                else 
                    $_SESSION['flash'] = ["warning", "bi bi-x-octagon-fill me-2", "Operación incompleta", "No se registraron cambios"];
                echo "from:" . $_GET['from'];
                die();
                // redirijo
                if (empty($_GET['from'])) 
                    // si no viene del perfil de ningun vendedor, lo mando a la tabla
                    $url = BASE_URL . "vendedores$page";
                else
                    // sino, lo devuelvo al perfil de donde vino
                    $url = BASE_URL . "vendedor/$id"; 
                header("Location: " . $url);
        */
        die();
    }

    function delete($id)
    {
        $success = $this->sellerModel->delete($id);
        /*        if ($success):
                    header("Location: " . BASE_URL . "vendedores");
                    $_SESSION['flash'] = ["success", "bi bi-patch-check-fill me-2", "Operación completada", "El vendedor se ha eliminado correctamente"];

                else:
                    header("Location: " . BASE_URL . "vendedores");
                    $_SESSION['flash'] = ["danger", "bi bi-x-octagon-fill me-2", "Oops! Algo falló", "El vendedor no se pudo eliminar"];
                endif;
        */
    }

    // sube la img al servidor y devuelve la ruta
    private function uploadImg($img)
    {
        $target = "img/" . uniqid() . "." . strtolower(pathinfo($img['name'], PATHINFO_EXTENSION));
        move_uploaded_file($img['tmp_name'], $target);
        return $target;
    }


    public function getSellers($req, $res)
    {
        $sellers = $this->sellerModel->getSellers();
        return $res->json($sellers);
    }

    public function getSeller($request, $res)
    {
        $id = $request->params->id;
        $seller = $this->sellerModel->getSellerById($id);

        // verifico que exista el vendedor
        if ($seller)
            return $res->json($seller);
        else
            return $res->json('Required seller not found', 404);
    }
}