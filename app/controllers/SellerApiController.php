<?php
require_once 'app/models/SellerModel.php';

class SellerApiController
{
    public $sellerModel;
    public $sellerView;
    private const MAX_SIZE = 4 * 1024 * 1024; // tamaño maximo de la imagen = 4 MB


    function __construct()
    {
        $this->sellerModel = new SellerModel();
    }

    // Valida que los datos del POST no esten vacíos
    private function validarPost()
    {
        // si se intenta agregar un nuevo vendedor sin datos
        if (empty($_POST) && empty($_FILES)) {
            // $_SESSION['flash'] = $flashSize;
            // header("Location: " . $urlRedirect);
            die();
        }
        // Si algun campo está vacio 
        if (empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['email'])) {
            // $_SESSION['flash'] = $flashRequired;
            // header("Location: " . $urlRedirect);
            die();
        }
        // Valida el formato de email
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            // $_SESSION['flash'] = $flashEmail;
            // header("Location: " . $urlRedirect);
            die();
        }
        return true;
    }

    private function validarImagen()
    {
        /*        if ($urlRedirect == BASE_URL . "vendedor/nuevo"):
                    $flashWarningPng = "Solo se permiten archivo de imagen en formato .png, .jpg o .jpeg";
                    $flashWarningSize = "El archivo de imagen debe pesar menos de 4 megabytes";
                else:
                    $flashWarningPng = ["warning", "bi bi-exclamation-triangle-fill me-2", "No se pudo completar", "Solo se permiten imágenes .jpeg, .jpg o .png menores a 4 MB"];
                    $flashWarningSize = ["warning", "bi bi-exclamation-triangle-fill me-2", "No se pudo completar", "El archivo de imagen debe pesar menos de 4 megabytes"];
                endif;
        */
        if (empty($_FILES['imagen']['tmp_name']) || $_FILES['imagen']['error'] != UPLOAD_ERR_OK) {
            // si no hay imagen
            return false;
        }
        $mime = mime_content_type($_FILES['imagen']['tmp_name']);
        if (!in_array($mime, ['image/jpeg', 'image/png'])) {
            // $_SESSION['flash'] = $flashWarningPng;
            // header("Location: " . $urlRedirect);
            die();
        }
        if ($_FILES['imagen']['size'] > self::MAX_SIZE) {
            // $_SESSION['flash'] = $flashWarningSize;
            // header("Location: " . $urlRedirect);
            die();
        }
        return true;
    }

    public function insert($request, $res)
    {
        // si los datos estan vacios
        if (!$request->validarDatos())
            return $request->json(['Algo fallo' => 'Faltan datos obligatorios'], 400);

        // obtiene los datos del body
        $nombre = $request->body->nombre;
        $telefono = $request->body->telefono;
        $email = $request->body->email;
        $imgToUpload = $this->validarImagen();
        $img = null;

        // si la img paso la validacion
        if ($imgToUpload)
            $img = $request->body->imagen;

        $newSeller = $this->sellerModel->insert($nombre, $telefono, $email, $img);
        // si algo fallo devuelve 500
        if (!$newSeller)
            return $res->json(['Algo fallo' => 'Error del servidor'], 500);
        // devuelve el elemento creado
        return $res->json([$newSeller => 'Creado'], 201);
    }

    public function update($id, $page = null)
    {
        if (!empty($page))
            $page = "?page=$page";
        // $url = BASE_URL . "vendedores/editar/$id$page";
        if (!empty($_GET['from'])) // si viene del perfil de algun vendedor
            // $url = BASE_URL . "vendedor/$id&from=" . $_GET['from'];
            if ($this->validarPost()) {
                $nombre = $_POST['nombre'];
                $telefono = $_POST['telefono'];
                $email = $_POST['email'];
                $imgToUpload = $this->validarImagen();
                $img = null;

                if ($imgToUpload)
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

    public function showNewSellerForm($error = null, $request)
    {
        /*        $msg = null;
                if (isset($_SESSION['flash'])) {
                    $msg = $_SESSION['flash'];
                    unset($_SESSION['flash']);
                }
                $this->sellerView->showFormAddSeller($msg, $request->user);
        */
    }

    public function showSellerEditMenu($request, $sellerId)
    {
        /*        $sellers = $this->sellerModel->getSellers();
                $paginacion = $this->paginar($sellers);
                if (!empty($_GET['from'])) {
                    $paginacion['from'] = "&from=" . $_GET['from'];
                }

                if (isset($_SESSION['flash'])) {
                    $msg = $_SESSION['flash'];
                    unset($_SESSION['flash']);
                    $this->sellerView->showEditMenu($sellerId, $sellers, $request->user, $paginacion, $msg);
                    return;
                }
                if ($request->user):
                    $this->sellerView->showEditMenu($sellerId, $sellers, $request->user, $paginacion);
                else:
                    $this->sellerView->showErrorMsg();
                endif;
        */
    }

    public function showSeller($sellerId, $request)
    {
        /*        $seller = $this->sellerModel->getSellerById($sellerId);
                $paginacion = $this->paginar($seller);

                $msg = null;
                if (isset($_SESSION['flash'])):
                    $msg = $_SESSION['flash'];
                    unset($_SESSION['flash']);
                endif;
                // verifico que exista el vendedor
                if ($seller) {
                    // instancio el modelo de ventas para obtener las ventas del vendedor
                    $saleModel = new SaleModel();
                    $sales = $saleModel->getSalesById($sellerId);
                    $totalVentas = count($sales);

                    if (!empty($_GET['from'])):
                        $paginacion['from'] = $_GET['from'];
                        $this->sellerView->showCard($seller, $request->user, $sales, $totalVentas, $paginacion, $msg);
                    else:
                        $this->sellerView->showCard($seller, $request->user, $sales, $totalVentas, $paginacion, $msg);
                    endif;
                } else {
                    $this->sellerView->showErrorMsg();
                }
        */
    }
}