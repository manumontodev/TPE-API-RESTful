<?php
require_once 'app/models/SellerModel.php'; //aca esta seller en minuscula, te funciona?
require_once 'app/models/SaleModel.php';

class SellerApiController
{
    private $sellerModel;
    private $saleModel;
    private const ERRORS = [
        'Invalid ID' => [
            'Error' => 'Provided ID is not valid'
        ],
        'Invalid Page' => [
            'Error' => 'Page or page size not a positive integer'
        ]
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
        $id = $this->validate_int($req->params->id, $res, self::ERRORS['Invalid ID']);
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
        $id = $this->validate_int($req->params->id, $res, self::ERRORS['Invalid ID']);
        $this->seller_exists($id, $res);

        // elimina
        $delete = $this->sellerModel->delete($id);
        if (!$delete)
            return $res->json(['Error' => 'An unexpected error occurred on the server'], 500);
        http_response_code(204);
        die();
    }

    /* ------------ GETTERS VENDEDORES ------------ */
    public function getAll($req, $res)
    {
        // filtros
        $filterData = $this->getFilterData($req);
        $filters = $filterData['filters'];
        $params = $filterData['params'];

        // paginacion
        $page = $req->query->page ?? null;
        $size = $req->query->size ?? 5;
        $total = $this->sellerModel->countSellers($filters, $params);

        $size = $this->validate_int($size, $res, self::ERRORS['Invalid Page']);

        $max_pages = $total === 0 ? 1 : ceil($total / $size);
        $page = filter_var($page, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1]
        ]);
        if (!$page) {
            $page = 1;
        }

        // ordenamiento
        $allowedSorts = ['name', 'email', 'phone', 'id'];
        $sort = $req->query->sort ?? 'id';
        if ($sort === '')
            $sort = 'id'; // default
        elseif (!in_array($sort, $allowedSorts))
            return ($res->json(['Invalid sort parameter' => $sort], 400));

        $sort = match ($req->query->sort ?? '') {
            'name' => 'nombre',
            'email' => 'email',
            'phone' => 'telefono',
            default => 'id'
        };
        $order = match (strtolower($req->query->order ?? 'asc')) {
            'desc' => 'DESC',
            'asc' => 'ASC',
            default => 'ASC',
        };

        $sellers = $this->sellerModel->getSellers($sort, $order, $page, $size, $filters, $params);
        // incluye metadata de la paginacion
        $response = [
            'sellers' => $sellers,
            'metadata' => [
                'current_page' => $page ?? 1,
                'max_pages' => $max_pages,
                'current_size' => $size,
                'total_sellers' => $total,
                'orderBy' => $sort,
                'order' => $order
            ]
        ];

        return $res->json($response);
    }


    public function get($req, $res)// obtiene un vendedor por su ID
    {
        // valida
        $id = $this->validate_int($req->params->id, $res, self::ERRORS['Invalid ID']);
        $seller = $this->seller_exists($id, $res);

        return $res->json($seller);
    }

    public function getSalesById($req, $res)
    {
        // valida
        $id = $this->validate_int($req->params->id, $res, self::ERRORS['Invalid ID']);
        $this->seller_exists($id, $res);

        // paginacion
        $total = $this->saleModel->countSales(['id_vendedor = ' . $id]); // cuenta solo las q corresponden al vendedor
        $page = $req->query->page ?? null;
        $size = $req->query->size ?? 5;
        $size = $this->validate_int($size, $res, self::ERRORS['Invalid Page']);

        $max_pages = $total === 0 ? 1 : ceil($total / $size);

        if (!empty($page)) {
            $page = $this->validate_int($page, $res, self::ERRORS['Invalid Page']);
        } else
            $page = 1;

        // ordenamiento
        $allowedSorts = ['price', 'item', 'date', 'sale_id'];
        $sort = $req->query->sort ?? 'sale_id';
        if ($sort === '')
            $sort = 'sale_id'; // default
        elseif (!in_array($sort, $allowedSorts))
            return ($res->json(['Invalid sort parameter' => $sort], 400));

        $sort = match ($req->query->sort ?? '') {
            'price' => 'precio',
            'item' => 'producto',
            'date' => 'fecha',
            'sale_id' => 'id_venta', 
            default => 'id_venta' // si llega otra cosa, ordena por ID
        };
        $order = match (strtolower($req->query->order ?? 'asc')) {
            'desc' => 'DESC',
            'asc' => 'ASC',
            default => 'ASC',
        };

        $sales = $this->saleModel->getSalesById($id, $sort, $order, $page, $size);
        $response = [
            'sales' => $sales,
            'metadata' => [
                    'current_page' => $page ?? 1,
                    'max_pages' => $max_pages,
                    'current_size' => $size,
                    'total_sales' => $total,
                    'orderBy' => $sort,
                    'order' => $order
                ]
        ];
        return $res->json($response);

    }

    /**
     * Devuelve un array con los query params de filtrado que llegan vía request 
     * ej.: 
     * - $req->query->name: "nombre LIKE :name" => "%{name}%", 
     * - $req->query->email: "email LIKE :email" => "%{email}%",
     * - $req->query->phone: "telefono LIKE :phone" => "%{phone}%"
     * 
     * @param object $req
     * @return array =>
     *     - filters: string[],    // filtros SQL (ej. "nombre LIKE :name")
     *     - params: array<string,string> // arreglo asociativo de params (ej. [':name' => '%foo%'])
     * 
     */
    private function getFilterData($req)
    {
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

        return [
            'filters' => $filters,
            'params' => $params
        ];

    }


    /* ------------ VALIDACIONES ------------ */
    /**
     * verifica que el valor provisto sea un entero valido
     * @return int si es es valido, lo devuelve como entero
     * @throws Error sino, corta ejecucion y envia $res->json($message, 400)
     */
    private function validate_int($num, $res, $msg)
    {
        $num = filter_var($num, FILTER_VALIDATE_INT);
        if ($num <= 0 || !$num)
            die($res->json($msg, 400));
        return $num;
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