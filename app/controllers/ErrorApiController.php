<?php
class ErrorApiController
{
    public function not_allowed($req, $res)// valida el verbo endpoint
    {
        // devuelve 405 si el method no es valido por ej si manda delete a /vendedores
        return $res->json('Request Method not Allowed', 405);
    }

    public function not_found($req, $res)
    {
        $res->json('Route Not Found', 404);
    }
}