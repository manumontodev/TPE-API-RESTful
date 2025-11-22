# 💻 TPE Parte 3: API RESTful - Tienda Computación

[![Status](https://img.shields.io/badge/Status-Completado-green.svg)](https://github.com/lumoreiralu/TPEspecial-web2-2025)
[![License](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)

Este es el repositorio de la **API REST** de la [Tienda Computación](https://github.com/lumoreiralu/TPEspecial-web2-2025). La API permite gestionar las entidades de **Vendedores** y **Ventas**, proporcionando un conjunto de servicios de Alta, Baja, Modificación (ABM) y consulta a través de HTTP.

## 🧑‍💻 Miembros del Equipo

| Nombre             | GitHub                                               | Email                             |
| :----------------- | :--------------------------------------------------- | :-------------------------------- |
| **Lucia Moreira**  | [@luciamoreira96](https://github.com/luciamoreira96) | `lulii.moreira96@gmail.com`       |
| **Manuel Montoya** | [@manumontodev](https://github.com/manumontodev)     | `montoya.christensen@outlook.com` |

---

## CREDENCIALES DE AUTENTICACION

- usuario administrador:

```bash
- user: webadmin
- password: admin
```

- usuarios **no** administradores:

```bash
- user: lucia
- password: lucia

- user: manuel
- password: manuel
```
---

## 🗺️ Endpoints de la API (Tabla de Ruteo)

La API opera sobre dos recursos principales: `ventas` y `vendedores`. El acceso a los servicios de ABM requiere **Autenticación** y permisos de administrador.

### Recurso: `/ventas`

| Verbo HTTP   | Endpoint      | Descripción                                | Requiere Auth |
| :----------- | :------------ | :----------------------------------------- | :------------ |
| **`GET`**    | `/ventas`     | Obtiene la lista de todas las ventas.      | No            |
| **`GET`**    | `/ventas/:id` | Obtiene el detalle de una venta por su ID. | No            |
| **`POST`**   | `/ventas`     | Crea una nueva venta.                      | **Sí**        |
| **`PUT`**    | `/ventas/:id` | Modifica una venta existente por su ID.    | **Sí**        |
| **`DELETE`** | `/ventas/:id` | Elimina una venta por su ID.               | **Sí**        |

### Recurso: `/vendedores`

| Verbo HTTP   | Endpoint                 | Descripción                                                  | Requiere Auth |
| :----------- | :----------------------- | :----------------------------------------------------------- | :------------ |
| **`GET`**    | `/vendedores`            | Obtiene la lista de todos los vendedores.                    | No            |
| **`GET`**    | `/vendedores/:id`        | Obtiene la información de un vendedor por su ID.             | No            |
| **`GET`**    | `/vendedores/:id/ventas` | Obtiene todas las ventas asociadas a un vendedor específico. | No            |
| **`POST`**   | `/vendedores`            | Crea un nuevo vendedor (Alta).                               | **Sí**        |
| **`PUT`**    | `/vendedores/:id`        | Modifica los datos de un vendedor existente.                 | **Sí**        |
| **`DELETE`** | `/vendedores/:id`        | Elimina un vendedor por su ID.                               | **Sí**        |

### 🔑 Autenticación (JWT)

| Verbo HTTP | Endpoint      | Descripción                                                                                                                                                |
| :--------- | :------------ | :--------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **`GET`**  | `/auth/login` | Genera un **Token JWT** necesario para incluir en el encabezado de las solicitudes (headers) que acceden a los servicios de ABM (`POST`, `PUT`, `DELETE`). |

---

## 🔎 Consultas Avanzadas (Filtros y Ordenamiento)

La API permite obtener listas de ventas y vendedores aplicando filtros, paginación y ordenamiento sobre diversos campos. Se pueden combinar entre sí concatenandose con `&`. A continuación se detallan ejemplos y valores aceptados:

### Recurso: /VENTAS

| Operación               | Ejemplo de URL                          | Descripción                                                             |
| :---------------------- | :-------------------------------------- | :---------------------------------------------------------------------- |
| **Ordenamiento Simple** | `/ventas?sort=price`                    | Ordena las ventas por `precio` (ascendente por defecto).                |
| **Filtrado por Rango**  | `/ventas?min_price=4000&max_price=5000` | Filtra ventas dentro de un rango de precios.                            |
| **Filtrado por Campo**  | `/vendedores?name=Lucia`                | Filtra vendedores cuyo nombre es "Lucia".                               |
| **Filtrado Relacional** | `/ventas?seller_id=1`                   | Filtra todas las ventas realizadas por el vendedor con `id_vendedor=1`. |

### Recurso: /vendedores

| Operación        | Ejemplo de URL                                     | Descripción                                                                                                                                                     | Valores aceptados                                         | Defecto                          |
| :--------------- | :------------------------------------------------- | :-------------------------------------------------------------------------------------------------------------------------------------------------------------- | :-------------------------------------------------------- | :------------------------------- |
| **Ordenamiento** | `?sort=phone&order=desc`                           | Ordena los vendedores segun el criterio solicitado. Opcionalmente se puede solicitar que la dirección de ordenamiento sea descendente u ascendente.             | sort: `name`, `email`, `phone`, `id` order: `asc`, `desc` | default: `sort=id` y `order=asc` |
| **Filtrado**     | `?phone=249&name=Pepito` ó `?email=.%@&phone=2%84` | Devuelve los vendedores que cumplan con el o los filtros solicitados. Se pueden concatenar entre sí o por descomposición de palabras utilizando el operador `%` | filtros: `name`, `email`, `phone`                         | No existe un filto por defecto   |

### Recurso: /vendedores/:id/ventas

| Operación        | Ejemplo de URL           | Descripción                                                                                                                                              | Valores aceptados                                            | Defecto                               |
| :--------------- | :----------------------- | :------------------------------------------------------------------------------------------------------------------------------------------------------- | :----------------------------------------------------------- | :------------------------------------ |
| **Ordenamiento** | `?sort=price&order=desc` | Permite ordenar las ventas segun un criterio solicitado. Opcionalmente se puede solicitar que la dirección de ordenamiento sea descendente u ascendente. | sort: `sale_id` `price`, `item`, `date` order: `asc`, `desc` | default: `sort=sale_id` y `order=asc` |

### PAGINACION

| Operación      | Ejemplo de URL   | Descripción                                                                                                                                                                       | Valores aceptados                                                                                                                         | Defecto                                                             |
| :------------- | :--------------- | :-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | :---------------------------------------------------------------------------------------------------------------------------------------- | :------------------------------------------------------------------ |
| **Paginación** | `?page=1&size=4` | Funciona para todos los recursos mencionados anteriormente. Se puede modificar el tamaño de las paginas usando `&size`. La inclusion de ambos parametros (page&size) es opcional. | Ambos parametros son opcionales. El valor solicitado para ajustar la cantidad de elementos por pagina debe ser un numero entero positivo. | Por defecto se mostrara `page=1`, con tamaño por defecto de`size=5` |

---

## 🛠️ Ejemplos de Request y Response

A continuación, se detalla la estructura JSON esperada para las solicitudes (`POST` y `PUT`) y las respuestas (`GET`).

### Formato de Request

- **POST /ventas**

```bash
{
  "producto": "Nuevo Producto",
  "precio": "112233.99",
  "id_vendedor": 1,
  "fecha": "2025-11-01"
}
```

- **put /ventas/:id**

```bash
{
  "producto": "Otro Producto",
  "precio": "11223345.99",
  "fecha": "2025-11-02"
}
```

- **POST /vendedores**

```bash
  "nombre": "Nuevo",
  "telefono": 123456,
  "email": "nuevo@mail.com"
```

- **PUT /vendedores/:id**

```bash
{
  "nombre": "otro",
  "telefono": 654321,
  "email": "otro@mail.com"
}
```

### Formato de Respuesta (`GET /:id`)

- **/venta/:id**

```bash
{
  "id_venta": 1,
  "producto": "Monitor Smart HD Samsung",
  "precio": "310900.00",
  "id_vendedor": 1,
  "fecha": "2025-10-01",
  "nombre": "Lucia M"
}
```

- **/vendedor/id**

```bash
{
  "id": 2,
  "nombre": "Manuel",
  "telefono": 2494002,
  "email": "manuel@tienda.com"
}
```

### Query Params

- **GET ventas?page=1&size=1**

```bash
{
  "sales": [
    {
      "id_venta": 1,
      "producto": "Monitor Smart HD Samsung",
      "precio": "310900.00",
      "id_vendedor": 1,
      "fecha": "2025-10-01",
      "vendedor": "Lucia M"
    },
    {
      "id_venta": 2,
      "producto": "Teclado Mecanico Logitech",
      "precio": "3900.00",
      "id_vendedor": 2,
      "fecha": "2025-10-06",
      "vendedor": "Manuel"
    }
  ],
  "metadata": {
    "current_page": 1,
    "max_pages": 28,
    "current_size": 2,
    "total_sales": 56,
    "orderBy": "id_venta",
    "order": "ASC"
  }
}
```

- **vendedores?sort=name&order=desc&page=1&size=2**

```bash
{
  "sellers": [
    {
      "id": 8,
      "nombre": "Zoe",
      "telefono": "228405403",
      "email": "1997.zoe@tienda.com",
      "imagen": "img/default-user-img.jpg"
    },
    {
      "id": 6,
      "nombre": "Ximena",
      "telefono": "2314000001",
      "email": "ximena@tienda.com",
      "imagen": "img/default-user-img.jpg"
    }
  ],
  "metadata": {
    "current_page": 1,
    "max_pages": 8,
    "current_size": 2,
    "total_sellers": 16,
    "orderBy": "nombre",
    "order": "DESC"
  }
}
```

### Formato de Solicitud (`POST` y `PUT`)

| Recurso                   | Solicitud JSON (Body)                                                                  |
| :------------------------ | :------------------------------------------------------------------------------------- |
| **Venta** (`POST/PUT`)    | `json { "producto": "______", "precio": ___, "id_vendedor": _, "fecha": "________" } ` |
| **Vendedor** (`POST/PUT`) | `json { "nombre": "______", "telefono": ______, "email": "______" } `                  |

> **Nota sobre `PUT`:** Para modificar un recurso (`PUT /:id`), el cuerpo de la solicitud debe incluir **todos los campos** de la entidad, no solo los que se van a modificar.

### Endpoints Invalidos y Acceso Restringido (`POST`, `PUT` y `DELETE`)

Si el cliente envía una solictud a un endpoint de acceso restringido, por ej. `POST  /api/vendedores/:id`, recibirá alguno los siguientes mensajes de error:

- Si no se encuentra logeado, recibirá un `401` Unauthorized y se le solicitará autenticarse.
- Si se encuentra logeado, pero no cuenta con permisos (rol) de administrador, recibirá un `403` Forbidden.

Si el cliente envía una solicitud a un recurso valido pero con un método inválido, por ejemplo DELETE /api/vendedores:

- Recibirá un `405 Method Not Allowed`

Si el cliente envía una solicitud, independientemente del verbo, a un endpoint que no fue marcado como válido en la presente documentación:

- Recibirá un `404 => Route Not Found`.

---

## ⚙️ Instalación y Configuración

Este proyecto requiere un entorno de servidor web (XAMPP) para su ejecución.

### 1. Instalación Automática

El sitio está configurado para realizar un **auto-deploy** de la base de datos al acceder.

1. Asegúrate de tener **Apache** y **phpMyAdmin** corriendo (por ejemplo, usando [XAMPP](https://www.apachefriends.org/)).
2. Clona este repositorio dentro de la carpeta `htdocs` de tu servidor Apache.
3. Accede al proyecto a través de tu navegador local.

### 2. Instalación Manual de la Base de Datos

Si la instalación automática falla o prefieres hacerlo manualmente:

1. Abre [phpMyAdmin](http://localhost/phpmyadmin/) en tu navegador.
2. Crea una nueva base de datos llamada `db_tiendaComputacion`.
3. Selecciona la base de datos recién creada.
4. Haz clic en la pestaña **Importar**.
5. Selecciona el archivo `db/db_tiendaComputacion.sql` que se encuentra en este proyecto.
6. Haz clic en **Continuar** para importar las tablas y datos de ejemplo.

---

## 📊 Diagrama Entidad-Relación (DER)

Este diagrama ilustra la estructura de la base de datos subyacente que utiliza la API.

<p align="center">
  <img alt="Diagrama Entidad-Relación" src="./DER tienda.jpg" />
</p>

---

## 📚 Más Información

Este proyecto fue desarrollado en el marco de la materia **Web 2** de la carrera **TUDAI** en la UNICEN.

- [Ciencias Exactas](https://exa.unicen.edu.ar/) — Facultad de Ciencias Exactas, UNICEN.
- [TUDAI](https://www.unicen.edu.ar/content/tecnicatura-universitaria-en-desarrollo-de-aplicaciones-inform%C3%A1ticas-tudai) — Tecnicatura Universitaria en Desarrollo de Aplicaciones Informáticas.
- [WEB 2](https://tudai1-2.alumnos.exa.unicen.edu.ar/web-2) — Sitio de la cátedra.
- [Repositorio Parte 2](https://github.com/lumoreiralu/TPEspecial-web2-2025) — TPE - Parte 2: Sitio Web Dinámico.
