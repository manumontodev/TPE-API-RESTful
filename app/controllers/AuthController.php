<?php
require_once 'app/models/UserModel.php';
require_once 'app/views/AuthView.php';
class AuthController
{
    private $model;
    private $view;

    public function __construct()
    {
        $this->model = new UserModel();
        $this->view = new AuthView();
    }

    public function showLogin()
    {
        $this->view->showLogin();
    }


    public function login() {
        if (empty($_POST['user']) || empty($_POST['password'])) {
            return $this->view->showError("Faltan datos obligatorios");
        }
    
        $user = $_POST['user'];
        $password = $_POST['password'];
    
        $userFromDB = $this->model->getByUser($user);
    
        if ($userFromDB && password_verify($password, $userFromDB->password)) {
            $_SESSION['USER_ID'] = $userFromDB->id_usuario;
            $_SESSION['USER_NAME'] = $userFromDB->user;
            $_SESSION['USER_ROLE'] = $userFromDB->rol; // guardamos el rol  
    
            header('Location: ' . BASE_URL);
            return;
        } else {
            return $this->view->showError("Usuario o contraseña incorrecta");
        }
    }
    

    public function logout($request)
    {
        session_destroy();
        header("Location: " . BASE_URL);
        return;
    }

}