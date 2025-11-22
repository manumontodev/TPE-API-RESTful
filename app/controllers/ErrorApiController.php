<?php
class ErrorApiController
{
    public function not_allowed($req, $res)// valida el verbo endpoint
    {
        // devuelve 405 si el method no es valido por ej si manda delete a /vendedores
        return $res->json(['Error' => 'Request method not allowed for requested resource'], 405);

    }

    public function not_found($req, $res)
    {
        return $res->json(['Error' => 'Route not found for requested resource'], 404);

    }
}