<?php
const MYSQL_USER = 'root';
const MYSQL_PASS = '';
const MYSQL_DB   = 'db_tiendaComputacion';
const MYSQL_HOST = 'localhost';

// valores predefinidos para las tablas;
const VENTAS = [
    ['Monitor Smart HD Samsung', 310900.00, 1, '2025-10-01'],
    ['Teclado Mecanico Logitech', 3900.00, 2, '2025-10-06'],
    ['Parlante JBL Autotune', 8900.00, 1, '2025-10-02'],
    ['Mouse Inalámbrico Apple', 100900.00, 1, '2025-10-02'],
    ['Impresora Epson Stylus 2000', 189000.00, 2, '2025-08-07'],
    ['Microfono Influencer', 89000.00, 1, '2025-10-03'],
    ['Luz led para selfie', 9000.00, 2, '2025-09-12'],
    ['Modem Router Huawei HG8145V5', 84000.06, 3, '2025-09-15'],
    ['Raspberry Pi SBC 8GB', 169000.26, 4, '2025-09-15'],
    ['Joystick Playstation 5', 120000.00, 1, '2025-10-15'],
    ['Focusrite Scarlett Solo', 299999.99, 1, '2025-10-12']
];

const VENDEDORES = [
    ['Lucia', 2494001, 'lucia@tienda.com'],
    ['Manuel', 2494002, 'manuel@tienda.com'],
    ['Carlos', 2494678, 'carlos@tienda.com'],
    ['Pepito', 1234321, 'pepito@tienda.com']
];

const USERS = [
    ['webadmin', '$2y$10$3lLnMvtZDc6XmA1p34CgoekFeWzk6RfIApomoH4JR3Z8tzeVOWxPK', 'administrador'],
    ['admin', '$2y$10$4ab1m5wRaAHWYDklGBubxOW3XXEVss4BQjyN2/MQMpy72LiOlwh.6', 'administrador'],
    ['lucia', '$2y$10$.GU91NnRISEpi02K0FkKEe.r4nGmJ4zRdL9JONimGwe0sbOlUO2IW', 'vendedor'],
    ['manuel', '$2y$10$wK5d9MPmipOq.C3iWf/Xs.TA0IZabQT4nnJgW9oOi.z2VeouA8/1a', 'vendedor']
];
