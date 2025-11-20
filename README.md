# 💻 TPE Parte 3: API RESTful - Tienda Computación

[![Status](https://img.shields.io/badge/Status-Completado-green.svg)](https://github.com/lumoreiralu/TPEspecial-web2-2025)
[![License](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)

Este es el repositorio de la **API RESTful** de la [Tienda Computación](https://github.com/lumoreiralu/TPEspecial-web2-2025). La API permite gestionar las entidades de **Vendedores** y **Ventas**, proporcionando un conjunto de servicios de Alta, Baja, Modificación (ABM) y consulta a través de HTTP.

## 🧑‍💻 Miembros del Equipo

| Nombre | GitHub | Email |
| :--- | :--- | :--- |
| **Lucia Moreira** | [@luciamoreira96](https://github.com/luciamoreira96) | `lulii.moreira96@gmail.com` |
| **Manuel Montoya** | [@manumontodev](https://github.com/manumontodev) | `montoya.christensen@outlook.com` |

---

## 🗺️ Endpoints de la API (Tabla de Ruteo)

La API opera sobre dos recursos principales: `ventas` y `vendedores`. El acceso a los servicios de ABM requiere **Autenticación**.

### Recurso: `/ventas`

| Verbo HTTP | Endpoint | Descripción | Requiere Auth |
| :--- | :--- | :--- | :--- |
| **`GET`** | `/ventas` | Obtiene la lista de todas las ventas. | No |
| **`GET`** | `/ventas/:id` | Obtiene el detalle de una venta por su ID. | No |
| **`POST`** | `/ventas` | Crea una nueva venta. | **Sí** |
| **`PUT`** | `/ventas/:id` | Modifica una venta existente por su ID. | **Sí** |
| **`DELETE`** | `/ventas/:id` | Elimina una venta por su ID. | **Sí** |

### Recurso: `/vendedores`

| Verbo HTTP | Endpoint | Descripción | Requiere Auth |
| :--- | :--- | :--- | :--- |
| **`GET`** | `/vendedores` | Obtiene la lista de todos los vendedores. | No |
| **`GET`** | `/vendedores/:id` | Obtiene la información de un vendedor por su ID. | No |
| **`GET`** | `/vendedores/:id/ventas` | Obtiene todas las ventas asociadas a un vendedor específico. | No |
| **`POST`** | `/vendedores` | Crea un nuevo vendedor (Alta). | **Sí** |
| **`PUT`** | `/vendedores/:id` | Modifica los datos de un vendedor existente. | **Sí** |
| **`DELETE`** | `/vendedores/:id` | Elimina un vendedor por su ID. | **Sí** |

### 🔑 Autenticación (JWT)

| Verbo HTTP | Endpoint | Descripción |
| :--- | :--- | :--- |
| **`GET`** | `/auth/login` | Genera un **Token JWT** necesario para incluir en el encabezado de las solicitudes (headers) que acceden a los servicios de ABM (`POST`, `PUT`, `DELETE`). |

---

## 🔎 Consultas Avanzadas (Filtros y Ordenamiento)

La API permite obtener listas de ventas y vendedores aplicando filtros, paginación y ordenamiento sobre diversos campos.

| Operación | Ejemplo de URL | Descripción |
| :--- | :--- | :--- |
| **Paginación & Ordenamiento** | `/ventas?page=2&sortField=precio&sortOrder=desc` | Obtiene la página 2 de ventas, ordenadas por `precio` de forma descendente. |
| **Ordenamiento Simple** | `/ventas?sortField=precio` | Ordena las ventas por `precio` (ascendente por defecto). |
| **Filtrado por Rango** | `/ventas?min_price=4000&max_price=5000` | Filtra ventas dentro de un rango de precios. |
| **Filtrado por Campo** | `/vendedores?name=Lucia` | Filtra vendedores cuyo nombre es "Lucia". |
| **Filtrado Relacional** | `/ventas?id_vendedor=1` | Filtra todas las ventas realizadas por el vendedor con `id_vendedor=1`. |

---

## 🛠️ Estructura de Datos (JSON Body)

A continuación, se detalla la estructura JSON esperada para las solicitudes (`POST` y `PUT`) y las respuestas (`GET`).

### Formato de Respuesta (`GET /:id`)

| Recurso | Ejemplo de Respuesta JSON |
| :--- | :--- |
| **Venta** | ```json { "id_venta": 1, "producto": "Monitor Smart HD Samsung", "precio": 10900.00, "id_vendedor": 1, "fecha": "2025-10-01" } ``` |
| **Vendedor** | ```json { "id": 1, "nombre": "Lucia", "telefono": 2494001, "email": "lucia@tienda.com" } ``` |

### Formato de Solicitud (`POST` y `PUT`)

| Recurso | Solicitud JSON (Body) |
| :--- | :--- |
| **Venta** (`POST/PUT`) | ```json { "producto": "______", "precio": ___, "id_vendedor": _, "fecha": "________" } ``` |
| **Vendedor** (`POST/PUT`) | ```json { "nombre": "______", "telefono": ______, "email": "______" } ``` |

> **Nota sobre `PUT`:** Para modificar un recurso (`PUT /:id`), el cuerpo de la solicitud debe incluir **todos los campos** de la entidad, no solo los que se van a modificar.

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

* [Ciencias Exactas](https://exa.unicen.edu.ar/) — Facultad de Ciencias Exactas, UNICEN.
* [TUDAI](https://www.unicen.edu.ar/content/tecnicatura-universitaria-en-desarrollo-de-aplicaciones-inform%C3%A1ticas-tudai) — Tecnicatura Universitaria en Desarrollo de Aplicaciones Informáticas.
* [WEB 2](https://tudai1-2.alumnos.exa.unicen.edu.ar/web-2) — Sitio de la cátedra.
* [Repositorio Parte 2](https://github.com/lumoreiralu/TPEspecial-web2-2025) — TPE - Parte 2: Sitio Web Dinámico.