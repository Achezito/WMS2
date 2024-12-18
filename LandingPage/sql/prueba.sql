-- Insertar datos de ejemplo en la tabla `tipo_material`
INSERT INTO `tipo_material` (`tipo_material_id`, `nombre`, `categoria`, `descripcion`) VALUES
(1, 'Laptop', 'Equipo de Cómputo', 'Computadora portátil para uso general'),
(2, 'Router', 'Redes', 'Dispositivo para conectar redes de datos'),
(3, 'Proyector', 'Audiovisual', 'Equipo para proyección de imágenes o videos'),
(4, 'Impresora', 'Equipo de Oficina', 'Dispositivo para impresión de documentos');

-- Insertar datos de ejemplo en la tabla `estatus`
INSERT INTO `estatus` (`estatus_id`, `estatus`) VALUES
(1, 'Disponible'),
(2, 'En uso'),
(3, 'En mantenimiento'),
(4, 'Fuera de servicio');

-- Insertar datos de ejemplo en la tabla `edificios`
INSERT INTO `edificios` (`edificio_id`, `nombre`) VALUES
(3, 'Laboratorio de Informática'),
(4, 'Centro de Cómputo');

-- Insertar datos de ejemplo en la tabla `inventario`
INSERT INTO `inventario` (`material_id`, `serie`, `modelo`, `edificio_id`, `estatus_id`, `tipo_material_id`) VALUES
(1, 'LPT12345', 'Dell Inspiron 15', 3, 1, 1),
(2, 'RTR67890', 'Cisco RV340', 4, 2, 2),
(3, 'PRJ54321', 'Epson EX3260', 4, 1, 3),
(4, 'IMP13579', 'HP LaserJet Pro', 3, 3, 4),
(5, 'LPT98765', 'Lenovo ThinkPad X1', 3, 1, 1),
(6, 'RTR24680', 'TP-Link Archer C80', 4, 4, 2);


SELECT 
    i.material_id,
    i.serie,
    i.modelo,
    e.nombre AS edificio,
    s.estatus AS estatus,
    t.nombre AS tipo_material,
    p.nombre AS personal_responsable -- Suponiendo que 'nombre' en 'personales' es el responsable
FROM 
    inventario AS i
JOIN 
    estatus AS s ON i.estatus_id = s.estatus_id
JOIN 
    tipo_material AS t ON i.tipo_material_id = t.tipo_material_id
JOIN 
    edificios AS e ON i.edificio_id = e.edificio_id
JOIN 
    personales AS p ON e.edificio_id = p.edificio_id
WHERE e.edificio_id = p.edificio_id AND s.estatus = 'disponible';


CREATE VIEW vista_prestamos_materiales AS
SELECT 
    p.prestamo_id,
    p.fecha_salida,
    p.fecha_devolucion,
    m.serie,
    m.modelo,
    m.tipo_material_id,
    mp.cantidad,
    pe.nombre AS personal_prestamo
FROM 
    prestamos p
JOIN 
    material_prestamos mp ON p.prestamo_id = mp.prestamo_id
JOIN 
    materiales m ON mp.material_id = m.material_id
JOIN 
    personales pe ON p.personal_id = pe.personal_id;
WHERE ed


SELECT m.descripcion, m.fecha_inicio, m.fecha_final, ma.serie, ma.modelo
FROM mantenimiento m
JOIN mantenimiento_material mm ON m.mantenimiento_id = mm.mantenimiento_id
JOIN materiales ma ON mm.material_id = ma.material_id;




SELECT 
    pe.nombre, 
    pe.primer_apellido, 
    pe.segundo_apellido, 
    mp.cantidad, 
    m.serie, 
    m.modelo,
    p.usuario_id
FROM 
    personales pe
JOIN 
    prestamos p ON pe.personal_id = p.personal_id
JOIN 
    material_prestamos mp ON p.prestamo_id = mp.prestamo_id
JOIN 
    materiales m ON mp.material_id = m.material_id
WHERE 
    p.fecha_devolucion IS NULL;  -- Filtra solo los materiales que no han sido devueltos

SELECT * FROM prestamos


ALTER TABLE prestamos
ADD COLUMN estatus ENUM('pendiente', 'aprobado', 'rechazado') NOT NULL DEFAULT 'pendiente';


---- VISTA ACTIVIDAD PERSONAL ----
select * from historial_operaciones

CREATE OR REPLACE VIEW historial_operaciones AS
SELECT DISTINCT
    'Transacción' AS tipo_operacion,
    t.transaccion_id AS operacion_id,  -- Agregar el ID de la operación
    t.fecha_inicio, 
    t.fecha_final, 
    t.notas, 
    CONCAT(p.nombre, ' ', p.primer_apellido, ' ', p.segundo_apellido) AS responsable,
    p.edificio_id
FROM 
    transacciones t
JOIN 
    (SELECT DISTINCT transaccion_id, personal_id 
     FROM inventario_transaccion) it ON t.transaccion_id = it.transaccion_id
JOIN 
    personales p ON it.personal_id = p.personal_id
UNION ALL 
SELECT 
    'Mantenimiento' AS tipo_operacion,
    m.mantenimiento_id AS operacion_id,  -- Agregar el ID de la operación
    m.fecha_inicio, 
    m.fecha_final, 
    m.notas, 
    CONCAT(p.nombre, ' ', p.primer_apellido, ' ', p.segundo_apellido) AS responsable,
    p.edificio_id
FROM 
    mantenimiento m
JOIN 
    personales p ON m.personal_id = p.personal_id
UNION ALL
SELECT 
    'Préstamo' AS tipo_operacion,
    pr.prestamo_id AS operacion_id,  -- Agregar el ID de la operación
    pr.fecha_salida AS fecha_inicio, 
    pr.fecha_devolucion AS fecha_final, 
    pr.notas, 
    CONCAT(p.nombre, ' ', p.primer_apellido, ' ', p.segundo_apellido) AS responsable,
    p.edificio_id
FROM 
    prestamos pr
JOIN 
    personales p ON pr.personal_id = p.personal_id;

select * from historial_materiales

CREATE OR REPLACE VIEW historial_materiales AS
SELECT 
    'Transacción' AS tipo_operacion,
    t.transaccion_id AS operacion_id,
    i.material_id,
    tm.nombre AS material_nombre,
    i.serie,
    i.modelo
FROM 
    transacciones t
JOIN 
    inventario_transaccion it ON t.transaccion_id = it.transaccion_id
JOIN 
    inventario i ON it.material_id = i.material_id
JOIN 
    tipo_material tm ON i.tipo_material_id = tm.tipo_material_id
UNION ALL
SELECT 
    'Mantenimiento' AS tipo_operacion,
    m.mantenimiento_id AS operacion_id,
    i.material_id,
    tm.nombre AS material_nombre,
    i.serie,
    i.modelo
FROM 
    mantenimiento m
JOIN 
    mantenimiento_inventario mi ON m.mantenimiento_id = mi.mantenimiento_id
JOIN 
    inventario i ON mi.material_id = i.material_id
JOIN 
    tipo_material tm ON i.tipo_material_id = tm.tipo_material_id
UNION ALL
SELECT 
    'Préstamo' AS tipo_operacion,
    pr.prestamo_id AS operacion_id,
    i.material_id,
    tm.nombre AS material_nombre,
    i.serie,
    i.modelo
FROM 
    prestamos pr
JOIN 
    inventario_prestamos ip ON pr.prestamo_id = ip.prestamo_id
JOIN 
    inventario i ON ip.material_id = i.material_id
JOIN 
    tipo_material tm ON i.tipo_material_id = tm.tipo_material_id;



SELECT 
    pr.prestamo_id AS operacion_id,  -- Identificador del préstamo
    pr.notas,  -- Notas del préstamo
    u.nombre AS Solicitado_Por,  -- Solicitado por
    pr.estatus,  -- Estatus del préstamo
    CASE 
        WHEN pr.estatus = 'pendiente' THEN 'Pendiente'
        WHEN pr.estatus = 'rechazado' THEN 'Rechazado'
        ELSE CONCAT(p.nombre, ' ', p.primer_apellido, ' ', p.segundo_apellido)
    END AS Responsable,  -- Responsable del préstamo
    pr.fecha_salida,  -- Fecha de salida
    pr.fecha_devolucion,  -- Fecha de devolución
    p.edificio_id  -- ID del edificio
FROM 
    prestamos pr
LEFT JOIN 
    personales p ON pr.personal_id = p.personal_id
LEFT JOIN 
    usuarios u ON pr.usuario_id = u.usuario_id

------------------------ VISTA HISTORIAL PRESTAMOS ----------------------
SELECT * FROM historial_prestamos;

DROP VIEW historial_prestamos;

CREATE OR REPLACE VIEW historial_prestamos AS
SELECT 
    pr.prestamo_id AS operacion_id,  -- Identificador del préstamo
    pr.notas,  -- Notas del préstamo
    u.nombre AS Solicitado_Por,  -- Solicitado por
    pr.estatus,  -- Estatus del préstamo
    CASE 
        WHEN pr.estatus = 'pendiente' THEN 'Pendiente'
        WHEN pr.estatus = 'rechazado' THEN 'Rechazado'
        ELSE CONCAT(p.nombre, ' ', p.primer_apellido, ' ', p.segundo_apellido)
    END AS Responsable,  -- Responsable del préstamo
    pr.fecha_salida,  -- Fecha de salida
    pr.fecha_devolucion,  -- Fecha de devolución
    p.edificio_id  -- ID del edificio
FROM 
    prestamos pr
LEFT JOIN 
    personales p ON pr.personal_id = p.personal_id
LEFT JOIN 
    usuarios u ON pr.usuario_id = u.usuario_id


select * from historial_prestamos


----------- VISTA HISTORIAL-PRESTAMOS POR USUARIOS -----------
CREATE OR REPLACE VIEW prestamos_usuarios AS
SELECT 
    pr.prestamo_id AS operacion_id,  -- Identificador del préstamo
    pr.notas,  -- Notas del préstamo
    pr.estatus,  -- Estatus del préstamo
    GROUP_CONCAT(i.modelo SEPARATOR ', ') AS materiales,  -- Concatenación de los materiales asignados
    pr.usuario_id,  -- ID del usuario solicitante
    CASE 
        WHEN pr.estatus = 'pendiente' THEN 'Pendiente'
        WHEN pr.estatus = 'rechazado' THEN 'Rechazado'
        ELSE CONCAT(p.nombre, ' ', p.primer_apellido, ' ', p.segundo_apellido)
    END AS Responsable,  -- Responsable del préstamo
    pr.fecha_salida,  -- Fecha de salida
    pr.fecha_devolucion -- Fecha de devolución
FROM 
    prestamos pr
LEFT JOIN 
    personales p ON pr.personal_id = p.personal_id
LEFT JOIN 
    usuarios u ON pr.usuario_id = u.usuario_id
LEFT JOIN 
    inventario_prestamos as ip ON pr.prestamo_id = ip.prestamo_id
LEFT JOIN 
    inventario as i ON ip.material_id = i.material_id
WHERE 
    pr.estatus IN ('pendiente', 'aprobado', 'rechazado') 
GROUP BY 
    pr.prestamo_id, pr.notas, pr.estatus, pr.usuario_id, pr.fecha_salida, pr.fecha_devolucion, Responsable;


SELECT * 
FROM prestamos_usuarios 
WHERE operacion_id = 28; -- Reemplaza con el ID que estés probando

SELECT * FROM prestamos WHERE prestamo_id = 30


SELECT p.prestamo_id, i.modelo AS modelo_material, tp.nombre AS tipo_material, p.notas, i.material_id 
              FROM prestamos AS p
              INNER JOIN inventario_prestamos AS ip ON p.prestamo_id = ip.prestamo_id
              INNER JOIN inventario AS i ON ip.material_id = i.material_id
              INNER JOIN tipo_material AS tp ON i.tipo_material_id = tp.tipo_material_id
              WHERE p.prestamo_id = 30;

----------- VISTA HISTORIAL MANTENIMIENTOS -----------
SELECT p.prestamo_id, i.modelo,tp.nombre ,p.notas 
FROM prestamos as p
INNER JOIN inventario_prestamos as ip on p.prestamo_id = ip.prestamo_id
INNER JOIN inventario as i on ip.material_id = i.material_id
INNER JOIN tipo_material as tp on i.material_id = tp.tipo_material_id
WHERE p.prestamo_id = 16;





CREATE OR REPLACE VIEW historial_mantenimientos AS
SELECT
    m.mantenimiento_id AS operacion_id,  -- Identificador del mantenimiento
    tm.nombre AS tipo_material_nombre,
    tm.categoria AS tipo_material_categoria,
    m.descripcion,
    m.notas,
    i.serie,
    i.modelo,
    m.fecha_inicio,
    m.fecha_final,
    CONCAT(p.nombre, ' ', p.primer_apellido, ' ', p.segundo_apellido) AS responsable,
    p.edificio_id  -- ID del edificio
FROM
    mantenimiento m
LEFT JOIN
    mantenimiento_inventario mi ON m.mantenimiento_id = mi.mantenimiento_id
LEFT JOIN
    inventario i ON mi.material_id = i.material_id
LEFT JOIN
    tipo_material tm ON i.tipo_material_id = tm.tipo_material_id
LEFT JOIN
    personales p ON m.personal_id = p.personal_id;





SELECT * FROM historial_transacciones;
DROP VIEW historial_transacciones;

CREATE OR REPLACE VIEW historial_transacciones AS
SELECT DISTINCT
    t.transaccion_id AS operacion_id,  -- Identificador de la transacción
    t.tipo_transaccion,
    t.fecha_inicio,
    t.fecha_final,
    t.notas,
    p.nombre AS proveedor_nombre,
    p.telefono AS proveedor_telefono,
    p.correo AS proveedor_correo,
    CONCAT(p2.nombre, ' ', p2.primer_apellido, ' ', p2.segundo_apellido) AS personal_nombre,
    p2.edificio_id  -- ID del edificio
FROM 
    transacciones t
JOIN 
    inventario_transaccion it ON t.transaccion_id = it.transaccion_id
JOIN 
    proveedores p ON it.proveedor_id = p.proveedor_id
JOIN 
    personales p2 ON it.personal_id = p2.personal_id;




------------------------------ INSERTS --------------------------------------

INSERT INTO edificios (edificio_id, nombre) VALUES 
(1, 'docencia 1'), 
(2, 'docencia 2'), 
(3, 'Laboratorio de Informática'),
(4, 'Centro de Cómputo');

INSERT INTO estatus (estatus_id, estatus) VALUES 
(1, 'Disponible'), 
(2, 'En uso'), 
(3, 'En mantenimiento'), 
(4, 'Fuera de servicio');

INSERT INTO tipo_material (tipo_material_id, nombre, categoria, descripcion) VALUES 
(1, 'Laptop', 'Electrónica', 'Equipo portátil de computación'),
(2, 'Proyector', 'Audiovisual', 'Equipo para presentaciones visuales'),
(3, 'Mesa', 'Mobiliario', 'Mobiliario para oficinas o salones'),
(4, 'Silla', 'Mobiliario', 'Silla ergonómica para oficina'),
(5, 'Impresora', 'Electrónica', 'Impresora multifuncional'),
(6, 'Pantalla', 'Audiovisual', 'Pantalla LED para proyecciones');

INSERT INTO inventario (material_id, serie, modelo, edificio_id, estatus_id, tipo_material_id) VALUES 
(1, 'ABC123', 'Dell Inspiron', 1, 1, 1), 
(2, 'DEF456', 'Epson EB-X51', 2, 2, 2), 
(3, 'GHI789', 'IKEA LACK', 3, 3, 3),
(4, 'JKL012', 'ErgoChair 2', 1, 1, 4),
(5, 'MNO345', 'HP LaserJet', 2, 2, 5),
(6, 'PQR678', 'Samsung 55-inch', 3, 1, 6),
(7, 'STU901', 'Asus ZenBook', 1, 1, 1),
(8, 'VWX234', 'Canon PIXMA', 2, 4, 5),
(9, 'YZA567', 'LG 65-inch', 3, 2, 6),
(10, 'BCD890', 'Logitech Spotlight', 1, 1, 2);

INSERT INTO personales (personal_id, nombre, primer_apellido, segundo_apellido, edificio_id) VALUES
(1, 'Juan', 'Pérez', 'Gómez', 1), 
(2, 'Ana', 'López', 'Martínez', 2), 
(3, 'Carlos', 'Sánchez', 'Hernández', 3);

INSERT INTO usuarios (usuario_id, nombre, descripcion, fecha_creacion, estado) VALUES 
(1, 'Pedro Gómez', 'Usuario activo del sistema', '2024-01-01 10:00:00', 'alta'), 
(2, 'María Torres', 'Usuario regular del sistema', '2024-01-02 11:00:00', 'alta'),
(3, 'Juan Pérez', 'Administrador del sistema', '2024-11-13 22:29:43', 'alta'),
(4, 'Roberto Castillo', 'Usuario temporal', '2024-07-01 14:00:00', 'alta'),
(5, 'Sofía Ramírez', 'Nuevo usuario registrado', '2024-09-15 10:30:00', 'alta'),
(6, 'Alejandro Ruiz', 'Usuario frecuente', '2024-10-01 12:00:00', 'alta');

INSERT INTO cuentas (cuenta_id, nombre_usuario, contraseña, tipo_cuenta, usuario_id, personal_id) VALUES
(11, 'holamundo', SHA1('holamundo'), 'usuario', 8, NULL)


INSERT INTO transacciones (transaccion_id, tipo_transaccion, fecha_inicio, fecha_final, notas) VALUES 
(1, 'Compra', '2024-11-01 10:00:00', '2024-11-01 12:00:00', 'Compra inicial de materiales'),
(2, 'Devolución', '2024-11-02 09:00:00', '2024-11-02 11:00:00', 'Devolución de equipos dañados'),
(3, 'Transferencia', '2024-11-03 08:00:00', '2024-11-03 10:00:00', 'Transferencia de materiales entre edificios'),
(4, 'Compra', '2024-11-04 14:00:00', '2024-11-04 16:00:00', 'Adquisición de nuevos equipos audiovisuales');

INSERT INTO prestamos (prestamo_id, fecha_salida, fecha_devolucion, estatus, notas, personal_id, usuario_id) VALUES 
(1, '2024-11-07', '2024-11-10', 'pendiente', 'Solicitud de laptops', NULL, 1), 
(2, '2024-11-08', '2024-11-11', 'aprobado', 'Solicitud de proyector', 2, 2),
(3, '2024-11-09', '2024-11-12', 'rechazado', 'Equipo no disponible', NULL, 3),
(4, '2024-11-10', '2024-11-15', 'pendiente', 'Material para presentación', NULL, 4);

INSERT INTO mantenimiento (mantenimiento_id, descripcion, fecha_inicio, fecha_final, notas, personal_id) VALUES 
(1, 'Revisión general de laptops', '2024-11-03', '2024-11-04', 'Limpieza y actualización', 1), 
(2, 'Reparación de proyector', '2024-11-05', '2024-11-06', 'Cambio de lámpara', 2),
(3, 'Reparación de impresora', '2024-11-07', '2024-11-08', 'Reemplazo de cabezal de impresión', 3);

INSERT INTO proveedores (proveedor_id, nombre, telefono, correo) VALUES 
(1, 'Proveedor Tech', '555-1234', 'juan.ortega@tech.com'),
(2, 'Equipos y Más', '555-5678', 'maria.lopez@equiposymas.com'),
(3, 'Mobiliario Moderno', '555-9101', 'carlos.herrera@mobiliariomoderno.com');

INSERT INTO estatus (estatus_id, estatus) VALUES 
(1, 'Disponible'),
(2, 'En uso'),
(3, 'En mantenimiento'),
(4, 'Fuera de servicio');

INSERT INTO inventario_prestamos (prestamo_id, material_id, cantidad) VALUES 
(1, 1, 3), 
(2, 2, 1),
(3, 5, 2),
(4, 6, 1);

INSERT INTO ppt (ppt_id, transaccion_id, personal_id, proveedor_id) VALUES 
(1, 1, 1, 1), 
(2, 2, 2, 2),
(3, 3, 3, 1),
(4, 4, 1, 2);

INSERT INTO inventario_transaccion (transaccion_id, material_id, cantidad) VALUES 
(1, 1, 10), 
(1, 2, 5), 
(2, 3, 2),
(3, 6, 7),
(4, 10, 4);


ALTER TABLE cuentas MODIFY tipo_cuenta ENUM('usuario', 'personal', 'administrador');
INSERT INTO cuentas (nombre_usuario, contraseña, tipo_cuenta) 
VALUES ('admin', SHA1('admin'), 'administrador');


INSERT INTO cuentas (tipo_cuenta, nombre_usuario, contraseña, personal_id, usuario_id) 
VALUES ('administrador', 'admin', SHA1('admin'), NULL, NULL);

CREATE OR REPLACE VIEW prestamos_usuarios_edificio AS
SELECT 
    e.edificio_id,
    pr.prestamo_id AS operacion_id,  -- Identificador del préstamo
    pr.notas,  -- Notas del préstamo
    pr.estatus,  -- Estatus del préstamo
    i.modelo,
    pr.usuario_id,  -- ID del usuario solicitante (necesario para filtrar después)
    CASE 
        WHEN pr.estatus = 'pendiente' THEN 'Pendiente'
        WHEN pr.estatus = 'rechazado' THEN 'Rechazado'
        ELSE CONCAT(p.nombre, ' ', p.primer_apellido, ' ', p.segundo_apellido)
    END AS Responsable,  -- Responsable del préstamo
    pr.fecha_salida,  -- Fecha de salida
    pr.fecha_devolucion -- Fecha de devolución
FROM 
    prestamos pr
LEFT JOIN 
    personales p ON pr.personal_id = p.personal_id
LEFT JOIN 
    usuarios u ON pr.usuario_id = u.usuario_id
LEFT JOIN 
    inventario_prestamos as ip on pr.prestamo_id = ip.prestamo_id
LEFT JOIN 
    inventario as i on ip.material_id = i.material_id
LEFT JOIN
    edificios as e on e.edificio_id = i.edificio_id
WHERE 
    pr.estatus IN ('pendiente', 'aprobado', 'rechazado'); 
SELECT edificio_id, nombre FROM edificios

SELECT * FROM prestamos_usuarios_edificio WHERE edificio_id = 1;



DROP VIEW IF EXISTS historial_prestamos_usuario;

CREATE OR REPLACE VIEW historial_prestamos_usuario AS
SELECT 
    pr.prestamo_id AS operacion_id,  -- Identificador del préstamo
    pr.notas,                        -- Notas del préstamo
    u.usuario_id,                    -- ID del usuario
    u.nombre AS solicitado_por,      -- Nombre del solicitante
    pr.estatus,                      -- Estatus del préstamo
    CASE 
        WHEN pr.estatus = 'pendiente' THEN 'Pendiente'
        WHEN pr.estatus = 'rechazado' THEN 'Rechazado'
        ELSE CONCAT(p.nombre, ' ', p.primer_apellido, ' ', p.segundo_apellido)
    END AS responsable,              -- Responsable del préstamo
    pr.fecha_salida,                 -- Fecha de salida
    pr.fecha_devolucion,             -- Fecha de devolución
    p.edificio_id                    -- ID del edificio asociado
FROM 
    prestamos pr
LEFT JOIN 
    personales p ON pr.personal_id = p.personal_id
LEFT JOIN 
    usuarios u ON pr.usuario_id = u.usuario_id;
    
    SELECT tipo_material_id as tpi, nombre, categoria FROM tipo_material


    INSERT INTO personales (personal_id, nombre, primer_apellido, segundo_apellido, telefono, correo)
     VALUES (0, 'Administrador', 'Gomez', 'perez','66432434', 'admin@gmail.com');

