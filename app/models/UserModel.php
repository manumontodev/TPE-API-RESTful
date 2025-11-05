<?php
require_once 'Model.php';
class UserModel extends Model{

    // Verifica si la base de datos tiene tablas
    protected function tableExists(){
        $query = $this->db->query('SHOW TABLES LIKE "usuario"');
        return count($query->fetchAll())>0;
    }
    
    // crea una tabla usuario en la db
    protected function createTable(){
                $this->db->exec(
            "CREATE TABLE IF NOT EXISTS `usuario` (
                `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
                `user` varchar(300) NOT NULL,
                `password` char(60) NOT NULL,
                `rol` varchar(300) NOT NULL,
                PRIMARY KEY (`id_usuario`),
                KEY `username` (`user`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;"
        );
    }

    // carga la tabla con los datos predefinidos en config.php
    public function preloadTable(){
        // USERS: array de usuarios
        foreach (USERS as $user)
            $this->insert(...$user); // '...' separa elems de $user para pasarlos como params
    }

    public function get($id) {
        $query = $this->db->prepare('SELECT * FROM usuario WHERE id_usuario = ?');
        $query->execute([$id]);
        $user = $query->fetch(PDO::FETCH_OBJ);

        return $user;
    }

    public function getByUser($user) {
        $query = $this->db->prepare('SELECT * FROM usuario WHERE user = ?');
        $query->execute([$user]);
        $user = $query->fetch(PDO::FETCH_OBJ);

        return $user;
    }
    
    public function getAll() {

        $query = $this->db->prepare('SELECT * FROM usuario');
        $query->execute();

        $users = $query->fetchAll(PDO::FETCH_OBJ);

        return $users;
    }

    function insert($user, $password, $rol = null) {
        $query = $this->db->prepare("INSERT INTO usuario(user, password, rol) VALUES(?,?,?)");
        $query->execute([$user, $password,$rol]);


        return $this->db->lastInsertId();
    }

}