<?php
    class Request {
        public $body = null; # { nombre: 'Saludar', descripcion: 'Saludar a todos' }
        public $params = null; # /api/tareas/:id
        public $query = null; # ?soloFinalizadas=true
        public $user = null; # Información del usuario autenticado
        public $authorization = null;

        public function __construct() {
            try {
                # file_get_contents('php://input') lee el body de la request
                $this->body = json_decode(file_get_contents('php://input'));
            }
            catch (Exception $e) {
                $this->body = null;
            }
            $this->authorization = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
            $this->query = (object) $_GET;
        }
    }