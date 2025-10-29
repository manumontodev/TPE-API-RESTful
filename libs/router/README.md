# Librería de ruteo ligera (libs/router)

Esta carpeta contiene una pequeña librería para enrutar peticiones HTTP en PHP. Está pensada para proyectos pequeños o como punto de partida educativo. La librería expone 3 clases principales:

- `Request` (en `request.php`)
- `Response` (en `response.php`)
- `Router` (en `router.php`)

La documentación siguiente explica cómo usar cada componente, ejemplos y notas sobre comportamiento.

## Instalación / inclusión

Simplemente incluye los archivos desde tu archivo de entrada (por ejemplo `api_router.php`). 
El archivo `router.php` ya incluye `request.php` y `response.php`, por lo que solo necesitas incluir `router.php`:

`require_once './libs/router/router.php';`


## Clases y API

### Router

`Router` representa la tabla de rutas y ejecución del ruteo.

Router API:
- `addRoute($url, $verb, $controller, $method)`: agrega una ruta. `$url` puede contener parámetros con prefijo `:` (ej. `/api/tareas/:id`). `$verb` es el método HTTP en mayúsculas (`GET`, `POST`, `PUT`, `DELETE`, etc.). `$controller` es el nombre de la clase del controlador (string). `$method` es el nombre del método a invocar en ese controlador.
- `setDefaultRoute($controller, $method)`: configura una ruta por defecto que se ejecuta si no coincide ninguna ruta registrada.
- `addMiddleware($middleware)`: agrega un middleware. El middleware debe tener un método `run($request, $response)` que será ejecutado antes de resolver rutas.
- `route($url, $verb)`: resuelve y ejecuta la ruta que coincida con la URL y verbo. Antes de buscar rutas, ejecuta todos los middlewares registrados.

#### Ejemplo de registro de rutas (por ejemplo en `api_router.php`):

```
$router = new Router();

// Rutas REST para tareas
$router->addRoute('tareas', 'GET', 'TaskApiController', 'getAll');
$router->addRoute('tareas', 'POST', 'TaskApiController', 'insert');
$router->addRoute('tareas/:id', 'GET', 'TaskApiController', 'get');
$router->addRoute('tareas/:id', 'PUT', 'TaskApiController', 'update');
$router->addRoute('tareas/:id', 'DELETE', 'TaskApiController', 'remove');

// Ruta por defecto (opcional)
$router->setDefaultRoute('TaskApiController', 'notFound');

// Ejecutar ruteo: pasar la URI solicitada y el método
$router->route($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
```

### Request
Representa la solicitud enviada al servidor. El router envia este objeto al controlador asociado a la ruta.

- Propiedades públicas:
  - `body` (object|null): JSON decodificado del cuerpo de la petición (lee `php://input`).
  - `params` (object|null): parámetros capturados en la ruta (p. ej. `:id`).
  - `query` (object): parámetros de consulta (`$_GET`) convertidos a objeto.

Ejemplo de uso dentro de un controller:

```
public function update($request, $response) {
    $id = $request->params->id;     // parámetro de ruta
    $data = $request->body;         // body JSON (objeto)
    $sort = $request->query->sort;  // query string
}
```

### Response
Se utiliza para devolver respuestas a las solicitudes de los clientesa. El router envia este objeto al controlador asociado a la ruta.

- Métodos públicos:
  - `json($data, $status = 200)`: envía una respuesta JSON con el código HTTP y cabeceras apropiadas.

Ejemplo:

```
public function insert($request, $response) {
    // ...

    $response->json(['message' => 'Creado'], 201);
}
```


### Middlewares
// COMPLETAR

