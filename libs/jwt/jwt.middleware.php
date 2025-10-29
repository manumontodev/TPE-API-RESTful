<?php

require_once __DIR__ . '/jwt.php';

class JWTMiddleware extends Middleware {
    public function run($request, $response) {
        $auth_header = $_SERVER['HTTP_AUTHORIZATION']; // "Bearer un.token.firma"
            $auth_header = explode(' ', $auth_header); // ["Bearer", "un.token.firma"]
            if(count($auth_header) != 2) {
                return;
            }
            if($auth_header[0] != 'Bearer') {
                return;
            }
            $jwt = $auth_header[1];
            $request->user = validateJWT($jwt);
        }
    }