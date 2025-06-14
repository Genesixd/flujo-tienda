-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-06-2025 a las 06:44:35
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
(1, 1, 2, 'ya no hay', 'PAGADA', '2025-06-13 13:46:12'),
(2, 1, 6, 'motivo1', 'PAGADA', '2025-06-14 00:36:10'),
(3, 1, 19, 'reposición ', 'PAGADA', '2025-06-14 01:12:06'),
(4, 1, 3000, 'reposicion', 'PAGADA', '2025-06-14 02:24:30'),
(5, 3, 20, 'ya no hay', 'PAGADA', '2025-06-14 03:49:45'),
(6, 2, 2, 'xd', 'PAGADA', '2025-06-14 03:57:09'),
(7, 1, 31133131, 'aa', 'PAGADA', '2025-06-14 04:14:01'),
(8, 3, 5, 'monitores', 'PAGADA', '2025-06-14 04:18:26');

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
(12, 12, 2, 1),
(13, 13, 3, 3),
(14, 14, 3, 4),
(15, 15, 1, 3),
(16, 16, 1, 3),
(17, 17, 1, 1),
(18, 18, 1, 1),
(19, 19, 2, 1),
(20, 20, 1, 1),
(21, 21, 1, 1),
(22, 22, 1, 1),
(23, 23, 1, 3),
(24, 24, 1, 1),
(25, 25, 3, 2),
(26, 26, 2, 3),
(27, 27, 1, 3),
(28, 28, 1, 1234),
(29, 29, 1, 2),
(30, 30, 1, 1),
(31, 31, 1, 2),
(32, 32, 3, 1),
(33, 33, 1, 1),
(34, 34, 1, 2),
(35, 35, 1, 1),
(36, 36, 1, 1),
(37, 37, 1, 1),
(38, 38, 1, 1),
(39, 39, 1, 3131),
(40, 40, 1, 1);

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
(1, 2, 'feop', '2025-06-13 09:34:10', 'REEMBOLSADO'),
(2, 7, 'aaa', '2025-06-13 21:09:57', 'REEMBOLSADO'),
(3, 4, 'aaaa', '2025-06-13 21:10:02', 'REEMBOLSADO'),
(4, 9, 'aa', '2025-06-13 21:54:34', 'RECHAZADA'),
(5, 13, 'aaa', '2025-06-13 21:54:38', 'REEMBOLSADO'),
(6, 3, 'aaa', '2025-06-13 21:54:41', 'REEMBOLSADO'),
(7, 19, 'aaa', '2025-06-13 22:21:19', 'REEMBOLSADO'),
(8, 18, 'fafas', '2025-06-13 22:21:24', 'REEMBOLSADO'),
(9, 31, 'solicitud', '2025-06-13 22:26:22', 'REEMBOLSADO'),
(10, 37, 'oki', '2025-06-13 23:48:36', 'REEMBOLSADO'),
(11, 38, 'asd', '2025-06-13 23:56:21', 'REEMBOLSADO'),
(12, 33, 'dfafa', '2025-06-14 00:05:56', 'REEMBOLSADO'),
(13, 39, 'muy caro', '2025-06-14 00:17:07', 'REEMBOLSADO');

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
(31, 12, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-13 15:19:03', 'Pago EFECTIVO'),
(32, 14, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-14 02:32:21', 'Venta confirmada por vendedor'),
(33, 14, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-14 02:35:34', 'Stock verificado'),
(34, 9, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-14 02:35:42', 'Stock verificado'),
(35, 10, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-14 02:37:28', 'Stock verificado'),
(36, 11, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-14 02:37:36', 'Stock verificado'),
(37, 15, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-14 02:58:36', 'Venta confirmada por vendedor'),
(38, 16, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-14 03:08:51', 'Venta confirmada por vendedor'),
(39, 13, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-14 03:08:55', 'Venta confirmada por vendedor'),
(40, 17, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-14 03:08:58', 'Venta confirmada por vendedor'),
(41, 18, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-14 03:09:00', 'Venta confirmada por vendedor'),
(42, 19, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-14 03:09:04', 'Venta confirmada por vendedor'),
(43, 13, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-14 03:09:16', 'Stock verificado'),
(44, 19, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-14 03:09:29', 'Stock verificado'),
(45, 18, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-14 03:09:32', 'Stock verificado'),
(46, 17, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-14 03:09:36', 'Stock verificado'),
(47, 19, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-14 03:10:50', 'Pago EFECTIVO'),
(48, 18, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-14 03:10:54', 'Pago EFECTIVO'),
(49, 14, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-14 03:10:58', 'Pago EFECTIVO'),
(50, 13, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-14 03:11:01', 'Pago EFECTIVO'),
(51, 11, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-14 03:11:05', 'Pago EFECTIVO'),
(52, 17, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-14 03:11:07', 'Pago EFECTIVO'),
(53, 10, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-14 03:11:10', 'Pago EFECTIVO'),
(54, 9, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-14 03:11:12', 'Pago EFECTIVO'),
(55, 20, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-14 03:41:29', 'Venta confirmada por vendedor'),
(56, 21, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-14 03:50:10', 'Venta confirmada por vendedor'),
(57, 22, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-14 03:51:32', 'Venta confirmada por vendedor'),
(58, 23, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-14 03:53:09', 'Venta confirmada por vendedor'),
(59, 26, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-14 03:55:00', 'Venta confirmada por vendedor'),
(60, 25, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-14 03:57:35', 'Venta confirmada por vendedor'),
(61, 24, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-14 04:02:41', 'Venta confirmada por vendedor'),
(62, 26, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-14 04:04:31', 'Stock verificado'),
(63, 23, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-14 04:04:35', 'Stock verificado'),
(64, 28, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-14 04:08:45', 'Venta confirmada por vendedor'),
(65, 27, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-14 04:09:46', 'Venta confirmada por vendedor'),
(66, 29, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-14 04:18:58', 'Venta confirmada por vendedor'),
(67, 32, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-14 04:19:48', 'Venta confirmada por vendedor'),
(68, 31, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-14 04:20:57', 'Venta confirmada por vendedor'),
(69, 30, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-14 04:21:08', 'Venta confirmada por vendedor'),
(70, 32, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-14 04:22:39', 'Stock verificado'),
(71, 31, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-14 04:22:44', 'Stock verificado'),
(72, 22, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-14 04:22:48', 'Stock verificado'),
(73, 24, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-14 04:22:51', 'Stock verificado'),
(74, 27, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-14 04:22:54', 'Stock verificado'),
(75, 16, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-14 04:22:58', 'Stock verificado'),
(76, 25, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-14 04:23:02', 'Stock verificado'),
(77, 21, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-14 04:23:08', 'Stock verificado'),
(78, 32, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-14 04:23:18', 'Pago EFECTIVO'),
(79, 27, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-14 04:23:21', 'Pago EFECTIVO'),
(80, 25, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-14 04:23:24', 'Pago EFECTIVO'),
(81, 22, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-14 04:23:27', 'Pago EFECTIVO'),
(82, 21, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-14 04:23:29', 'Pago EFECTIVO'),
(83, 16, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-14 04:23:32', 'Pago EFECTIVO'),
(84, 24, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-14 04:23:34', 'Pago EFECTIVO'),
(85, 30, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-14 04:24:03', 'Stock verificado'),
(86, 29, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-14 04:24:06', 'Stock verificado'),
(87, 20, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-14 04:24:12', 'Stock verificado'),
(88, 28, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-14 04:24:59', 'Stock verificado'),
(89, 15, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-14 04:25:02', 'Stock verificado'),
(90, 31, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-14 04:25:16', 'Pago QR'),
(91, 30, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-14 04:25:19', 'Pago EFECTIVO'),
(92, 26, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-14 04:25:24', 'Pago TARJETA'),
(93, 28, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-14 04:25:32', 'Pago EFECTIVO'),
(94, 20, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-14 04:25:37', 'Pago EFECTIVO'),
(95, 23, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-14 04:25:40', 'Pago EFECTIVO'),
(96, 29, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-14 04:25:43', 'Pago EFECTIVO'),
(97, 15, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-14 04:25:47', 'Pago EFECTIVO'),
(98, 33, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-14 04:36:14', 'Venta confirmada por vendedor'),
(99, 33, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-14 04:48:59', 'Stock verificado'),
(100, 33, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-14 04:49:11', 'Pago EFECTIVO'),
(101, 34, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-14 05:18:59', 'Venta confirmada por vendedor'),
(102, 34, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-14 05:19:11', 'Stock verificado'),
(103, 34, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-14 05:19:22', 'Pago EFECTIVO'),
(104, 35, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-14 05:21:11', 'Venta confirmada por vendedor'),
(105, 35, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-14 05:21:26', 'Stock verificado'),
(106, 35, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-14 05:21:42', 'Pago EFECTIVO'),
(107, 35, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-14 05:30:20', 'Pago EFECTIVO'),
(108, 35, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-14 05:32:50', 'Pago EFECTIVO'),
(109, 36, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-14 05:34:11', 'Venta confirmada por vendedor'),
(110, 36, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-14 05:34:24', 'Stock verificado'),
(111, 36, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-14 05:46:53', 'Pago EFECTIVO'),
(112, 37, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-14 05:47:54', 'Venta confirmada por vendedor'),
(113, 37, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-14 05:48:05', 'Stock verificado'),
(114, 37, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-14 05:48:15', 'Pago EFECTIVO'),
(115, 38, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-14 05:55:41', 'Venta confirmada por vendedor'),
(116, 38, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-14 05:55:52', 'Stock verificado'),
(117, 38, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-14 05:56:00', 'Pago EFECTIVO'),
(118, 39, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-14 06:10:54', 'Venta confirmada por vendedor'),
(119, 39, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-14 06:16:27', 'Stock verificado'),
(120, 39, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-14 06:16:45', 'Pago QR'),
(121, 40, 'F1_venta_cliente', 'confirmaVenta', 'vendedor1', '2025-06-14 06:37:19', 'Venta confirmada por vendedor'),
(122, 40, 'F1_venta_cliente', 'verificaStock', 'almacen1', '2025-06-14 06:37:31', 'Stock verificado'),
(123, 40, 'F1_venta_cliente', 'cobraCliente', 'cajero1', '2025-06-14 06:37:41', 'Pago EFECTIVO');

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
(1, 'Laptop HP', 'Laptop gama media', 4200.00, 31131756),
(2, 'Mouse Logitech', 'Mouse inalámbrico', 150.00, 27),
(3, 'Monitor Samsung', '24 pulgadas LED', 1100.00, 30);

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
(9, 1, '2025-06-13 08:46:58', 'FINALIZADO'),
(10, 1, '2025-06-13 08:47:04', 'FINALIZADO'),
(11, 1, '2025-06-13 09:03:19', 'FINALIZADO'),
(12, 1, '2025-06-13 09:18:20', 'FINALIZADO'),
(13, 1, '2025-06-13 20:31:36', 'FINALIZADO'),
(14, 1, '2025-06-13 20:31:41', 'FINALIZADO'),
(15, 1, '2025-06-13 20:58:07', 'FINALIZADO'),
(16, 1, '2025-06-13 20:58:53', 'FINALIZADO'),
(17, 1, '2025-06-13 21:01:32', 'FINALIZADO'),
(18, 1, '2025-06-13 21:01:36', 'FINALIZADO'),
(19, 1, '2025-06-13 21:08:32', 'FINALIZADO'),
(20, 1, '2025-06-13 21:31:34', 'FINALIZADO'),
(21, 1, '2025-06-13 21:42:26', 'FINALIZADO'),
(22, 1, '2025-06-13 21:51:20', 'FINALIZADO'),
(23, 1, '2025-06-13 21:52:58', 'FINALIZADO'),
(24, 1, '2025-06-13 21:54:13', 'FINALIZADO'),
(25, 1, '2025-06-13 21:54:17', 'FINALIZADO'),
(26, 1, '2025-06-13 21:54:21', 'FINALIZADO'),
(27, 1, '2025-06-13 22:08:24', 'FINALIZADO'),
(28, 1, '2025-06-13 22:08:27', 'FINALIZADO'),
(29, 1, '2025-06-13 22:18:48', 'FINALIZADO'),
(30, 1, '2025-06-13 22:19:27', 'FINALIZADO'),
(31, 1, '2025-06-13 22:19:34', 'FINALIZADO'),
(32, 1, '2025-06-13 22:19:39', 'FINALIZADO'),
(33, 1, '2025-06-13 22:36:05', 'FINALIZADO'),
(34, 1, '2025-06-13 23:18:51', 'FINALIZADO'),
(35, 1, '2025-06-13 23:20:59', 'FINALIZADO'),
(36, 1, '2025-06-13 23:33:57', 'FINALIZADO'),
(37, 1, '2025-06-13 23:47:38', 'FINALIZADO'),
(38, 1, '2025-06-13 23:55:32', 'FINALIZADO'),
(39, 1, '2025-06-14 00:10:43', 'FINALIZADO'),
(40, 1, '2025-06-14 00:37:08', 'FINALIZADO');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `devolucion`
--
ALTER TABLE `devolucion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `flujoseguimiento`
--
ALTER TABLE `flujoseguimiento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tramite`
--
ALTER TABLE `tramite`
  MODIFY `nro_tramite` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

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
