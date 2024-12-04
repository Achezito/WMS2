-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-12-2024 a las 18:30:18
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

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
  `tipo_cuenta` enum('usuario','personal','administrador') DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `personal_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cuentas`
--

INSERT INTO `cuentas` (`cuenta_id`, `nombre_usuario`, `contraseña`, `tipo_cuenta`, `usuario_id`, `personal_id`) VALUES
(1, 'santi', 'e516f979536994a14d9b0500bca3a1287b9ea9fe', 'personal', NULL, 1),
(3, 'santipro', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'usuario', 3, NULL),
(4, 'm_torres', 'ff37a98a9963d347e9749a5c1b3936a4a245a6ff', 'usuario', 2, NULL),
(5, 'l_mendez', '6869bcdc0668dbaa605fa8c2e54cba51fc74b3af', 'usuario', 1, NULL),
(6, 'r_castillo', '5685d2d94cc204b6a183bdf4d04ec9d9abb0cfd6', 'usuario', 4, NULL),
(7, 's_ramirez', '88f2e409251ab59ad72f1bcca67fb4593d0260cc', 'usuario', 5, NULL),
(8, 'a_ruiz', 'a2b1218cee94259653724b3d97d77dc45628e606', 'usuario', 6, NULL),
(9, 'gamypro', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'usuario', 7, NULL),
(12, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'administrador', NULL, NULL),
(14, 'SoyPro', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'usuario', 10, NULL),
(15, 'holamundo', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'usuario', 11, NULL),
(16, 'sektik', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'usuario', 12, NULL),
(17, 'Sekrok', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'personal', NULL, 5),
(18, 'Sekrok1', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'personal', NULL, 6),
(19, 'Sekrok123', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'personal', NULL, 7),
(20, 'sektik2', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'usuario', 13, NULL),
(21, 'Santimijas', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'usuario', 14, NULL),
(22, 'Santimijas1', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'usuario', 15, NULL),
(23, 'Santimijas12', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'usuario', 16, NULL),
(24, 'asfasfs', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'usuario', 17, NULL),
(25, 'sektik3', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'usuario', 18, NULL),
(26, 'sektik4', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'usuario', 19, NULL),
(27, 'mariEsmiAmor', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'usuario', 20, NULL),
(28, 'admin324', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'usuario', 21, NULL),
(29, 'admin3242', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'usuario', 22, NULL),
(30, 'mariana2', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'usuario', 23, NULL),
(31, 'Juan01', '3d4f2bf07dc1be38b20cd6e46949a1071f9d0e3d', 'usuario', 24, NULL),
(32, 'Juan10', '39cebfad161e838026b367a33659e709a3bc8b6b', 'usuario', 25, NULL);

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
(2, 'docencia 2'),
(3, 'Laboratorio de Informática'),
(4, 'Centro de Cómputo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estatus`
--

CREATE TABLE `estatus` (
  `estatus_id` int(11) NOT NULL,
  `estatus` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estatus`
--

INSERT INTO `estatus` (`estatus_id`, `estatus`) VALUES
(1, 'Disponible'),
(2, 'En uso'),
(3, 'En mantenimiento'),
(4, 'Fuera de servicio');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `historial_mantenimientos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `historial_mantenimientos` (
`operacion_id` int(11)
,`tipo_material_nombre` varchar(100)
,`tipo_material_categoria` varchar(50)
,`descripcion` varchar(250)
,`notas` text
,`serie` varchar(50)
,`modelo` varchar(50)
,`fecha_inicio` date
,`fecha_final` date
,`responsable` varchar(152)
,`edificio_id` int(11)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `historial_materiales`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `historial_materiales` (
`tipo_operacion` varchar(13)
,`operacion_id` int(11)
,`material_id` int(11)
,`material_nombre` varchar(100)
,`serie` varchar(50)
,`modelo` varchar(50)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `historial_operaciones`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `historial_operaciones` (
`tipo_operacion` varchar(13)
,`operacion_id` int(11)
,`fecha_inicio` datetime
,`fecha_final` datetime
,`notas` mediumtext
,`responsable` varchar(152)
,`edificio_id` int(11)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `historial_prestamos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `historial_prestamos` (
`operacion_id` int(11)
,`notas` text
,`usuario_id` int(11)
,`solicitado_por` varchar(100)
,`estatus` enum('pendiente','aprobado','rechazado','finalizado')
,`responsable` varchar(152)
,`fecha_salida` date
,`fecha_devolucion` date
,`edificio_id` int(11)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `historial_prestamos_usuario`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `historial_prestamos_usuario` (
`operacion_id` int(11)
,`notas` text
,`usuario_id` int(11)
,`solicitado_por` varchar(100)
,`estatus` enum('pendiente','aprobado','rechazado','finalizado')
,`responsable` varchar(152)
,`fecha_salida` date
,`fecha_devolucion` date
,`edificio_id` int(11)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `historial_transacciones`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `historial_transacciones` (
`operacion_id` int(11)
,`tipo_transaccion` enum('Entrada','Salida')
,`fecha_inicio` timestamp
,`fecha_final` datetime
,`notas` text
,`proveedor_nombre` varchar(100)
,`proveedor_telefono` varchar(15)
,`proveedor_correo` varchar(100)
,`personal_nombre` varchar(152)
,`edificio_id` int(11)
);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`material_id`, `serie`, `modelo`, `edificio_id`, `estatus_id`, `tipo_material_id`) VALUES
(1, 'ABC123', 'Dell Inspiron', 1, 3, 1),
(2, 'DEF456', 'Epson EB-X51', 1, 3, 2),
(3, 'GHI789', 'IKEA LACK', 3, 4, 3),
(4, 'JKL012', 'ErgoChair 2', 1, 2, 4),
(5, 'MNO345', 'HP LaserJet', 2, 1, 5),
(6, 'PQR678', 'Samsung 55-inch', 3, 1, 6),
(7, 'STU901', 'Asus ZenBook', 1, 2, 1),
(8, 'VWX234', 'Canon PIXMA', 2, 1, 5),
(9, 'YZA567', 'LG 65-inch', 3, 1, 6),
(10, 'BCD890', 'Logitech Spotlight', 1, 2, 2),
(21, 'AODKUE8376', 'DELL Inspiron C21', 1, 4, 1),
(22, 'U87YDT5RSF', 'DELL Inspiron C21', 1, 4, 1),
(23, 'HADAYS7', 'HP C300', 1, 4, 2),
(24, '37D7GADH', 'HP C300', 1, 4, 2),
(25, '3UAUD8QWD8', 'HP C300', 1, 4, 2),
(26, '89473298578932', 'HP C200', 1, 1, 1),
(27, '123456', 'Laptop Asus 5G', 1, 4, 1),
(28, '123434', 'Laptop Asus 5G', 1, 4, 1),
(29, '675768', 'Laptop Asus 5G', 1, 4, 1),
(30, 'AAAAAAAAAAA', 'ASUS A10', 1, 1, NULL),
(31, 'BBBBBBBBBBB', 'ASUS A10', 1, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_prestamos`
--

CREATE TABLE `inventario_prestamos` (
  `prestamo_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `inventario_prestamos`
--

INSERT INTO `inventario_prestamos` (`prestamo_id`, `material_id`) VALUES
(1, 7),
(2, 10),
(3, 2),
(5, 1),
(9, 4),
(9, 10),
(11, 7),
(11, 10),
(12, 26),
(13, 2),
(14, 4),
(16, 26),
(17, 7),
(17, 26),
(18, 7),
(19, 1),
(19, 26);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_transaccion`
--

CREATE TABLE `inventario_transaccion` (
  `transaccion_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `personal_id` int(11) NOT NULL,
  `proveedor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `inventario_transaccion`
--

INSERT INTO `inventario_transaccion` (`transaccion_id`, `material_id`, `personal_id`, `proveedor_id`) VALUES
(1, 1, 1, 1),
(1, 2, 1, 1),
(2, 3, 2, 2),
(4, 10, 1, 1),
(8, 21, 1, 1),
(8, 22, 1, 1),
(8, 23, 1, 1),
(8, 24, 1, 1),
(8, 25, 1, 1),
(9, 21, 1, 1),
(9, 22, 1, 1),
(9, 23, 1, 1),
(9, 24, 1, 1),
(9, 25, 1, 1),
(12, 27, 1, 1),
(12, 28, 1, 1),
(12, 29, 1, 1),
(13, 27, 1, 1),
(13, 28, 1, 1),
(13, 29, 1, 1),
(14, 30, 1, 2),
(14, 31, 1, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantenimiento`
--

CREATE TABLE `mantenimiento` (
  `mantenimiento_id` int(11) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_final` date DEFAULT NULL,
  `notas` text DEFAULT NULL,
  `personal_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `mantenimiento`
--

INSERT INTO `mantenimiento` (`mantenimiento_id`, `descripcion`, `fecha_inicio`, `fecha_final`, `notas`, `personal_id`) VALUES
(1, 'Revisión general de laptops', '2024-11-03', '2024-11-29', 'Exito', 1),
(2, 'Reparación de proyector', '2024-11-05', '2024-11-06', 'Se cambio el lente del proyector', 2),
(3, 'Reparación de impresora', '2024-11-07', '2024-11-19', 'Reemplazo de cabezal de impresión', 3),
(4, 'Limpieza de mobiliario', '2024-11-18', '2024-11-18', 'Se limpio la mesa', 3),
(5, 'Limpieza interna de componentes.', '2024-11-26', '2024-11-26', 'Se limpiaron con éxito los componentes', 1),
(6, 'Cambio de lente y ajuste de configuracion', '2024-11-26', '2024-11-26', 'Se realizo con exito', 1),
(7, 'Reparacion procesador', '2024-11-29', NULL, NULL, 1),
(8, 'Manteniminto de limpieza de equipo', '2024-12-02', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantenimiento_inventario`
--

CREATE TABLE `mantenimiento_inventario` (
  `mantenimiento_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `mantenimiento_inventario`
--

INSERT INTO `mantenimiento_inventario` (`mantenimiento_id`, `material_id`) VALUES
(1, 1),
(2, 2),
(3, 5),
(4, 3),
(5, 10),
(6, 2),
(7, 2),
(8, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personales`
--

CREATE TABLE `personales` (
  `personal_id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `primer_apellido` varchar(50) NOT NULL,
  `segundo_apellido` varchar(50) DEFAULT NULL,
  `telefono` varchar(20) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `edificio_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `personales`
--

INSERT INTO `personales` (`personal_id`, `nombre`, `primer_apellido`, `segundo_apellido`, `telefono`, `correo`, `edificio_id`) VALUES
(1, 'Juan', 'Pérez', 'Gómez', '6641239481', 'juanpgomez@ut-tijuana.edu.mx', 1),
(2, 'Ana', 'López', 'Martínez', '6649876283', 'analm@ut-tijuana.edu.mx', 2),
(3, 'Carlos', 'Sánchez', 'Hernández', '6641230982', 'carlossh@ut-tijuana.edu.mx', 3),
(5, 'Santimijas', 'lolaazo', 'lolaazo', '3243253252', 'elmej@gmail.com', 1),
(6, 'Santimijas', 'lolaazo', 'lolaazo', '3243253252', 'elmejs@gmail.com', 1),
(7, 'Santimijas', 'lolaazo', 'lolaazo', '3243253252', 'elm2ejs@gmail.com', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

CREATE TABLE `prestamos` (
  `prestamo_id` int(11) NOT NULL,
  `fecha_salida` date NOT NULL,
  `fecha_devolucion` date DEFAULT NULL,
  `estatus` enum('pendiente','aprobado','rechazado','finalizado') DEFAULT NULL,
  `notas` text DEFAULT 'NULL',
  `personal_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `prestamos`
--

INSERT INTO `prestamos` (`prestamo_id`, `fecha_salida`, `fecha_devolucion`, `estatus`, `notas`, `personal_id`, `usuario_id`) VALUES
(1, '2024-11-25', '2024-11-26', 'finalizado', 'Solicitud de laptop asus', 1, 1),
(2, '2024-11-08', '2024-11-29', 'finalizado', 'Solicitud de proyector', 1, 2),
(3, '2024-11-09', NULL, 'pendiente', 'Solicitud de proyector epson', NULL, 3),
(5, '2024-11-22', '2024-11-26', 'rechazado', 'Material en mantenimiento', 1, 1),
(9, '2024-11-26', NULL, 'aprobado', 'asd', 1, 10),
(11, '2024-11-26', '2024-11-27', 'finalizado', 'holamundo', 1, 11),
(12, '2024-11-26', '2024-11-26', 'finalizado', 'quiero una laptop', 1, 12),
(13, '2024-11-26', '2024-11-26', 'finalizado', 'asddsa', 1, 12),
(14, '2024-11-26', '2024-12-02', 'rechazado', 'En mantenimiento', 1, 12),
(16, '2024-11-28', NULL, 'pendiente', 'asdsad', NULL, 11),
(17, '2024-11-29', '2024-11-29', 'rechazado', 'Rechazo ', 1, 23),
(18, '2024-11-29', NULL, 'aprobado', 'hol', 1, 23),
(19, '2024-12-02', '2024-12-02', 'rechazado', 'Latosa\r\n', 1, 23);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `prestamos_usuarios`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `prestamos_usuarios` (
`operacion_id` int(11)
,`notas` text
,`estatus` enum('pendiente','aprobado','rechazado','finalizado')
,`materiales` mediumtext
,`usuario_id` int(11)
,`Responsable` varchar(152)
,`fecha_salida` date
,`fecha_devolucion` date
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `prestamos_usuarios_edificio`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `prestamos_usuarios_edificio` (
`edificio_id` int(11)
,`operacion_id` int(11)
,`notas` text
,`estatus` enum('pendiente','aprobado','rechazado','finalizado')
,`modelo` varchar(50)
,`usuario_id` int(11)
,`Responsable` varchar(152)
,`fecha_salida` date
,`fecha_devolucion` date
);

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

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`proveedor_id`, `nombre`, `telefono`, `correo`) VALUES
(1, 'Proveedor Tech', '555-1234', 'juan.ortega@tech.com'),
(2, 'Equipos y Más', '555-5678', 'maria.lopez@equiposymas.com'),
(3, 'Mobiliario Moderno', '555-9101', 'carlos.herrera@mobiliariomoderno.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_material`
--

CREATE TABLE `tipo_material` (
  `tipo_material_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipo_material`
--

INSERT INTO `tipo_material` (`tipo_material_id`, `nombre`, `categoria`, `descripcion`) VALUES
(1, 'Laptop', 'Electrónica', 'Equipo portátil de computación'),
(2, 'Proyector', 'Audiovisual', 'Equipo para presentaciones visuales'),
(3, 'Mesa', 'Mobiliario', 'Mobiliario para oficinas o salones'),
(4, 'Silla', 'Mobiliario', 'Silla ergonómica para oficina'),
(5, 'Impresora', 'Electrónica', 'Impresora multifuncional'),
(6, 'Pantalla', 'Audiovisual', 'Pantalla LED para proyecciones');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transacciones`
--

CREATE TABLE `transacciones` (
  `transaccion_id` int(11) NOT NULL,
  `tipo_transaccion` enum('Entrada','Salida') NOT NULL,
  `fecha_inicio` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_final` datetime DEFAULT NULL,
  `notas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `transacciones`
--

INSERT INTO `transacciones` (`transaccion_id`, `tipo_transaccion`, `fecha_inicio`, `fecha_final`, `notas`) VALUES
(1, 'Entrada', '2024-11-01 17:00:00', '2024-11-01 12:00:00', 'Compra inicial de materiales'),
(2, 'Salida', '2024-11-02 16:00:00', '2024-11-02 11:00:00', 'Devolución de equipos dañados'),
(4, 'Entrada', '2024-11-04 22:00:00', '2024-11-04 16:00:00', 'Adquisición de nuevos equipos audiovisuales'),
(8, 'Entrada', '2024-11-26 08:00:00', NULL, 'Compra de 2 laptops DELL Inspiron C21 y 3 proyectores HP C300'),
(9, 'Salida', '2024-11-26 08:00:00', '2024-11-26 00:00:00', 'Devolucion, no servian.'),
(10, 'Entrada', '2024-11-26 08:00:00', NULL, 'Entrada de prueba de materiales'),
(11, 'Entrada', '2024-11-27 08:00:00', NULL, ''),
(12, 'Entrada', '2024-11-29 08:00:00', NULL, 'Compra de 2 laptops ASUS 5G'),
(13, 'Salida', '2024-11-29 08:00:00', '2024-11-29 00:00:00', 'Falla del cpu'),
(14, 'Entrada', '2024-12-02 08:00:00', NULL, 'Laptop Nueva');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `usuario_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('baja','alta') DEFAULT 'alta',
  `telefono` int(11) NOT NULL,
  `correo` varchar(50) DEFAULT NULL,
  `edificio_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`usuario_id`, `nombre`, `fecha_creacion`, `estado`, `telefono`, `correo`, `edificio_id`) VALUES
(1, 'Pedro Gómez', '2024-01-01 18:00:00', 'alta', 0, NULL, 1),
(2, 'María Torres', '2024-01-02 19:00:00', 'alta', 0, NULL, 1),
(3, 'Juan Pérez', '2024-11-14 06:29:43', 'alta', 0, NULL, 2),
(4, 'Roberto Castillo', '2024-07-01 21:00:00', 'alta', 0, NULL, 1),
(5, 'Sofía Ramírez', '2024-09-15 17:30:00', 'alta', 0, NULL, 1),
(6, 'Alejandro Ruiz', '2024-10-01 19:00:00', 'alta', 0, NULL, 1),
(7, 'Gameros Perez Salazar', '2024-11-22 16:00:46', 'alta', 0, 'tijuana@edu.mx', 1),
(10, 'Garcia Hernandez', '2024-11-26 17:18:51', 'alta', 0, 'achezzini@hotmail.com', 1),
(11, 'Holamundo', '2024-11-26 17:38:37', 'alta', 0, 'pru2e@gmail.com', 1),
(12, 'Christian Martinez', '2024-11-26 18:29:32', 'alta', 0, 'prueba2@gmail.com', 1),
(13, 'Christian Martinez', '2024-11-28 03:10:07', 'alta', 0, 'prueba3@gmail.com', 1),
(14, 'Santiago', '2024-11-28 03:23:12', 'alta', 0, 'elmejorassfasf@gmail.com', 1),
(15, 'Santiago', '2024-11-28 03:26:30', 'alta', 0, 'elmejorasdsfasf@gmail.com', 1),
(16, 'Santiago', '2024-11-28 03:27:42', 'alta', 0, 'esf@gmail.com', 1),
(17, 'Hector', '2024-11-28 03:29:22', 'alta', 0, 'elmes@gmail.com', 1),
(18, 'Christian Martinez', '2024-11-28 20:18:47', 'alta', 0, 'prueb3@gmail.com', 1),
(19, 'Christian Martinez', '2024-11-28 20:19:31', 'alta', 0, 'pru3@gmail.com', 3),
(20, 'Mariana Martinez Valenzuela', '2024-11-29 16:35:54', 'alta', 0, 'marianamtz@gmail.com', 1),
(21, 'Mariana Martinez Valenzuela', '2024-11-29 16:37:38', 'alta', 0, 'mariana@gmail.com', 2),
(22, 'Mariana Martinez Valenzuela', '2024-11-29 16:38:43', 'alta', 0, 'mariamtz@gmail.com', 1),
(23, 'Mariana Martinez Valenzuela', '2024-11-29 16:57:09', 'alta', 0, 'mariamastz@gmail.com', 1),
(24, 'Juan Lopez Valenzuela', '2024-12-01 10:15:27', 'alta', 0, 'JuanLV@gmail.com', 1),
(25, 'Juan Lopez Valenzuela', '2024-12-03 17:27:42', 'alta', 0, 'Juan01@gmail.com', 1);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_inventario`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_inventario` (
`material_id` int(11)
,`serie` varchar(50)
,`modelo` varchar(50)
,`edificio` varchar(100)
,`estatus` varchar(50)
,`tipo_material` varchar(100)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `historial_mantenimientos`
--
DROP TABLE IF EXISTS `historial_mantenimientos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `historial_mantenimientos`  AS SELECT `m`.`mantenimiento_id` AS `operacion_id`, `tm`.`nombre` AS `tipo_material_nombre`, `tm`.`categoria` AS `tipo_material_categoria`, `m`.`descripcion` AS `descripcion`, `m`.`notas` AS `notas`, `i`.`serie` AS `serie`, `i`.`modelo` AS `modelo`, `m`.`fecha_inicio` AS `fecha_inicio`, `m`.`fecha_final` AS `fecha_final`, concat(`p`.`nombre`,' ',`p`.`primer_apellido`,' ',`p`.`segundo_apellido`) AS `responsable`, `p`.`edificio_id` AS `edificio_id` FROM ((((`mantenimiento` `m` left join `mantenimiento_inventario` `mi` on(`m`.`mantenimiento_id` = `mi`.`mantenimiento_id`)) left join `inventario` `i` on(`mi`.`material_id` = `i`.`material_id`)) left join `tipo_material` `tm` on(`i`.`tipo_material_id` = `tm`.`tipo_material_id`)) left join `personales` `p` on(`m`.`personal_id` = `p`.`personal_id`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `historial_materiales`
--
DROP TABLE IF EXISTS `historial_materiales`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `historial_materiales`  AS SELECT 'Transacción' AS `tipo_operacion`, `t`.`transaccion_id` AS `operacion_id`, `i`.`material_id` AS `material_id`, `tm`.`nombre` AS `material_nombre`, `i`.`serie` AS `serie`, `i`.`modelo` AS `modelo` FROM (((`transacciones` `t` join `inventario_transaccion` `it` on(`t`.`transaccion_id` = `it`.`transaccion_id`)) join `inventario` `i` on(`it`.`material_id` = `i`.`material_id`)) join `tipo_material` `tm` on(`i`.`tipo_material_id` = `tm`.`tipo_material_id`))union all select 'Mantenimiento' AS `tipo_operacion`,`m`.`mantenimiento_id` AS `operacion_id`,`i`.`material_id` AS `material_id`,`tm`.`nombre` AS `material_nombre`,`i`.`serie` AS `serie`,`i`.`modelo` AS `modelo` from (((`mantenimiento` `m` join `mantenimiento_inventario` `mi` on(`m`.`mantenimiento_id` = `mi`.`mantenimiento_id`)) join `inventario` `i` on(`mi`.`material_id` = `i`.`material_id`)) join `tipo_material` `tm` on(`i`.`tipo_material_id` = `tm`.`tipo_material_id`)) union all select 'Préstamo' AS `tipo_operacion`,`pr`.`prestamo_id` AS `operacion_id`,`i`.`material_id` AS `material_id`,`tm`.`nombre` AS `material_nombre`,`i`.`serie` AS `serie`,`i`.`modelo` AS `modelo` from (((`prestamos` `pr` join `inventario_prestamos` `ip` on(`pr`.`prestamo_id` = `ip`.`prestamo_id`)) join `inventario` `i` on(`ip`.`material_id` = `i`.`material_id`)) join `tipo_material` `tm` on(`i`.`tipo_material_id` = `tm`.`tipo_material_id`))  ;

-- --------------------------------------------------------

--
-- Estructura para la vista `historial_operaciones`
--
DROP TABLE IF EXISTS `historial_operaciones`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `historial_operaciones`  AS SELECT DISTINCT 'Transacción' AS `tipo_operacion`, `t`.`transaccion_id` AS `operacion_id`, `t`.`fecha_inicio` AS `fecha_inicio`, `t`.`fecha_final` AS `fecha_final`, `t`.`notas` AS `notas`, concat(`p`.`nombre`,' ',`p`.`primer_apellido`,' ',`p`.`segundo_apellido`) AS `responsable`, `p`.`edificio_id` AS `edificio_id` FROM ((`transacciones` `t` join (select distinct `inventario_transaccion`.`transaccion_id` AS `transaccion_id`,`inventario_transaccion`.`personal_id` AS `personal_id` from `inventario_transaccion`) `it` on(`t`.`transaccion_id` = `it`.`transaccion_id`)) join `personales` `p` on(`it`.`personal_id` = `p`.`personal_id`))union all select 'Mantenimiento' AS `tipo_operacion`,`m`.`mantenimiento_id` AS `operacion_id`,`m`.`fecha_inicio` AS `fecha_inicio`,`m`.`fecha_final` AS `fecha_final`,`m`.`notas` AS `notas`,concat(`p`.`nombre`,' ',`p`.`primer_apellido`,' ',`p`.`segundo_apellido`) AS `responsable`,`p`.`edificio_id` AS `edificio_id` from (`mantenimiento` `m` join `personales` `p` on(`m`.`personal_id` = `p`.`personal_id`)) union all select 'Préstamo' AS `tipo_operacion`,`pr`.`prestamo_id` AS `operacion_id`,`pr`.`fecha_salida` AS `fecha_inicio`,`pr`.`fecha_devolucion` AS `fecha_final`,`pr`.`notas` AS `notas`,concat(`p`.`nombre`,' ',`p`.`primer_apellido`,' ',`p`.`segundo_apellido`) AS `responsable`,`p`.`edificio_id` AS `edificio_id` from (`prestamos` `pr` join `personales` `p` on(`pr`.`personal_id` = `p`.`personal_id`))  ;

-- --------------------------------------------------------

--
-- Estructura para la vista `historial_prestamos`
--
DROP TABLE IF EXISTS `historial_prestamos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `historial_prestamos`  AS SELECT `pr`.`prestamo_id` AS `operacion_id`, `pr`.`notas` AS `notas`, `u`.`usuario_id` AS `usuario_id`, `u`.`nombre` AS `solicitado_por`, `pr`.`estatus` AS `estatus`, CASE WHEN `pr`.`estatus` = 'pendiente' THEN 'Pendiente' WHEN `pr`.`estatus` = 'rechazado' THEN 'Rechazado' ELSE concat(`p`.`nombre`,' ',`p`.`primer_apellido`,' ',`p`.`segundo_apellido`) END AS `responsable`, `pr`.`fecha_salida` AS `fecha_salida`, `pr`.`fecha_devolucion` AS `fecha_devolucion`, `p`.`edificio_id` AS `edificio_id` FROM ((`prestamos` `pr` left join `personales` `p` on(`pr`.`personal_id` = `p`.`personal_id`)) left join `usuarios` `u` on(`pr`.`usuario_id` = `u`.`usuario_id`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `historial_prestamos_usuario`
--
DROP TABLE IF EXISTS `historial_prestamos_usuario`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `historial_prestamos_usuario`  AS SELECT `pr`.`prestamo_id` AS `operacion_id`, `pr`.`notas` AS `notas`, `u`.`usuario_id` AS `usuario_id`, `u`.`nombre` AS `solicitado_por`, `pr`.`estatus` AS `estatus`, CASE WHEN `pr`.`estatus` = 'pendiente' THEN 'Pendiente' WHEN `pr`.`estatus` = 'rechazado' THEN 'Rechazado' ELSE concat(`p`.`nombre`,' ',`p`.`primer_apellido`,' ',`p`.`segundo_apellido`) END AS `responsable`, `pr`.`fecha_salida` AS `fecha_salida`, `pr`.`fecha_devolucion` AS `fecha_devolucion`, `p`.`edificio_id` AS `edificio_id` FROM ((`prestamos` `pr` left join `personales` `p` on(`pr`.`personal_id` = `p`.`personal_id`)) left join `usuarios` `u` on(`pr`.`usuario_id` = `u`.`usuario_id`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `historial_transacciones`
--
DROP TABLE IF EXISTS `historial_transacciones`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `historial_transacciones`  AS SELECT DISTINCT `t`.`transaccion_id` AS `operacion_id`, `t`.`tipo_transaccion` AS `tipo_transaccion`, `t`.`fecha_inicio` AS `fecha_inicio`, `t`.`fecha_final` AS `fecha_final`, `t`.`notas` AS `notas`, `p`.`nombre` AS `proveedor_nombre`, `p`.`telefono` AS `proveedor_telefono`, `p`.`correo` AS `proveedor_correo`, concat(`p2`.`nombre`,' ',`p2`.`primer_apellido`,' ',`p2`.`segundo_apellido`) AS `personal_nombre`, `p2`.`edificio_id` AS `edificio_id` FROM (((`transacciones` `t` join `inventario_transaccion` `it` on(`t`.`transaccion_id` = `it`.`transaccion_id`)) join `proveedores` `p` on(`it`.`proveedor_id` = `p`.`proveedor_id`)) join `personales` `p2` on(`it`.`personal_id` = `p2`.`personal_id`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `prestamos_usuarios`
--
DROP TABLE IF EXISTS `prestamos_usuarios`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `prestamos_usuarios`  AS SELECT `pr`.`prestamo_id` AS `operacion_id`, `pr`.`notas` AS `notas`, `pr`.`estatus` AS `estatus`, group_concat(`i`.`modelo` separator ', ') AS `materiales`, `pr`.`usuario_id` AS `usuario_id`, CASE WHEN `pr`.`estatus` = 'pendiente' THEN 'Pendiente' WHEN `pr`.`estatus` = 'rechazado' THEN 'Rechazado' ELSE concat(`p`.`nombre`,' ',`p`.`primer_apellido`,' ',`p`.`segundo_apellido`) END AS `Responsable`, `pr`.`fecha_salida` AS `fecha_salida`, `pr`.`fecha_devolucion` AS `fecha_devolucion` FROM ((((`prestamos` `pr` left join `personales` `p` on(`pr`.`personal_id` = `p`.`personal_id`)) left join `usuarios` `u` on(`pr`.`usuario_id` = `u`.`usuario_id`)) left join `inventario_prestamos` `ip` on(`pr`.`prestamo_id` = `ip`.`prestamo_id`)) left join `inventario` `i` on(`ip`.`material_id` = `i`.`material_id`)) WHERE `pr`.`estatus` in ('pendiente','aprobado','rechazado') GROUP BY `pr`.`prestamo_id`, `pr`.`notas`, `pr`.`estatus`, `pr`.`usuario_id`, `pr`.`fecha_salida`, `pr`.`fecha_devolucion`, CASE WHEN `pr`.`estatus` = 'pendiente' THEN 'Pendiente' WHEN `pr`.`estatus` = 'rechazado' THEN 'Rechazado' ELSE concat(`p`.`nombre`,' ',`p`.`primer_apellido`,' ',`p`.`segundo_apellido`) END ;

-- --------------------------------------------------------

--
-- Estructura para la vista `prestamos_usuarios_edificio`
--
DROP TABLE IF EXISTS `prestamos_usuarios_edificio`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `prestamos_usuarios_edificio`  AS SELECT `e`.`edificio_id` AS `edificio_id`, `pr`.`prestamo_id` AS `operacion_id`, `pr`.`notas` AS `notas`, `pr`.`estatus` AS `estatus`, `i`.`modelo` AS `modelo`, `pr`.`usuario_id` AS `usuario_id`, CASE WHEN `pr`.`estatus` = 'pendiente' THEN 'Pendiente' WHEN `pr`.`estatus` = 'rechazado' THEN 'Rechazado' ELSE concat(`p`.`nombre`,' ',`p`.`primer_apellido`,' ',`p`.`segundo_apellido`) END AS `Responsable`, `pr`.`fecha_salida` AS `fecha_salida`, `pr`.`fecha_devolucion` AS `fecha_devolucion` FROM (((((`prestamos` `pr` left join `personales` `p` on(`pr`.`personal_id` = `p`.`personal_id`)) left join `usuarios` `u` on(`pr`.`usuario_id` = `u`.`usuario_id`)) left join `inventario_prestamos` `ip` on(`pr`.`prestamo_id` = `ip`.`prestamo_id`)) left join `inventario` `i` on(`ip`.`material_id` = `i`.`material_id`)) left join `edificios` `e` on(`e`.`edificio_id` = `i`.`edificio_id`)) WHERE `pr`.`estatus` in ('pendiente','aprobado','rechazado') ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_inventario`
--
DROP TABLE IF EXISTS `vista_inventario`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_inventario`  AS SELECT `i`.`material_id` AS `material_id`, `i`.`serie` AS `serie`, `i`.`modelo` AS `modelo`, `e`.`nombre` AS `edificio`, `s`.`estatus` AS `estatus`, `t`.`nombre` AS `tipo_material` FROM (((`inventario` `i` join `estatus` `s` on(`i`.`estatus_id` = `s`.`estatus_id`)) join `tipo_material` `t` on(`i`.`tipo_material_id` = `t`.`tipo_material_id`)) join `edificios` `e` on(`i`.`edificio_id` = `e`.`edificio_id`)) ;

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
  ADD KEY `material_id` (`material_id`),
  ADD KEY `personal_id` (`personal_id`),
  ADD KEY `proveedor_id` (`proveedor_id`);

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
  ADD PRIMARY KEY (`usuario_id`),
  ADD KEY `fk_edificios` (`edificio_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  MODIFY `cuenta_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `edificios`
--
ALTER TABLE `edificios`
  MODIFY `edificio_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `estatus`
--
ALTER TABLE `estatus`
  MODIFY `estatus_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `material_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `mantenimiento`
--
ALTER TABLE `mantenimiento`
  MODIFY `mantenimiento_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `personales`
--
ALTER TABLE `personales`
  MODIFY `personal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `prestamo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `proveedor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tipo_material`
--
ALTER TABLE `tipo_material`
  MODIFY `tipo_material_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `transacciones`
--
ALTER TABLE `transacciones`
  MODIFY `transaccion_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usuario_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

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
  ADD CONSTRAINT `inventario_transaccion_ibfk_2` FOREIGN KEY (`material_id`) REFERENCES `inventario` (`material_id`),
  ADD CONSTRAINT `inventario_transaccion_ibfk_3` FOREIGN KEY (`personal_id`) REFERENCES `personales` (`personal_id`),
  ADD CONSTRAINT `inventario_transaccion_ibfk_4` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`proveedor_id`);

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
-- Filtros para la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD CONSTRAINT `fk_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`),
  ADD CONSTRAINT `prestamos_ibfk_1` FOREIGN KEY (`personal_id`) REFERENCES `personales` (`personal_id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_edificios` FOREIGN KEY (`edificio_id`) REFERENCES `edificios` (`edificio_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

SET FOREIGN_KEY_CHECKS = 0; -- Desactivar claves foráneas temporalmente
TRUNCATE TABLE `cuentas`;
TRUNCATE TABLE `edificios`;
TRUNCATE TABLE `estatus`;
TRUNCATE TABLE `historial_mantenimientos`;
TRUNCATE TABLE `historial_materiales`;
TRUNCATE TABLE `historial_operaciones`;
TRUNCATE TABLE `historial_prestamos`;
TRUNCATE TABLE `historial_prestamos_usuario`;
TRUNCATE TABLE `historial_transacciones`;
TRUNCATE TABLE `inventario`;
TRUNCATE TABLE `inventario_prestamos`;
TRUNCATE TABLE `inventario_transaccion`;
TRUNCATE TABLE `mantenimiento`;
TRUNCATE TABLE `mantenimiento_inventario`;
TRUNCATE TABLE `personales`;
TRUNCATE TABLE `prestamos`;
TRUNCATE TABLE `prestamos_usuarios`;
TRUNCATE TABLE `prestamos_usuarios_edificio`;
TRUNCATE TABLE `proveedores`;
TRUNCATE TABLE `tipo_material`;
TRUNCATE TABLE `transacciones`;
TRUNCATE TABLE `usuarios`;
TRUNCATE TABLE `vista_inventario`;
SET FOREIGN_KEY_CHECKS = 1; -- Reactivar claves foráneas

INSERT INTO `cuentas` (`cuenta_id`, `nombre_usuario`, `contraseña`, `tipo_cuenta`, `usuario_id`, `personal_id`)
VALUES (NULL, 'admin', 'admin123', 'administrador', NULL, NULL);

INSERT INTO `personales` (`personal_id`, `nombre`, `apellido_paterno`, `apellido_materno`)
VALUES (NULL, 'Angel', 'Gameros', 'Garcia');
