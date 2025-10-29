<?php

    class GuardMiddleware {
        public function run($request) {
            if($request->user) {  //se verifica que haya un usuario logueado
                return $request; //si lo hay, se continua la ejecucion
            } else {
                header("Location: ".BASE_URL."showLogin"); //y si no, vuelvo al home
                exit();
            }
        }
    }
