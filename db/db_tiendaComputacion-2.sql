-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 15-10-2025 a las 19:54:20
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_tiendaComputacion`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `user` varchar(300) NOT NULL,
  `password` char(60) NOT NULL,
  `rol` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `user`, `password`, `rol`) VALUES
(3, 'webadmin', '$2y$10$3lLnMvtZDc6XmA1p34CgoekFeWzk6RfIApomoH4JR3Z8tzeVOWxPK', 'administrador'),
(4, 'admin', '$2y$10$4ab1m5wRaAHWYDklGBubxOW3XXEVss4BQjyN2/MQMpy72LiOlwh.6', 'administrador'),
(5, 'lucia', '$2y$10$.GU91NnRISEpi02K0FkKEe.r4nGmJ4zRdL9JONimGwe0sbOlUO2IW', 'vendedor'),
(6, 'manuel', '$2y$10$wK5d9MPmipOq.C3iWf/Xs.TA0IZabQT4nnJgW9oOi.z2VeouA8/1a', 'vendedor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vendedor`
--

CREATE TABLE `vendedor` (
  `id` int(11) NOT NULL,
  `nombre` varchar(300) NOT NULL,
  `telefono` int(11) NOT NULL,
  `email` varchar(300) NOT NULL,
  `imagen` varchar(300)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `vendedor`
--

INSERT INTO `vendedor` (`id`, `nombre`, `telefono`, `email`, `imagen`) VALUES
(1, 'Lucia M', 2494000001, 'lucia@tienda.com', 'img/default-user-img.jpg'),
(2, 'Manuel', 2494000005, 'manuel@tienda.com', 'img/default-user-img.jpg'),
(3, 'Carlos', 2494000003, 'carlos@tienda.com', 'img/default-user-img.jpg'),
(4, 'Pepito', 2494000004, 'pepito@tienda.com', 'img/default-user-img.jpg'),
(5, 'Juanita', 2494000009, 'atinaujuanita@tienda.com', 'img/default-user-img.jpg'),
(6, 'Ximena', 2314000001, 'ximena@tienda.com', 'img/default-user-img.jpg'),
(7, 'Panchito', 1214213002, 'pancho@tienda.com', 'img/default-user-img.jpg'),
(8, 'Zoe', 228405403, '1997.zoe@tienda.com', 'img/default-user-img.jpg'),
(9, 'Roberto', 248412004, 'el.rober@tienda.com', 'img/default-user-img.jpg'),
(10, 'Fernanda', 218614005, 'fernanda@tienda.com', 'img/default-user-img.jpg'),
(11, 'Raquel', 21253456, '123raquel@tienda.com', 'img/default-user-img.jpg'),
(12, 'Tito', 235236607, 'calderon@tienda.com', 'img/default-user-img.jpg'),
(13, 'Claudio', 246773718, 'g.claudio@tienda.com', 'img/default-user-img.jpg'),
(14, 'Nayla', 263678789, 'nay.la@tienda.com', 'img/default-user-img.jpg'),
(15, 'Marcos', 223567890, 'mail@tienda.com', 'img/default-user-img.jpg'),
(16, 'ultimo', 66666666, 'vendedor@tienda.com', 'img/default-user-img.jpg');




-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `id_venta` int(11) NOT NULL,
  `producto` varchar(255) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `id_vendedor` int(11) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `venta`
--

INSERT INTO `venta` (`id_venta`, `producto`, `precio`, `id_vendedor`, `fecha`) VALUES
(1, 'Monitor Smart HD Samsung', 310900.00, 1, '2025-10-01'),
(2, 'Teclado Mecanico Logitech', 3900.00, 2, '2025-10-06'),
(3, 'Parlante JBL Autotune', 8900.00, 1, '2025-10-02'),
(4, 'Mouse Inalámbrico Apple', 100900.00, 1, '2025-10-02'),
(5, 'Impresora Epson Stylus 2000', 189000.00, 2, '2025-08-07'),
(6, 'Microfono Influencer', 89000.00, 1, '2025-10-03'),
(7, 'Luz led para selfie', 9000.00, 2, '2025-09-12'),
(8, 'Modem Router Huawei HG8145V5', 84000.06, 3, '2025-09-15'),
(9, 'Raspberry Pi SBC 8GB', 169000.26, 4, '2025-09-15'),
(10, 'Joystick Playstation 5', 120000.00, 1, '2025-10-15'),
(11, 'Focusrite Scarlett Solo', 299999.99, 1, '2025-10-12'),
(12, 'Smartwatch Garmin Venu', 120000.00, 5, '2025-10-18'),
(13, 'Auriculares Sony WH-1000XM5', 98000.00, 5, '2025-10-19'),
(14, 'Teclado Mecánico Redragon', 18000.00, 6, '2025-10-18'),
(15, 'Mouse Logitech MX Master 3', 25000.00, 6, '2025-10-19'),
(16, 'Webcam Razer Kiyo', 22000.00, 6, '2025-10-20'),
(17, 'Cámara Instantánea Fujifilm', 32000.00, 7, '2025-10-21'),
(18, 'Micrófono USB Blue Yeti', 65000.00, 7, '2025-10-21'),
(19, 'Tablet Xiaomi Pad 6', 145000.00, 8, '2025-10-22'),
(20, 'Parlante JBL Charge 5', 28000.00, 8, '2025-10-23'),
(21, 'Mousepad Gamer XXL', 5000.00, 8, '2025-10-24'),
(22, 'Raspberry Pi 5 Model B', 189000.00, 9, '2025-10-23'),
(23, 'Cargador Inalámbrico Belkin', 4000.00, 9, '2025-10-24'),
(24, 'Auriculares HyperX Cloud II', 35000.00, 10, '2025-10-24'),
(25, 'Teclado Logitech G915', 92000.00, 10, '2025-10-24'),
(26, 'Altavoz Inteligente Google Nest', 18000.00, 11, '2025-10-25'),
(27, 'Disco SSD Western Digital 2TB', 54000.00, 11, '2025-10-25'),
(28, 'Micrófono Shure SM7B', 125000.00, 12, '2025-10-26'),
(36, 'Auriculares Inalámbricos JBL Tune 230', 14500.00, 5, '2025-10-30'),
(37, 'Cargador Portátil Anker 20000mAh', 9200.00, 5, '2025-11-01'),
(38, 'Teclado Mecánico Keychron K2', 28000.00, 6, '2025-10-29'),
(39, 'Monitor Samsung Curvo 27"', 175000.00, 7, '2025-10-30'),
(40, 'Mouse Gamer Razer Viper', 18500.00, 7, '2025-11-01'),
(41, 'Webcam Logitech StreamCam', 37000.00, 7, '2025-11-02'),
(42, 'Micrófono Condensador Behringer', 75000.00, 8, '2025-10-28'),
(43, 'Parlante Bluetooth Sony SRS-XB33', 25000.00, 9, '2025-10-31'),
(44, 'Raspberry Pi 4 8GB', 165000.00, 9, '2025-11-01'),
(45, 'Tablet Samsung Galaxy Tab S7', 215000.00, 10, '2025-10-29'),
(46, 'Altavoz Inteligente Amazon Echo', 22000.00, 11, '2025-10-30'),
(47, 'Mousepad Gamer XL', 3500.00, 11, '2025-11-01'),
(48, 'Auriculares HyperX Cloud II', 35000.00, 11, '2025-11-02');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `user name` (`user`);

--
-- Indices de la tabla `vendedor`
--
ALTER TABLE `vendedor`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_vendedor` (`id_vendedor`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `vendedor`
--
ALTER TABLE `vendedor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `venta_ibfk_1` 
  FOREIGN KEY (`id_vendedor`) REFERENCES `vendedor` (`id`)
  ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
