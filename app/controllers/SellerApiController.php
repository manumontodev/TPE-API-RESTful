<?php
require_once 'app/models/sellerModel.php';
require_once 'app/models/SaleModel.php';

class sellerApiController
{
    private $sellerModel;
    private $saleModel;
    private const INVALID_ID = ['Syntax Error' => 'Provided ID is not a valid ID'];
    private const INVALID_PAGE = [
        'Syntax Error' => 'Syntax Error: Required page is not a valid number',
        'Out of Range' => "Out of Range: Required page is out of range."
    ];

    function __construct()
    {
        $this->sellerModel = new SellerModel();
        $this->saleModel = new SaleModel();
    }

    /* ------------ FUNCIONES CRUD ------------ */
    public function insert($req, $res) // insertar vendedor
    {
        $this->validate_body($req, $res);

        // obtiene los datos del body
        $nombre = $req->body->nombre;
        $telefono = $req->body->telefono;
        $email = $req->body->email;

        // inserta los datos
        $newId = $this->sellerModel->insert($nombre, $telefono, $email);
        // si falla, devuelve 500
        if (!$newId)
            return $res->json(['An unexpected error occurred on the server'], 500);

        // devuelve datos insertados
        $newSeller = $this->sellerModel->getSellerById($newId);
        return $res->json($newSeller, 201);
    }

    public function update($req, $res)
    {
        // valida 
        $id = $this->validate_int($req->params->id, $res, self::INVALID_ID);
        $this->validate_body($req, $res);
        $this->seller_exists($id, $res);

        $nombre = $req->body->nombre;
        $telefono = $req->body->telefono;
        $email = $req->body->email;

        // actualiza los datos
        $result = $this->sellerModel->update($id, $nombre, $telefono, $email);
        if (!$result)
            return $res->json(['An unexpected error occurred on the server'], 500);

        // devuelve datos actualizados
        return $res->json($this->sellerModel->getSellerById($id));
    }

    function delete($req, $res)
    {
        // valida
        $id = $this->validate_int($req->params->id, $res, self::INVALID_ID);
        $this->seller_exists($id, $res);

        // elimina
        $delete = $this->sellerModel->delete($id);
        if (!$delete)
            return $res->json(['An unexpected error occurred on the server'], 500);
        return $res->json('', 204);
    }

    /* ------------ GETTERS VENDEDORES ------------ */
    public function getAll($req, $res)
    {
        $sort = !empty($req->query->sort) ? $req->query->sort : 'id'; // campos
        $order = $req->query->order ?? 'ASC'; // direccion
        $page = $req->query->page ?? null;
        $_size = $req->query->size ?? 5; // default 5 por pagina

        // paginacion
        if (!empty($page))
            $page = $this->validate_int($page, $res, self::INVALID_PAGE['Syntax Error']);
        if (!empty($_size) && $_size != 5)
            $_size = $this->validate_int($_size, $res, self::INVALID_PAGE['Syntax Error']);

        // ordenamiento
        if (!empty($req->query->sort)) {
            $sort = match ($sort) {
                'name' => 'nombre',
                'email' => 'email',
                'phone' => 'telefono',
                default => 'id' // si llega otra cosa queda ordena por ID
            };
        }
        if (!empty($req->query->order)) {
            $order = match ($order) {
                'desc' => 'DESC',
                'asc' => 'ASC',
                default => 'ASC' // si llega otra cosa ordena ASC
            };
        }

        // filtros
        $filters = [];
        $params = [];

        if (!empty($req->query->name)) {
            $filters[] = "nombre LIKE :name";
            $params[':name'] = '%' . $req->query->name . '%';
        }

        if (!empty($req->query->email)) {
            $filters[] = "email LIKE :email";
            $params[':email'] = '%' . $req->query->email . '%';
        }

        if (!empty($req->query->phone)) {
            $filters[] = "telefono LIKE :phone";
            $params[':phone'] = '%' . $req->query->phone . '%';
        }

        $sellers = $this->sellerModel->getSellers($sort, $order, $page, $_size, $filters, $params);

        if (empty($page)):
            return $res->json($sellers);
        else:
            $total = $this->sellerModel->countSellers($filters, $params);
            $max_pages = ceil($total / $_size);
            // envia metadata de la paginacion en la response
            $response = [
                'sellers' => $sellers,
                'page' => $this->validate_page_range($page, $max_pages, $res, self::INVALID_PAGE['Out of Range']),
                'size' => $_size,
                'total_sellers' => $total,
                'max_pages' => $max_pages
            ];

            return $res->json($response);

        endif;
    }


    public function get($req, $res)// obtiene un vendedor por su ID
    {
        // valida
        $id = $this->validate_int($req->params->id, $res, self::INVALID_ID);
        $seller = $this->seller_exists($id, $res);

        return $res->json($seller);
    }

    public function getSalesById($req, $res)
    {
        $sort = !empty($req->query->sort) ? $req->query->sort : 'id_venta'; // campos
        $order = $req->query->order ?? 'ASC'; // direccion
        $page = $req->query->page ?? null;
        $_size = $req->query->size ?? 3; // default = 3 ventas x pagina

        // valida
        $id = $this->validate_int($req->params->id, $res, self::INVALID_ID);
        $this->seller_exists($id, $res);

        // paginacion
        if (!empty($page))
            $page = $this->validate_int($page, $res, self::INVALID_PAGE['Syntax Error']);
        if (!empty($_size) && $_size != 5)
            $_size = $this->validate_int($_size, $res, self::INVALID_PAGE['Syntax Error']);

        // ordenamiento
        if (!empty($req->query->sort)) {
            $sort = match ($sort) {
                'price' => 'precio',
                'product' => 'producto',
                'date' => 'fecha',
                default => 'id_venta' // si llega otra cosa queda ordena por ID
            };
        }
        if (!empty($req->query->order)) {
            $order = match ($order) {
                'desc' => 'DESC',
                'asc' => 'ASC',
                default => 'ASC' // si llega otra cosa ordena ASC
            };
        }

        $sales = $this->saleModel->getSalesById($id, $sort, $order, $page, $_size);

        if (empty($page)):
            return $res->json($sales);
        else:
            $total = $this->saleModel->countSales(['id_vendedor = ' . $id]); // cuenta solo las q corresponden al vendedor
            if ($_size > $total)
                $_size = $total;
            $max_pages = ceil($total / $_size);
            // envia metadata de la paginacion en la response
            $response = [
                'ventas' => $sales,
                'page' => $this->validate_page_range($page, $max_pages, $res, self::INVALID_PAGE['Out of Range']),
                'size' => $_size,
                'total_sales' => $total,
                'max_pages' => $max_pages
            ];


            return $res->json($response);

        endif;
    }

    /* ------------ VALIDACIONES ------------ */
    /**
     * verifica que el valor provisto sea un entero valido
     * @return int si es es valido, lo devuelve como entero
     * @throws Error sino, corta ejecucion y envia $res->json($message, 400)
     */
    private function validate_int($id, $res, $msg)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id)
            die($res->json($msg, 400));
        return $id;
    }
    /**
     * verifica que body request contenga todos datos obligatorios y valida formato de email
     * @return mixed si pasa las validaciones devuelve true
     * @throws Error sino, corta ejecucion y envia $res->json(404)
     */
    private function validate_body($req, $res)
    {
        if (empty($req->body->nombre) || empty($req->body->telefono) || empty($req->body->email))
            die($res->json(['Missing required data' => 'One or more required fields are missing'], 400));
        elseif (!filter_var($req->body->email, FILTER_VALIDATE_EMAIL))
            die($res->json(['Syntax error' => 'The provided email is not a valid email'], 400));
        return true;
    }

    private function validate_page_range($num, $range, $res, $msg)
    {
        if ($num > $range)
            die($res->json($msg . ' Total pages = ' . $range, 400)); // agrego informacion extra al msg de error
        return $num;
    }

    /**
     * verifica existencia vendedor segun su id
     * @return mixed si encuentra al vendedor, lo devuelve. 
     * @throws Error sino, corta ejecucion y envia $res->json(404)
     */
    private function seller_exists($id, $res)
    {
        $seller = $this->sellerModel->getSellerById($id);
        if (!$seller)
            die($res->json(["Seller not found" => "Seller with ID=$id doesn't exist in the database"], 404));
        return $seller;
    }
}