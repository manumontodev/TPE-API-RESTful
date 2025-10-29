<?php
require_once 'Model.php';
class SellerModel extends Model{
    
    protected function createTable()
    {
        $this->db->exec(
            "CREATE TABLE IF NOT EXISTS `vendedor` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `nombre` varchar(100) NOT NULL,
                `telefono` varchar(20) NOT NULL,
                `email` varchar(200) NOT NULL,
                `imagen` varchar(50) NOT NULL,
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
    
    public function getSellers(){
        $query = $this->db->prepare('SELECT * FROM vendedor');
        $query->execute();

        $sellers = $query->fetchAll(PDO::FETCH_OBJ);

        return $sellers;
    }

    public function getSellerById($id) {
        $query  = $this->db->prepare('SELECT * FROM vendedor WHERE id = ?');
        $query->execute([(int)$id]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function update($id, $nombre, $telefono, $email, $img = null) {        
        if ($img):
            $query = $this->db->prepare('UPDATE vendedor SET imagen = ? WHERE id = ?');
            $query->execute([$img, $id]);
            return $query->rowCount()>0;                    
        else:
            $query = $this->db->prepare("UPDATE `vendedor` SET `nombre` = ?, `telefono` = ? , `email` = ? WHERE id = ?");
            $query->execute([$nombre, $telefono, $email, $id]);
            return $query->rowCount()>0;
        endif;
    } 

    public function delete($id) {
        $query = $this->db->prepare("DELETE FROM vendedor WHERE id = ?");        
        $query->execute([$id]);
        return $query->rowCount() > 0;
    }

    public function insert($nombre, $telefono, $email, $img = null) {
        if ($img)
            $path = $img;
        else
            $path = 'img/default-user-img.jpg';
        $query = $this->db->prepare("INSERT INTO `vendedor` (`id`, `nombre`, `telefono`, `email`, `imagen` ) VALUES (NULL, ?, ?, ?, ?)");
        $query->execute([$nombre, $telefono, $email, $path]);
        return $query->rowCount() > 0;
    }
}