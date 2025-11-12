<?php
require_once 'Model.php';
class SellerModel extends Model
{

    protected function createTable()
    {
        $this->db->exec( // subo imagen a (300) por si pinta permitir URLs
            "CREATE TABLE IF NOT EXISTS `vendedor` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `nombre` varchar(100) NOT NULL,
                `telefono` varchar(20) NOT NULL,
                `email` varchar(200) NOT NULL,
                `imagen` varchar(300) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;"
        );
    }

    // carga la tabla con los datos predefinidos en config.php
    function preloadTable()
    {
        foreach (VENDEDORES as $vendedor)
            $this->insert(...$vendedor);
    }

    // Verifica si la base de datos tiene tablas
    protected function tableExists()
    {
        $query = $this->db->query('SHOW TABLES LIKE "vendedor"');
        return count($query->fetchAll()) > 0;
    }

    public function getSellers($sort = null, $order = null, $page = null, $_size = 3, $filters = [], $params = [])
    {
        $sql = "SELECT * FROM `vendedor`";

        if (!empty($filters)) 
            $sql .= " WHERE " . implode(" AND ", $filters);
        if (!empty($sort) && !empty($order)) 
            $sql .= " ORDER BY $sort $order";

        if (!empty($page) && $page > 0) {
            $offset = ($page - 1) * $_size;
            $sql .= " LIMIT $offset, $_size";
        }
        $query = $this->db->prepare($sql);
        $query->execute($params);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function getSellerById($id)
    {
        $query = $this->db->prepare('SELECT * FROM vendedor WHERE id = ?');
        $query->execute([(int) $id]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function update($id, $nombre, $telefono, $email, $img = null)
    {
        if ($img):
            $query = $this->db->prepare('UPDATE vendedor SET imagen = ? WHERE id = ?');
            $query->execute([$img, $id]);
            return $query->rowCount() > 0;
        else:
            $query = $this->db->prepare("UPDATE `vendedor` SET `nombre` = ?, `telefono` = ? , `email` = ? WHERE id = ?");
            $query->execute([$nombre, $telefono, $email, $id]);
            return $query->rowCount() > 0;
        endif;
    }

    public function delete($id)
    {
        $query = $this->db->prepare("DELETE FROM vendedor WHERE id = ?");
        $query->execute([$id]);
        return $query->rowCount() > 0;
    }

    public function insert($nombre, $telefono, $email, $img = null)
    {
        if ($img)
            $path = $img;
        else
            $path = 'img/default-user-img.jpg';
        $query = $this->db->prepare("INSERT INTO `vendedor` (`id`, `nombre`, `telefono`, `email`, `imagen` ) VALUES (NULL, ?, ?, ?, ?)");
        $query->execute([$nombre, $telefono, $email, $path]);
        return $this->db->lastInsertId();
    }
    public function countSellers($filters = [], $params = [])
    {
        $sql = "SELECT COUNT(*) FROM vendedor";
        if (!empty($filters)) 
            $sql .= " WHERE " . implode(" AND ", $filters);

        $query = $this->db->prepare($sql);
        $query->execute($params);
        return (int) $query->fetchColumn();
    }


}