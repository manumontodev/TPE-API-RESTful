# TPE Parte 3: API RESTful  - Tienda Computación
### [WEB 2 - TUDAI, UNICEN](#más-información)

Este es el repositorio de nuestra API de la [Tienda Computación](https://github.com/lumoreiralu/TPEspecial-web2-2025), la cual proporciona acceso a los datos de todos los vendedores almacenados en su base de datos, así como a todas sus ventas.

## Miembros del equipo
>- **[Lucia Moreira](https://github.com/luciamoreira96)** — `lulii.moreira96@gmail.com`  
>- **[Manuel Montoya](https://github.com/manumontodev)** — `montoya.christensen@outlook.com`

---

## Tabla de ruteo

La siguiente lista contiene todos los **endpoints** junto con sus **verbos HTTP** para realizar las operaciones de *Alta, Baja y Modificación*, además de listar las categorías y entidades:

### Tabla de ventas
- **GET** `/ventas` → obtiene la lista de todas las ventas.  
- **GET** `/ventas/:id` → obtiene el detalle de una venta por su ID.  
- **POST** `/ventas` → crea una nueva venta (incluir en el body todos los datos requeridos, [ver diagrama](#der)).  
- **PUT** `/ventas/:id` → modifica una venta existente por su ID.  
- **DELETE** `/ventas/:id` → elimina una venta por su ID.  

### Tabla de vendedores
- **GET** `/vendedores` → obtiene la lista de todos los vendedores.  
- **GET** `/vendedores/:id` → obtiene la información de un vendedor por su ID.  
- **GET** `/vendedores/:id/ventas` → obtiene todas las ventas de un vendedor específico.  
- **POST** `/vendedores` → da de alta un nuevo vendedor (incluir en el body los datos requeridos, [ver diagrama](#der)).  
- **PUT** `/vendedores/:id` → modifica los datos de un vendedor.  
- **DELETE** `/vendedores/:id` → elimina un vendedor por su ID.  

### Autenticación
- **GET** `/auth/login` → genera un token JWT, que debe incluirse en el encabezado de las solicitudes HTTP para acceder a los servicios de ABM.

---

## Instalación automática de la base de datos

Este sitio está configurado para realizar un auto-deploy de la base de datos.  
Para acceder al sitio solo es necesario tener corriendo [Apache](https://www.apachefriends.org/) y [phpMyAdmin](http://localhost/phpmyadmin/), y clonar este repositorio en la carpeta `htdocs`.

## Instalación manual de la base de datos

1. Abrir [phpMyAdmin](http://localhost/phpmyadmin/) en el navegador.  
2. Crear una nueva base de datos llamada `db_tiendaComputacion`.  
3. Seleccionar la base de datos.  
4. Hacer clic en la pestaña **Importar**.  
5. Seleccionar el archivo `db/db_tiendaComputacion.sql` de este proyecto.  
6. Hacer clic en **Continuar** para importar las tablas y datos.

---

## DER

<p align="center">
  <img width="640" height="330" alt="Diagrama Entidad-Relación" src="./DER tienda.jpg" /><br>
</p>

---

~~Este proyecto continúa la idea del [TPE - Parte 2: Sitio Web Dinámico](https://github.com/lumoreiralu/TPEspecial-web2-2025), agregando una API REST pública que permite consumir los servicios de ABM de vendedores y ventas.~~

---

### Más información
- [Ciencias Exactas](https://exa.unicen.edu.ar/) — Facultad de Ciencias Exactas, UNICEN.  
- [TUDAI](https://www.unicen.edu.ar/content/tecnicatura-universitaria-en-desarrollo-de-aplicaciones-inform%C3%A1ticas-tudai) — Tecnicatura Universitaria en Desarrollo de Aplicaciones Informáticas.  
- [WEB 2](https://tudai1-2.alumnos.exa.unicen.edu.ar/web-2) — Sitio de la cátedra.  

También te puede interesar: [UNICEN](https://www.unicen.edu.ar/)
