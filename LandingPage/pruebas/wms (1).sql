-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-11-2024 a las 20:42:05
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
-- Base de datos: `wms`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas`
--

CREATE TABLE `cuentas` (
  `cuenta_id` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `tipo_cuenta` enum('usuario','personal') NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `personal_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cuentas`
--

INSERT INTO `cuentas` (`cuenta_id`, `nombre_usuario`, `contraseña`, `tipo_cuenta`, `usuario_id`, `personal_id`) VALUES
(1, 'santi', 'e516f979536994a14d9b0500bca3a1287b9ea9fe', 'personal', NULL, 1),
(3, 'santipro', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'usuario', 3, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `edificios`
--

CREATE TABLE `edificios` (
  `edificio_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `edificios`
--

INSERT INTO `edificios` (`edificio_id`, `nombre`) VALUES
(1, 'docencia 1'),
(2, 'docencia 2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estatus`
--

CREATE TABLE `estatus` (
  `estatus_id` int(11) NOT NULL,
  `estatus` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `material_id` int(11) NOT NULL,
  `serie` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `edificio_id` int(11) DEFAULT NULL,
  `estatus_id` int(11) NOT NULL,
  `tipo_material_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_prestamos`
--

CREATE TABLE `inventario_prestamos` (
  `prestamo_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_transaccion`
--

CREATE TABLE `inventario_transaccion` (
  `transaccion_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantenimiento`
--

CREATE TABLE `mantenimiento` (
  `mantenimiento_id` int(11) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_final` date DEFAULT NULL,
  `personal_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantenimiento_inventario`
--

CREATE TABLE `mantenimiento_inventario` (
  `mantenimiento_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personales`
--

CREATE TABLE `personales` (
  `personal_id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `primer_apellido` varchar(50) NOT NULL,
  `segundo_apellido` varchar(50) DEFAULT NULL,
  `edificio_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `personales`
--

INSERT INTO `personales` (`personal_id`, `nombre`, `primer_apellido`, `segundo_apellido`, `edificio_id`) VALUES
(1, 'Juan', 'Pérez', 'Gómez', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ppt`
--

CREATE TABLE `ppt` (
  `ppt_id` int(11) NOT NULL,
  `transaccion_id` int(11) DEFAULT NULL,
  `personal_id` int(11) DEFAULT NULL,
  `proveedor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

CREATE TABLE `prestamos` (
  `prestamo_id` int(11) NOT NULL,
  `fecha_salida` date NOT NULL,
  `fecha_devolucion` date DEFAULT NULL,
  `notas` text DEFAULT NULL,
  `personal_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `proveedor_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `correo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_material`
--

CREATE TABLE `tipo_material` (
  `tipo_material_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transacciones`
--

CREATE TABLE `transacciones` (
  `transaccion_id` int(11) NOT NULL,
  `tipo_transaccion` varchar(50) NOT NULL,
  `fecha_inicio` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_final` datetime DEFAULT NULL,
  `notas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `usuario_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('baja','alta') DEFAULT 'alta'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`usuario_id`, `nombre`, `descripcion`, `fecha_creacion`, `estado`) VALUES
(3, 'Juan Pérez', 'Administrador del sistema', '2024-11-14 06:29:43', 'alta');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  ADD PRIMARY KEY (`cuenta_id`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `personal_id` (`personal_id`),
  ADD KEY `idx_nombre_usuario` (`nombre_usuario`);

--
-- Indices de la tabla `edificios`
--
ALTER TABLE `edificios`
  ADD PRIMARY KEY (`edificio_id`);

--
-- Indices de la tabla `estatus`
--
ALTER TABLE `estatus`
  ADD PRIMARY KEY (`estatus_id`);

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`material_id`),
  ADD UNIQUE KEY `serie` (`serie`),
  ADD KEY `edificio_id` (`edificio_id`),
  ADD KEY `estatus_id` (`estatus_id`),
  ADD KEY `tipo_material_id` (`tipo_material_id`);

--
-- Indices de la tabla `inventario_prestamos`
--
ALTER TABLE `inventario_prestamos`
  ADD PRIMARY KEY (`prestamo_id`,`material_id`),
  ADD KEY `material_id` (`material_id`);

--
-- Indices de la tabla `inventario_transaccion`
--
ALTER TABLE `inventario_transaccion`
  ADD PRIMARY KEY (`transaccion_id`,`material_id`),
  ADD KEY `material_id` (`material_id`);

--
-- Indices de la tabla `mantenimiento`
--
ALTER TABLE `mantenimiento`
  ADD PRIMARY KEY (`mantenimiento_id`),
  ADD KEY `personal_id` (`personal_id`);

--
-- Indices de la tabla `mantenimiento_inventario`
--
ALTER TABLE `mantenimiento_inventario`
  ADD PRIMARY KEY (`mantenimiento_id`,`material_id`),
  ADD KEY `material_id` (`material_id`);

--
-- Indices de la tabla `personales`
--
ALTER TABLE `personales`
  ADD PRIMARY KEY (`personal_id`),
  ADD KEY `edificio_id` (`edificio_id`);

--
-- Indices de la tabla `ppt`
--
ALTER TABLE `ppt`
  ADD PRIMARY KEY (`ppt_id`),
  ADD KEY `transaccion_id` (`transaccion_id`),
  ADD KEY `personal_id` (`personal_id`),
  ADD KEY `proveedor_id` (`proveedor_id`);

--
-- Indices de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD PRIMARY KEY (`prestamo_id`),
  ADD KEY `personal_id` (`personal_id`),
  ADD KEY `fk_usuario` (`usuario_id`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`proveedor_id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indices de la tabla `tipo_material`
--
ALTER TABLE `tipo_material`
  ADD PRIMARY KEY (`tipo_material_id`);

--
-- Indices de la tabla `transacciones`
--
ALTER TABLE `transacciones`
  ADD PRIMARY KEY (`transaccion_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuario_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  MODIFY `cuenta_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `edificios`
--
ALTER TABLE `edificios`
  MODIFY `edificio_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `estatus`
--
ALTER TABLE `estatus`
  MODIFY `estatus_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `material_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `mantenimiento`
--
ALTER TABLE `mantenimiento`
  MODIFY `mantenimiento_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `personales`
--
ALTER TABLE `personales`
  MODIFY `personal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `ppt`
--
ALTER TABLE `ppt`
  MODIFY `ppt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `prestamo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `proveedor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipo_material`
--
ALTER TABLE `tipo_material`
  MODIFY `tipo_material_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `transacciones`
--
ALTER TABLE `transacciones`
  MODIFY `transaccion_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usuario_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cuentas`
--
ALTER TABLE `cuentas`
  ADD CONSTRAINT `cuentas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`),
  ADD CONSTRAINT `cuentas_ibfk_2` FOREIGN KEY (`personal_id`) REFERENCES `personales` (`personal_id`);

--
-- Filtros para la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD CONSTRAINT `inventario_ibfk_1` FOREIGN KEY (`edificio_id`) REFERENCES `edificios` (`edificio_id`),
  ADD CONSTRAINT `inventario_ibfk_2` FOREIGN KEY (`estatus_id`) REFERENCES `estatus` (`estatus_id`),
  ADD CONSTRAINT `inventario_ibfk_3` FOREIGN KEY (`tipo_material_id`) REFERENCES `tipo_material` (`tipo_material_id`);

--
-- Filtros para la tabla `inventario_prestamos`
--
ALTER TABLE `inventario_prestamos`
  ADD CONSTRAINT `inventario_prestamos_ibfk_1` FOREIGN KEY (`prestamo_id`) REFERENCES `prestamos` (`prestamo_id`),
  ADD CONSTRAINT `inventario_prestamos_ibfk_2` FOREIGN KEY (`material_id`) REFERENCES `inventario` (`material_id`);

--
-- Filtros para la tabla `inventario_transaccion`
--
ALTER TABLE `inventario_transaccion`
  ADD CONSTRAINT `inventario_transaccion_ibfk_1` FOREIGN KEY (`transaccion_id`) REFERENCES `transacciones` (`transaccion_id`),
  ADD CONSTRAINT `inventario_transaccion_ibfk_2` FOREIGN KEY (`material_id`) REFERENCES `inventario` (`material_id`);

--
-- Filtros para la tabla `mantenimiento`
--
ALTER TABLE `mantenimiento`
  ADD CONSTRAINT `mantenimiento_ibfk_1` FOREIGN KEY (`personal_id`) REFERENCES `personales` (`personal_id`);

--
-- Filtros para la tabla `mantenimiento_inventario`
--
ALTER TABLE `mantenimiento_inventario`
  ADD CONSTRAINT `mantenimiento_inventario_ibfk_1` FOREIGN KEY (`mantenimiento_id`) REFERENCES `mantenimiento` (`mantenimiento_id`),
  ADD CONSTRAINT `mantenimiento_inventario_ibfk_2` FOREIGN KEY (`material_id`) REFERENCES `inventario` (`material_id`);

--
-- Filtros para la tabla `personales`
--
ALTER TABLE `personales`
  ADD CONSTRAINT `personales_ibfk_1` FOREIGN KEY (`edificio_id`) REFERENCES `edificios` (`edificio_id`);

--
-- Filtros para la tabla `ppt`
--
ALTER TABLE `ppt`
  ADD CONSTRAINT `ppt_ibfk_1` FOREIGN KEY (`transaccion_id`) REFERENCES `transacciones` (`transaccion_id`),
  ADD CONSTRAINT `ppt_ibfk_2` FOREIGN KEY (`personal_id`) REFERENCES `personales` (`personal_id`),
  ADD CONSTRAINT `ppt_ibfk_3` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`proveedor_id`);

--
-- Filtros para la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD CONSTRAINT `fk_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`),
  ADD CONSTRAINT `prestamos_ibfk_1` FOREIGN KEY (`personal_id`) REFERENCES `personales` (`personal_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
