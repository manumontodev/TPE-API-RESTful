<?php
require_once 'Model.php';
class SaleModel extends Model{ 

    public function createTable()
    {
        $this->db->exec(
            "CREATE TABLE IF NOT EXISTS `venta` (
                `id_venta` int(11) NOT NULL AUTO_INCREMENT,
                `producto` varchar(200) NOT NULL,
                `precio` decimal(10,2) NOT NULL,
                `id_vendedor` int(11) NOT NULL,
                `fecha` date NOT NULL,
                PRIMARY KEY (`id_venta`),
                KEY `id_vendedor` (`id_vendedor`),
                CONSTRAINT `venta_ibfk_1` FOREIGN KEY (`id_vendedor`) REFERENCES `vendedor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;"
        );
    }

    // carga la tabla con los datos predefinidos en config.php
    public function preloadTable()
    {
        // VENTAS: array de ventas
        foreach (VENTAS as $venta) {
            $this->insert(...$venta); // '...' separa elems de $venta para pasarlos como params
        }
    }

    // Verifica si la base de datos tiene tablas
    public function tableExists()
    {
        $query = $this->db->query('SHOW TABLES LIKE "venta"');
        return count($query->fetchAll()) > 0;
    }
    
    public function getAll() {
        $query = $this->db->prepare('
            SELECT v.*, ve.nombre AS vendedor
            FROM venta v
            JOIN vendedor ve ON v.id_vendedor = ve.id
        ');
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    // devuelve array de ventas por vendedor
    public function getSalesById($sellerId){
        $query = $this->db->prepare('SELECT * FROM `venta` WHERE `id_vendedor` = ? ORDER BY `id_venta` ASC');
        $query->execute([(int)$sellerId]);

        $sales = $query->fetchAll(PDO::FETCH_OBJ);

        return $sales;
    }

    
    public function getSaleById($idVenta) {
        $query = $this->db->prepare('
            SELECT v.*, s.nombre AS nombre
            FROM venta v
            INNER JOIN vendedor s ON v.id_vendedor = s.id
            WHERE v.id_venta = ?
        ');
        $query->execute([(int)$idVenta]);
        return $query->fetch(PDO::FETCH_OBJ);
    }
    
    
    public function showSale($id){
        $query = $this->db->prepare('SELECT * FROM venta WHERE `id_venta` = ?'); 
        $query->execute([$id]);

        $sale = $query->fetch(PDO::FETCH_OBJ);

        return $sale;
    }


    public function insert($producto, $precio, $vendedor, $fecha){
        $query = $this->db->prepare('INSERT INTO venta(producto, precio, id_vendedor, fecha) VALUES (?,?,?,?)');
        $query->execute([$producto, $precio, $vendedor, $fecha]);

        return $this->db->lastInsertId();
    }

    public function updateSale($id, $producto, $precio, $fecha) {
        $query = $this->db->prepare('UPDATE venta SET producto = ?, precio = ?, fecha = ? WHERE id_venta = ?');
        return $query->execute([$producto, $precio, $fecha, $id]);
    }
    

    public function deleteSale($id){
        $query = $this->db->prepare('DELETE FROM `venta` WHERE `id_venta` = ?');
        $query->execute([$id]);
    }

}