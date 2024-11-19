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


INSERT INTO cuentas(contraseña) VALUES 
(SHA1('1234'))


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


ALTER TABLE usuarios
ADD CONSTRAINT fk_edificio FOREIGN KEY (edificio_id) REFERENCES edificios(edificio_id)

ALTER TABLE usuarios ADD COLUMN edificio_id INT NOT NULL;

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


ALTER TABLE prestamos

ADD FOREIGN KEY (usuario_id) REFERENCES usuarios(usuario_id);





SELECT 
FROM materiales 
WHERE


INSERT INTO usuarios (usuario_id, nombre, descripcion, estado, edificio_id)
VALUES (4, 'Jose Perez', 'Alumno', 'alta', 1),
(5, 'Jose Perez', 'Alumno', 'alta', 1),
(6, 'Jose Perez', 'Alumno', 'alta', 1),
(7, 'Jose Perez', 'Alumno', 'alta', 1),
(8, 'Jose Perez', 'Alumno', 'alta', 1);

