<?php
require_once __DIR__ . '/../config/config.php';


abstract class Model
{
    // variable de conexion al sv para las clases hijas
    private static $pdo;

    
    // variable usada por las clases hijas
    protected $db;

    public function __construct()
    {
        // si no existe una conexion
        if (!self::$pdo) {
            try {

                // Se conecta al server
                self::$pdo = new PDO(
                    "mysql:host=" . MYSQL_HOST . ";charset=utf8",
                    MYSQL_USER,
                    MYSQL_PASS
                );

                // Si no existe db, inicializa una
                self::$pdo->exec("CREATE DATABASE IF NOT EXISTS `" . MYSQL_DB . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");

                // Se conecta a la nueva db
                self::$pdo = new PDO(
                    "mysql:host=" . MYSQL_HOST . ";dbname=" . MYSQL_DB . ";charset=utf8mb4",
                    MYSQL_USER,
                    MYSQL_PASS
                );



            } catch (PDOException $e) {
                throw new Exception ("Error en la conexión o creación de DB: " . $e->getMessage());
            }
        }
        // llama a funcion para crear y cargar tablas
        $this->deploy();
        // le asigna la conexion existente para las hijas
        $this->db = self::$pdo;

    }

    // funcion responsable de la creacion y precarga de las tablas
    public function deploy()
    {
        $this->db = self::$pdo;
        // si ya existen tablas termino
        if ($this->tableExists())
            return;
        $this->createTable();
        $this->preloadTable();
    }

    // las hijas definen el comportamiento de deploy
    protected abstract function tableExists();

    protected abstract function createTable();

    protected abstract function preloadTable();
}

