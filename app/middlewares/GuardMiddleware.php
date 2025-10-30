<?php

    class GuardMiddleware extends Middleware {
        public function run($request, $response) {
            if(!$request->user) {
                header("WWW-Authenticate: Basic realm='Access to the API'");
                return $response->json("No autorizado", 401);
            }
            else if(!in_array('BANANA', $request->user->roles)) {
                return $response->json("No tiene permisos suficientes", 403);
            }
        }
    }
