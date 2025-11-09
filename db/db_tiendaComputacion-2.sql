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
(1, 'Lucia M', 2494001, 'lucia@tienda.com', 'img/default-user-img.jpg'),
(2, 'Manuel', 2494002, 'manuel@tienda.com', 'img/68f2920fb3b78.png'),
(3, 'Carlos', 2494678, 'carlos@tienda.com', 'img/default-user-img.jpg'),
(4, 'Pepito', 1234321, 'pepito@tienda.com', 'img/default-user-img.jpg');

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
(6, 'Microfono Influencer ', 89000.00, 1, '2025-10-03'),
(7, 'Luz led para selfie ', 9000.00, 2, '2025-09-12'),
(8, 'Modem Router Huawei HG8145V5', 84000.06, 3, '2025-09-15'),
(9, 'Raspberry Pi SBC 8GB', 169000.26, 4, '2025-09-15');

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
