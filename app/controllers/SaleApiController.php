<?php

require_once 'app/models/SaleModel.php';
require_once 'app/models/SellerModel.php'; 

class SaleController {
    private $model;
    private $view;
    private $modelSeller;

    function __construct() {
        // instanciar categoria primero, luego item (contrario: rompe autodeploy por la dependencia de tablas)
        $this->modelSeller = new SellerModel(); 
        $this->model = new SaleModel();
        $this->view = new SaleView();
    }

    public function showSales($request) {
        $sales = $this->model->getAll();
        $this->view->showSales($sales, $request->user);
    } 


    public function showSaleDetail($id) {
        $sale = $this->model->getSaleById($id);
    
        if ($sale) {
            $this->view->showSaleDetail($sale);
        } else {
            $this->view->showError("No se encontró la venta con ID $id");
        }
    } 
    

    public function showSale($id) {
        $sale = $this->model->showSale($id);
        if (!$sale) {
            $this->view->showError("No se encontró la venta con ID $id");
            return;
        }
        $this->view->showSaleDetail($sale);
    }

    public function showAddSaleForm($request) {
        if (!isset($_SESSION['USER_ROLE']) || $_SESSION['USER_ROLE'] !== 'administrador') {
            return $this->view->showError('Acceso denegado. Solo los administradores pueden agregar ventas.');
        }
    
        $sellers = $this->modelSeller->getSellers();
        $this->view->showAddSaleForm($sellers, $request->user); // necesito el user para mostrar el boton de nuevo vendedor en el menu dropdown
    }//se usa 
    

    public function addSale($request) {
        if (!isset($_SESSION['USER_ROLE']) || $_SESSION['USER_ROLE'] !== 'administrador') {
            return $this->view->showError('Acceso denegado. Solo los usuarios registrados pueden agregar ventas.');
        }
    
        if (empty($_POST['producto']) || empty($_POST['precio']) || empty($_POST['vendedor']) || empty($_POST['fecha'])) {
            return $this->view->showError('Error: faltan datos obligatorios');
        }
    
        $producto = $_POST['producto'];
        $precio = $_POST['precio'];
        $vendedor = $_POST['vendedor'];
        $fecha = $_POST['fecha'];
    
        $id = $this->model->insert($producto, $precio, $vendedor, $fecha);
    
        if (!$id) {
            return $this->view->showError('Error al generar la venta');
        }
    
        header('Location: ' . BASE_URL); 
    }


    public function updateSale($id, $request) {
        // Solo admin puede actualizar
        if (!$request->user || $request->user->rol !== 'administrador') {
            return $this->view->showError('Acceso denegado. Solo los administradores pueden editar ventas.');
        }
    
        if (empty($_POST['producto']) || empty($_POST['precio']) || empty($_POST['fecha'])) {
            return $this->view->showError('Faltan datos obligatorios para editar la venta.');
        }
    
        $producto = $_POST['producto'];
        $precio = $_POST['precio'];
        $fecha = $_POST['fecha'];
    
        $ok = $this->model->updateSale($id, $producto, $precio, $fecha);
    
        if (!$ok) {
            return $this->view->showError('Error al actualizar la venta.');
        }
    
        $this->view->showMessageConfirm('Venta editada correctamente.');
        header('Location: ' . BASE_URL);
    }
    

    public function showFormUpdate($id, $request) {
        // Solo admin puede acceder
        if (!$request->user || $request->user->rol !== 'administrador') {
            return $this->view->showError('Acceso denegado. Solo los administradores pueden editar ventas.');
        }
    
        $sale = $this->model->showSale($id);
    
        if (!$sale) {
            return $this->view->showError('Venta no encontrada.');
        }
    
        $this->view->showEditSaleForm($sale);
    }
    
    

    public function deleteSale($id, $request){
        $this->model->deleteSale($id);
        $this->view->showMessageConfirm("Venta eliminada!");

        
    }

}
