-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-06-2025 a las 15:48:55
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tienda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

CREATE TABLE `compra` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `motivo` text DEFAULT NULL,
  `estado` enum('PENDIENTE','APROBADA','RECHAZADA','ENTREGADA','PAGADA') DEFAULT 'PENDIENTE',
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compra`
--

INSERT INTO `compra` (`id`, `producto_id`, `cantidad`, `motivo`, `estado`, `fecha`) VALUES
(1, 1, 2, 'ya no hay', 'PAGADA', '2025-06-13 13:46:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `id` int(11) NOT NULL,
  `nro_tramite` int(11) DEFAULT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_pedido`
--

INSERT INTO `detalle_pedido` (`id`, `nro_tramite`, `producto_id`, `cantidad`) VALUES
(1, 1, 1, 1),
(2, 2, 1, 1),
(3, 3, 1, 1),
(4, 4, 1, 2),
(5, 5, 1, 1),
(6, 6, 1, 1),
(7, 7, 1, 1),
(8, 8, 1, 1),
(9, 9, 1, 2),
(10, 10, 1, 2),
(11, 11, 1, 2),
(12, 12, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `devolucion`
--

CREATE TABLE `devolucion` (
  `id` int(11) NOT NULL,
  `nro_tramite` int(11) DEFAULT NULL,
  `motivo` text DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `estado` enum('PENDIENTE','APROBADA','RECHAZADA','FINALIZADA','REEMBOLSADO') DEFAULT 'PENDIENTE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `devolucion`
--

INSERT INTO `devolucion` (`id`, `nro_tramite`, `motivo`, `fecha`, `estado`) VALUES
(1, 2, 'feop', '2025-06-13 09:34:10', 'REEMBOLSADO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `flujoseguimiento`
--

CREATE TABLE `flujoseguimiento` (
  `id` int(11) NOT NULL,
  `nro_tramite` int(11) DEFAULT NULL,
  `flujo` varchar(50) DEFAULT NULL,
  `proceso` varchar(50) DEFAULT NULL,
  `usuario` varchar(50) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `observacion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `flujoseguimiento`
--

INSERT INTO `flujoseguimiento` (`id`, `nro_tramite`, `flujo`, `proceso`, `usuario`, `fecha`, `observacion`) VALUES
(1, 2, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-13 14:30:59', 'Venta confirmada'),
(2, 2, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-13 14:32:22', 'Stock OK'),
(3, 2, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-13 14:34:26', 'Stock OK'),
(4, 1, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-13 14:47:18', 'Venta confirmada por vendedor'),
(5, 3, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-13 14:47:23', 'Venta confirmada por vendedor'),
(6, 4, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-13 14:47:26', 'Venta confirmada por vendedor'),
(7, 5, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-13 14:47:29', 'Venta confirmada por vendedor'),
(8, 6, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-13 14:47:32', 'Venta confirmada por vendedor'),
(9, 7, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-13 14:47:34', 'Venta confirmada por vendedor'),
(10, 8, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-13 14:47:36', 'Venta confirmada por vendedor'),
(11, 9, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-13 14:47:39', 'Venta confirmada por vendedor'),
(12, 10, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-13 14:47:42', 'Venta confirmada por vendedor'),
(13, 3, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-13 14:49:17', 'Stock verificado'),
(14, 4, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-13 14:49:20', 'Stock verificado'),
(15, 1, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-13 14:49:25', 'Stock verificado'),
(16, 5, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-13 14:49:29', 'Stock verificado'),
(17, 6, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-13 14:49:32', 'Stock verificado'),
(18, 7, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-13 14:49:36', 'Stock verificado'),
(19, 8, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-13 14:49:38', 'Stock verificado'),
(20, 1, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-13 14:50:55', 'Pago EFECTIVO'),
(21, 2, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-13 14:51:01', 'Pago QR'),
(22, 3, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-13 14:51:05', 'Pago QR'),
(23, 6, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-13 14:51:08', 'Pago EFECTIVO'),
(24, 4, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-13 14:51:10', 'Pago EFECTIVO'),
(25, 7, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-13 14:51:13', 'Pago EFECTIVO'),
(26, 5, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-13 14:51:16', 'Pago EFECTIVO'),
(27, 8, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-13 14:51:18', 'Pago EFECTIVO'),
(28, 11, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-13 15:03:33', 'Venta confirmada por vendedor'),
(29, 12, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-13 15:18:31', 'Venta confirmada por vendedor'),
(30, 12, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-13 15:18:49', 'Stock verificado'),
(31, 12, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-13 15:19:03', 'Pago EFECTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id`, `nombre`, `descripcion`, `precio`, `stock`) VALUES
(1, 'Laptop HP', 'Laptop gama media', 4200.00, 2),
(2, 'Mouse Logitech', 'Mouse inalámbrico', 150.00, 29),
(3, 'Monitor Samsung', '24 pulgadas LED', 1100.00, 15);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tramite`
--

CREATE TABLE `tramite` (
  `nro_tramite` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `fecha_inicio` datetime DEFAULT NULL,
  `estado` enum('EN_PROCESO','FINALIZADO') DEFAULT 'EN_PROCESO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tramite`
--

INSERT INTO `tramite` (`nro_tramite`, `cliente_id`, `fecha_inicio`, `estado`) VALUES
(1, 1, '2025-06-13 08:23:00', 'FINALIZADO'),
(2, 1, '2025-06-13 08:30:33', 'FINALIZADO'),
(3, 1, '2025-06-13 08:35:01', 'FINALIZADO'),
(4, 1, '2025-06-13 08:35:07', 'FINALIZADO'),
(5, 1, '2025-06-13 08:44:22', 'FINALIZADO'),
(6, 1, '2025-06-13 08:44:57', 'FINALIZADO'),
(7, 1, '2025-06-13 08:45:07', 'FINALIZADO'),
(8, 1, '2025-06-13 08:45:17', 'FINALIZADO'),
(9, 1, '2025-06-13 08:46:58', 'EN_PROCESO'),
(10, 1, '2025-06-13 08:47:04', 'EN_PROCESO'),
(11, 1, '2025-06-13 09:03:19', 'EN_PROCESO'),
(12, 1, '2025-06-13 09:18:20', 'FINALIZADO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) DEFAULT NULL,
  `contraseña` varchar(255) DEFAULT NULL,
  `rol` enum('CLIENTE','VENDEDOR','ALMACEN','CAJERO') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `usuario`, `contraseña`, `rol`) VALUES
(1, 'cliente1', '1234', 'CLIENTE'),
(2, 'vendedor1', '1234', 'VENDEDOR'),
(3, 'almacen1', '1234', 'ALMACEN'),
(4, 'cajero1', '1234', 'CAJERO');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nro_tramite` (`nro_tramite`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `devolucion`
--
ALTER TABLE `devolucion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nro_tramite` (`nro_tramite`);

--
-- Indices de la tabla `flujoseguimiento`
--
ALTER TABLE `flujoseguimiento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nro_tramite` (`nro_tramite`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tramite`
--
ALTER TABLE `tramite`
  ADD PRIMARY KEY (`nro_tramite`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `compra`
--
ALTER TABLE `compra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `devolucion`
--
ALTER TABLE `devolucion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `flujoseguimiento`
--
ALTER TABLE `flujoseguimiento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tramite`
--
ALTER TABLE `tramite`
  MODIFY `nro_tramite` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compra`
--
ALTER TABLE `compra`
  ADD CONSTRAINT `compra_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`);

--
-- Filtros para la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD CONSTRAINT `detalle_pedido_ibfk_1` FOREIGN KEY (`nro_tramite`) REFERENCES `tramite` (`nro_tramite`),
  ADD CONSTRAINT `detalle_pedido_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`);

--
-- Filtros para la tabla `devolucion`
--
ALTER TABLE `devolucion`
  ADD CONSTRAINT `devolucion_ibfk_1` FOREIGN KEY (`nro_tramite`) REFERENCES `tramite` (`nro_tramite`);

--
-- Filtros para la tabla `flujoseguimiento`
--
ALTER TABLE `flujoseguimiento`
  ADD CONSTRAINT `flujoseguimiento_ibfk_1` FOREIGN KEY (`nro_tramite`) REFERENCES `tramite` (`nro_tramite`);

--
-- Filtros para la tabla `tramite`
--
ALTER TABLE `tramite`
  ADD CONSTRAINT `tramite_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `usuario` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
