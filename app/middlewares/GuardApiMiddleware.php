<?php

class GuardApiMiddleware extends Middleware
{
    public function run($request, $response)
    {
        // arreglo de rutas con acceso restringido
        $protectedRoute = [
            'POST' => ['ventas', 'vendedores'],
            'PUT' => ['ventas/:id', 'vendedores/:id'],
            'DELETE' => ['ventas/:id', 'vendedores/:id']
        ];

        $method = $_SERVER['REQUEST_METHOD'];
        $resource = $_GET['resource'];

        $flag = false;
        if (isset($protectedRoute[$method]))
            foreach ($protectedRoute[$method] as $pattern)
                // busca en el arreglo 
                if (str_starts_with($resource, explode('/:id', $pattern)[0]))
                    $flag = true;

        // si es de acceso restringido, valida credenciales de usuario
        if ($flag):
            if (!$request->user) {
                header("WWW-Authenticate: Bearer realm='Access to the API'");
                return $response->json(["Error" => "Authentication is required"], 401);
            }

            if (!in_array('administrador', $request->user->roles)) {
                return $response->json(["Error" => "Request does not meet required permissions"], 403);
            }
        endif;
    }
}

