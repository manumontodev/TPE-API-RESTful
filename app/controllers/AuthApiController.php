<?php
require_once 'app/models/UserModel.php';
class AuthApiController
{
    private $model;

    public function __construct()
    {
        $this->model = new UserModel();
    }

    public function login($request, $res)
    {
        $authorization = $request->authorization;

        $auth = explode(' ', $authorization);

        // valida el encabezado
        if (count($auth) != 2 || $auth[0] !== 'Basic'):
            header("WWW-Authenticate: Basic realm='Get a token'");
            return $res->json("Autenticación inválida", 401);
        endif;

        $auth = base64_decode($auth[1]);
        $user_pass = explode(":", $auth);

        if (count($user_pass) != 2) 
            return $res->json("Autenticación inválida", 401);
        

        $username = $user_pass[0];
        $password = $user_pass[1];
        // Pide el usuario al model
        $user = $this->model->getByUser($username);
        
        // si no encuentra al usuario o contraseña invalida
        if (!$user || !password_verify($password, $user->password)) 
            return $res->json("Usuario o contraseña incorrecta", 401);
        

        // prepara un token
        $payload = [
            'sub' => $user->id_usuario,
            'usuario' => $user->user,
            'roles' => ["$user->rol"],
            'exp' => time() + 3600 // 1 hora
        ];

        // devuelve token JWT
        return $res->json(createJWT($payload));
    }
}