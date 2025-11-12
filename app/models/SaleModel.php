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
    
    public function getAll($userSortField, $userSortOrder, $filters, $limit, $offset, $params) {
      // Construye la consulta SQL
      $sql = "SELECT * FROM venta";

      // Agrega filtros si existen
      if (!empty($filters)) {
          $sql .= " WHERE " . implode(" AND ", $filters); //.= agrega esta nueva parte al final de la cadena existente en $sql. implode convierte array en cadena texto
      }

      // Agrega ordenamiento y limit
      $sql .= " ORDER BY $userSortField $userSortOrder LIMIT :limit OFFSET :offset";

      $query = $this->db->prepare($sql);

      // Vincula parámetros
      foreach ($params as $key => $value) {
          $query->bindValue($key, $value); //En PHP, bindValue es un método que se utiliza en consultas preparadas para asociar un valor específico a un parámetro de la consulta SQL antes de ejecutarla
      }

      // Vincula límite y offset
      $query->bindValue(':limit', $limit, PDO::PARAM_INT); //:limit y :offset son marcadores de posición para parámetros en una consulta SQL. El símbolo : indica que limit y offset son parámetros que se enlazarán (o vincularán) a valores específicos más adelante en el código.
      $query->bindValue(':offset', $offset, PDO::PARAM_INT);

      $query->execute();

      // Retorna resultados como objetos
      return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function countSales($filters, $params = []) {
        $sql = "SELECT COUNT(*) FROM venta";
        if (!empty($filters)) {
            $sql .= " WHERE " . implode(" AND ", $filters);
        }
    
        $query = $this->db->prepare($sql);
    
        foreach ($params as $key => $value) {
            $query->bindValue($key, $value);
        }
    
        $query->execute();
        return $query->fetchColumn();
    }
    


    public function getSalesById($sellerId, $sort = null, $order = null, $page = null, $_size = 3)
    { 
        $sql = 'SELECT * FROM `venta` WHERE `id_vendedor` = ?';
        if (!empty($sort) && !empty($order)) 
            $sql .= " ORDER BY $sort $order";
        if (!empty($page) && $page > 0) {
            $offset = ($page - 1) * $_size;
            $sql .= " LIMIT $offset, $_size";
        }
        $query = $this->db->prepare($sql);
        $query->execute([(int)$sellerId]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function getSaleById($idVenta) { // obtiene una venta por su ID
        $query = $this->db->prepare('
            SELECT v.*, s.nombre AS nombre
            FROM venta v
            INNER JOIN vendedor s ON v.id_vendedor = s.id
            WHERE v.id_venta = ?
        ');
        $query->execute([(int)$idVenta]);
        return $query->fetch(PDO::FETCH_OBJ);
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