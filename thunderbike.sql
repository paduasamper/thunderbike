-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-12-2024 a las 15:29:19
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
-- Base de datos: `thunderbike`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `aumentar_inventario` (IN `p_id` INT, IN `p_cantidad` INT)   BEGIN
    UPDATE insumos
    SET cantidad = cantidad + p_cantidad
    WHERE id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `disminuir_inventario` (IN `p_id` INT, IN `p_cantidad` INT)   BEGIN
    UPDATE insumos
    SET cantidad = cantidad - p_cantidad
    WHERE id = p_id AND cantidad >= p_cantidad;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `numero_identificacion` varchar(30) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `correo` varchar(30) NOT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `numero_identificacion`, `nombre`, `direccion`, `telefono`, `correo`, `estado`) VALUES
(35, '1234567890', 'Marcos Llorente', 'Calle 32 #87-65', '3215648956', 'marcos.llorente@example.com', 1),
(36, '9876543210', 'Juan Pérez', 'Calle 123, Ciudad', '33211234569', 'juan.perez@example.com', 1),
(43, '1242545451', 'leon javier', 'Calle 87 #67-90', '33211234569', 'jashew@gmail.com', 1),
(46, '', 'Juan hgfdtr', 'Avenida Proveedor B, Pueblo', '3215648956', '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `fecha_factura` date NOT NULL,
  `estado` varchar(20) DEFAULT 'pendiente',
  `total` decimal(10,2) DEFAULT NULL,
  `productos` varchar(50) NOT NULL,
  `cantidad` varchar(50) NOT NULL,
  `vendedor` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`id`, `cliente_id`, `fecha_factura`, `estado`, `total`, `productos`, `cantidad`, `vendedor`) VALUES
(17, NULL, '2024-11-08', 'Cancelada', 250000.00, 'guaya', '', ''),
(21, 36, '2024-11-30', 'Pendiente', 250000.00, '[\"sadds\"]', '8', 'anderson acosta'),
(22, NULL, '2024-11-09', 'Cancelada', 20000.00, '[\"fvdvdv\"]', '', ''),
(23, 35, '2024-11-15', 'Credito', 20000.00, '[\"dczdc\",\"fvdvdvssssss\"]', '', ''),
(24, 36, '2024-12-25', 'Pendiente', 32233.00, '[\"sadds\"]', '3', 'anderson acosta'),
(28, 35, '2024-10-23', 'Credito', 400000.00, '[\"dczdc\"]', '10', 'anderson acosta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_compras`
--

CREATE TABLE `historial_compras` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `fecha_compra` datetime NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_compras`
--

INSERT INTO `historial_compras` (`id`, `cliente_id`, `producto_id`, `fecha_compra`, `total`, `descripcion`) VALUES
(1, 35, 41, '2024-11-15 00:00:00', 22222.00, 'fgfdyt');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_proveedores`
--

CREATE TABLE `historial_proveedores` (
  `id` int(11) NOT NULL,
  `proveedor_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `fecha_entrega` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_proveedores`
--

INSERT INTO `historial_proveedores` (`id`, `proveedor_id`, `producto_id`, `fecha_entrega`) VALUES
(5, 2, 41, '2024-11-12 16:19:35'),
(6, 1, 42, '2024-11-12 19:29:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `insumos`
--

CREATE TABLE `insumos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `imagen` blob NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `producto_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `insumos`
--

INSERT INTO `insumos` (`id`, `nombre`, `cantidad`, `descripcion`, `imagen`, `usuario_id`, `producto_id`) VALUES
(4, 'Bicicleta de montaña', 2, 'gfyutfyu', 0x2e2e5c75706c6f6164732f74657272656e6f2e706e67, NULL, 33),
(6, ' SAVADECK', 3, 'vxnsf', 0x2e2e5c75706c6f6164732f6269636c657461206465206d7565737472612e6a7067, NULL, 35),
(12, 'Casco de ciclismo', 20, 'sfhrw', 0x2e2e5c75706c6f6164732f434153434f204445204d5545535452412e6a70672e77656270, NULL, 41),
(13, ' SAVADECK', 63, 'ghefwejft', 0x2e2e5c75706c6f6164732f42494349204d5545535452412e77656270, NULL, 42);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `imagen` blob DEFAULT NULL,
  `cantidad` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `imagen`, `cantidad`) VALUES
(33, 'Bicicleta de montaña', 'gfyutfyu', 1111111.00, 0x2e2e5c75706c6f6164732f74657272656e6f2e706e67, 2),
(35, ' SAVADECK', 'vxnsf', 2222222.00, 0x2e2e5c75706c6f6164732f6269636c657461206465206d7565737472612e6a7067, 3),
(41, 'Casco de ciclismo', 'sfhrw', 22222.00, 0x2e2e5c75706c6f6164732f434153434f204445204d5545535452412e6a70672e77656270, 20),
(42, ' SAVADECK', 'ghefwejft', 1122526.00, 0x2e2e5c75706c6f6164732f42494349204d5545535452412e77656270, 63);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_proveedores`
--

CREATE TABLE `productos_proveedores` (
  `producto_id` int(11) NOT NULL,
  `proveedor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id`, `nombre`, `direccion`, `telefono`) VALUES
(1, 'Proveedor A', 'Calle Proveedor A, Ciudad', '111111111'),
(2, 'Proveedor B', 'Avenida Proveedor B, Pueblo', '222222222');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reparaciones`
--

CREATE TABLE `reparaciones` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `costo` decimal(10,2) DEFAULT NULL,
  `fecha_reparacion` date DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `mecanico_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reparaciones`
--

INSERT INTO `reparaciones` (`id`, `cliente_id`, `producto_id`, `descripcion`, `costo`, `fecha_reparacion`, `usuario_id`, `mecanico_id`) VALUES
(25, 35, 41, 'GHJJHBs', 344444.00, '2024-11-22', 15, NULL),
(26, 35, 42, 'JGJVGHVGHVH', 6.00, '2024-11-22', 15, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reparaciones_proveedores`
--

CREATE TABLE `reparaciones_proveedores` (
  `reparacion_id` int(11) NOT NULL,
  `proveedor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `rol` varchar(255) NOT NULL DEFAULT 'usuario',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_modificacion` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `correo`, `clave`, `rol`, `fecha_creacion`, `fecha_modificacion`) VALUES
(10, 'anderson acosta ', 'anderson12@yahoo.com', '827ccb0eea8a706c4c34a16891f84e7b', 'vendedor', '2024-03-29 05:03:01', NULL),
(14, 'javier leon', 'javi@hotmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 'administrador', '2024-03-29 05:14:11', NULL),
(15, 'johan samper', 'johan123@yahoo.com', '01cfcd4f6b8770febfb40cb906715822', 'mecanico', '2024-03-29 05:15:45', NULL),
(16, 'javier', 'leon@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 'mecanico', '2024-08-24 13:56:31', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `fecha_venta` date DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `descripcion_venta` text DEFAULT NULL,
  `producto_vendido_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `vendedor` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `cliente_id`, `fecha_venta`, `total`, `descripcion_venta`, `producto_vendido_id`, `usuario_id`, `vendedor`) VALUES
(26, 35, '2024-11-15', 22222.00, 'fgfdyt', 41, 10, '');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_identificacion` (`numero_identificacion`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Indices de la tabla `historial_compras`
--
ALTER TABLE `historial_compras`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `historial_proveedores`
--
ALTER TABLE `historial_proveedores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proveedor_id` (`proveedor_id`),
  ADD KEY `historial_proveedores_ibfk_2` (`producto_id`);

--
-- Indices de la tabla `insumos`
--
ALTER TABLE `insumos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `insumos_ibfk_1` (`usuario_id`),
  ADD KEY `insumos_ibfk_2` (`producto_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos_proveedores`
--
ALTER TABLE `productos_proveedores`
  ADD PRIMARY KEY (`producto_id`,`proveedor_id`),
  ADD KEY `proveedor_id` (`proveedor_id`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reparaciones`
--
ALTER TABLE `reparaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `reparaciones_ibfk_3` (`usuario_id`);

--
-- Indices de la tabla `reparaciones_proveedores`
--
ALTER TABLE `reparaciones_proveedores`
  ADD PRIMARY KEY (`reparacion_id`,`proveedor_id`),
  ADD KEY `proveedor_id` (`proveedor_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `producto_vendido_id` (`producto_vendido_id`),
  ADD KEY `ventas_ibfk_3` (`usuario_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `historial_compras`
--
ALTER TABLE `historial_compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `historial_proveedores`
--
ALTER TABLE `historial_proveedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `insumos`
--
ALTER TABLE `insumos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `reparaciones`
--
ALTER TABLE `reparaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `facturas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `historial_proveedores`
--
ALTER TABLE `historial_proveedores`
  ADD CONSTRAINT `historial_proveedores_ibfk_1` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`),
  ADD CONSTRAINT `historial_proveedores_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `insumos`
--
ALTER TABLE `insumos`
  ADD CONSTRAINT `insumos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `insumos_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `productos_proveedores`
--
ALTER TABLE `productos_proveedores`
  ADD CONSTRAINT `productos_proveedores_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `productos_proveedores_ibfk_2` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`);

--
-- Filtros para la tabla `reparaciones`
--
ALTER TABLE `reparaciones`
  ADD CONSTRAINT `reparaciones_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reparaciones_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `reparaciones_ibfk_3` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `reparaciones_proveedores`
--
ALTER TABLE `reparaciones_proveedores`
  ADD CONSTRAINT `reparaciones_proveedores_ibfk_1` FOREIGN KEY (`reparacion_id`) REFERENCES `reparaciones` (`id`),
  ADD CONSTRAINT `reparaciones_proveedores_ibfk_2` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`producto_vendido_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
