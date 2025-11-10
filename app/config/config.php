<?php
const MYSQL_USER = 'root';
const MYSQL_PASS = '';
const MYSQL_DB = 'db_tiendaComputacion';
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
    ['Focusrite Scarlett Solo', 299999.99, 1, '2025-10-12'],
    ['Smartwatch Garmin Venu', 120000.00, 5, '2025-10-18'],
    ['Auriculares Sony WH-1000XM5', 98000.00, 5, '2025-10-19'],
    ['Teclado Mecánico Redragon', 18000.00, 6, '2025-10-18'],
    ['Mouse Logitech MX Master 3', 25000.00, 6, '2025-10-19'],
    ['Webcam Razer Kiyo', 22000.00, 6, '2025-10-20'],
    ['Cámara Instantánea Fujifilm', 32000.00, 7, '2025-10-21'],
    ['Micrófono USB Blue Yeti', 65000.00, 7, '2025-10-21'],
    ['Tablet Xiaomi Pad 6', 145000.00, 8, '2025-10-22'],
    ['Parlante JBL Charge 5', 28000.00, 8, '2025-10-23'],
    ['Mousepad Gamer XXL', 5000.00, 8, '2025-10-24'],
    ['Raspberry Pi 5 Model B', 189000.00, 9, '2025-10-23'],
    ['Cargador Inalámbrico Belkin', 4000.00, 9, '2025-10-24'],
    ['Auriculares HyperX Cloud II', 35000.00, 10, '2025-10-24'],
    ['Teclado Logitech G915', 92000.00, 10, '2025-10-24'],
    ['Altavoz Inteligente Google Nest', 18000.00, 11, '2025-10-25'],
    ['Disco SSD Western Digital 2TB', 54000.00, 11, '2025-10-25'],
    ['Micrófono Shure SM7B', 125000.00, 12, '2025-10-26'],
    ['Monitor Curvo Samsung 32"', 198000.00, 12, '2025-10-26'],
    ['Tablet Samsung Galaxy Tab S9', 245000.00, 13, '2025-10-27'],
    ['Joystick Xbox Elite', 115000.00, 14, '2025-10-27'],
    ['Hub USB-C Anker 8 Puertos', 8500.00, 15, '2025-10-28'],
    ['Teclado Inalámbrico Microsoft', 7200.00, 15, '2025-10-28'],
    ['Cámara GoPro Hero 12', 325000.00, 16, '2025-10-29'],
    ['Auriculares Bose QuietComfort 45', 95000.00, 16, '2025-10-29'],
    ['Auriculares Inalámbricos JBL Tune 230', 14500.00, 5, '2025-10-30'],
    ['Cargador Portátil Anker 20000mAh', 9200.00, 5, '2025-11-01'],
    ['Teclado Mecánico Keychron K2', 28000.00, 6, '2025-10-29'],
    ['Monitor Samsung Curvo 27"', 175000.00, 7, '2025-10-30'],
    ['Mouse Gamer Razer Viper', 18500.00, 7, '2025-11-01'],
    ['Webcam Logitech StreamCam', 37000.00, 7, '2025-11-02'],
    ['Micrófono Condensador Behringer', 75000.00, 8, '2025-10-28'],
    ['Parlante Bluetooth Sony SRS-XB33', 25000.00, 9, '2025-10-31'],
    ['Raspberry Pi 4 8GB', 165000.00, 9, '2025-11-01'],
    ['Tablet Samsung Galaxy Tab S7', 215000.00, 10, '2025-10-29'],
    ['Altavoz Inteligente Amazon Echo', 22000.00, 11, '2025-10-30'],
    ['Mousepad Gamer XL', 3500.00, 11, '2025-11-01'],
    ['Auriculares HyperX Cloud II', 35000.00, 11, '2025-11-02'],
    ['Disco SSD Samsung 1TB', 55000.00, 12, '2025-10-30'],
    ['Joystick Xbox Series X', 95000.00, 13, '2025-10-31'],
    ['Teclado Inalámbrico Microsoft', 7200.00, 13, '2025-11-01'],
    ['Cámara GoPro Hero 11', 320000.00, 14, '2025-10-30'],
    ['Micrófono Rode NT1-A', 89000.00, 14, '2025-11-01'],
    ['Hub USB 3.0 7 Puertos', 6500.00, 15, '2025-10-31'],
    ['Smartwatch Garmin Forerunner', 98000.00, 15, '2025-11-01'],
    ['Parlante Bluetooth Sony', 14500.00, 15, '2025-11-02']
];

const VENDEDORES = [
    ['Lucia M', 2494000001, 'lucia@tienda.com'],
    ['Manuel', 2494000005, 'manuel@tienda.com'],
    ['Carlos', 2494000003, 'carlos@tienda.com'],
    ['Pepito', 2494000004, 'pepito@tienda.com'],
    ['Juanita', 2494000009, 'atinaujuanita@tienda.com'],
    ['Ximena', 2314000001, 'ximena@tienda.com'],
    ['Panchito', 1214213002, 'pancho@tienda.com'],
    ['Zoe', 228405403, '1997.zoe@tienda.com'],
    ['Roberto', 248412004, 'el.rober@tienda.com'],
    ['Fernanda', 218614005, 'fernanda@tienda.com'],
    ['Raquel', 21253456, '123raquel@tienda.com'],
    ['Tito', 235236607, 'calderon@tienda.com'],
    ['Claudio', 246773718, 'g.claudio@tienda.com'],
    ['Nayla', 263678789, 'nay.la@tienda.com'],
    ['Marcos', 223567890, 'mail@tienda.com'],
    ['ultimo', 66666666, 'vendedor@tienda.com']
];

const USERS = [
    ['webadmin', '$2y$10$3lLnMvtZDc6XmA1p34CgoekFeWzk6RfIApomoH4JR3Z8tzeVOWxPK', 'administrador'],
    ['admin', '$2y$10$4ab1m5wRaAHWYDklGBubxOW3XXEVss4BQjyN2/MQMpy72LiOlwh.6', 'administrador'],
    ['lucia', '$2y$10$.GU91NnRISEpi02K0FkKEe.r4nGmJ4zRdL9JONimGwe0sbOlUO2IW', 'vendedor'],
    ['manuel', '$2y$10$wK5d9MPmipOq.C3iWf/Xs.TA0IZabQT4nnJgW9oOi.z2VeouA8/1a', 'vendedor']
];
