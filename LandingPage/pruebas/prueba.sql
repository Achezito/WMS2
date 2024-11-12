CREATE DATABASE pruebaProyecto;
use pruebaProyecto;
CREATE TABLE users (
    id_users int PRIMARY Key AUTO_INCREMENT,
    user_name VARCHAR(20) not null,
    user_password VARCHAR(50) not null
)


INSERT INTO users (user_name, user_password) VALUE 
('santiPrah12','elmejor1234'),
('marianaNoob', 'pobr3t0na'),
('gamyGod', 'JOJO');

SELECT id_users, user_name ,user_password FROM users where user_name = 'gamyGod';



CREATE DATABASE WMS;

use WMS;

-- INSTITUCIONES Table

-- ESTADO Table
CREATE TABLE estatus (
    estatus_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    estatus VARCHAR(50) NOT NULL 
);

-- TIPO_MATERIAL Table
CREATE TABLE tipo_material (
    tipo_material_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    categoria VARCHAR(50),
    descripcion VARCHAR(255)
);

-- PROVEEDORES Table
CREATE TABLE proveedores (
    proveedor_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(15),
    correo VARCHAR(100) UNIQUE NOT NULL
);

-- EDIFICIOS Table
CREATE TABLE edificios (
    edificio_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombre VARCHAR(100) NOT NULL
);

INSERT INTO edificios (nombre) VALUES 
('Campues tijuana');

-- PERSONALES Table
CREATE TABLE personales (
    personal_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    primer_apellido VARCHAR(50) NOT NULL,
    segundo_apellido VARCHAR(50),
    correo VARCHAR(100) UNIQUE NOT NULL,
    edificio_id INT NOT NULL,
    FOREIGN KEY (edificio_id) REFERENCES edificios(edificio_id)
);
SELECT * FROM personales;
ALTER TABLE personales AUTO_INCREMENT = 1;


INSERT INTO personales (nombre, primer_apellido, segundo_apellido, correo, worker_user,worker_password, edificio_id) VALUES
('Jese Santiago', ' Perez', 'Salazar', 'ut-tijuana@gmail.com', 'santi',SHA1('holamundo'), 1),
('Jese Santiago', ' Perez', 'Salazar', 'ut-tijuana2@gmail.com', 'gamyyy',SHA1('holamundo'), 1);
SHOW TABLE STATUS LIKE 'personales';

ALTER TABLE personales 
    ADD COLUMN worker_user VARCHAR(255) NOT NULL;

    ALTER TABLE personales 
    ADD COLUMN worke_password VARCHAR(255) NOT NULL COMMENT 'Contraseña encriptada del trabajador';



-- MANTENIMIENTO Table
CREATE TABLE mantenimiento (
    mantenimiento_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    descripcion VARCHAR(250) NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_final DATE,
    personal_id INT,
    FOREIGN KEY (personal_id) REFERENCES personales(personal_id)
);

-- USUARIOS Table (PERSONAS)
CREATE TABLE usuarios (
    usuario_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255),
    fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('baja','alta') DEFAULT 'alta'
);

-- TRANSACCIONES Table
CREATE TABLE transacciones (
    transaccion_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    tipo_transacciong VARCHAR(50) NOT NULL,
    fecha_inicio TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
fecha_final DATETIME,
    notas TEXT
);

-- MATERIAL Table
CREATE TABLE materiales (
    material_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    serie VARCHAR(50) UNIQUE NOT NULL,
    modelo VARCHAR(50) NOT NULL,
    edificio_id INT,
    estatus_id INT NOT NULL,  -- Cambié 'estatus' por 'estatus_id' para ser una FK
    tipo_material_id INT,
    FOREIGN KEY (edificio_id) REFERENCES edificios(edificio_id),
    FOREIGN KEY (estatus_id) REFERENCES estatus(estatus_id),  -- FK hacia 'estatus'
    FOREIGN KEY (tipo_material_id) REFERENCES tipo_material(tipo_material_id)
);



-- PRESTAMOS Table
CREATE TABLE prestamos (
    prestamo_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    fecha_salida DATE NOT NULL,
    fecha_devolucion DATE,
    notas TEXT,
    personal_id INT,
    FOREIGN KEY (personal_id) REFERENCES personales(personal_id)
);

-- MANTENIMIENTO_MATERIAL Table
CREATE TABLE mantenimiento_material (
    mantenimiento_id INT,
    material_id INT,
    PRIMARY KEY (mantenimiento_id, material_id),
    FOREIGN KEY (mantenimiento_id) REFERENCES mantenimiento(mantenimiento_id),
    FOREIGN KEY (material_id) REFERENCES materiales(material_id)
);

-- MATERIAL_TRANSACCION Table
CREATE TABLE material_transaccion (
    transaccion_id INT,
    material_id INT,
    cantidad INT NOT NULL,
    PRIMARY KEY (transaccion_id, material_id),
    FOREIGN KEY (transaccion_id) REFERENCES transacciones(transaccion_id),
    FOREIGN KEY (material_id) REFERENCES materiales(material_id)
);

-- MATERIAL_PRESTAMOS Table
CREATE TABLE material_prestamos (
    prestamo_id INT,
    material_id INT,
    cantidad INT NOT NULL,
    PRIMARY KEY (prestamo_id, material_id),
    FOREIGN KEY (prestamo_id) REFERENCES prestamos(prestamo_id),
    FOREIGN KEY (material_id) REFERENCES materiales(material_id)
);

-- PPT Table (Préstamos-Proveedores-Transacciones)
CREATE TABLE ppt (
    ppt_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    transaccion_id INT,
    personal_id INT,
    proveedor_id INT,
    FOREIGN KEY (transaccion_id) REFERENCES transacciones(transaccion_id),
    FOREIGN KEY (personal_id) REFERENCES personales(personal_id),
    FOREIGN KEY (proveedor_id) REFERENCES proveedores(proveedor_id)
);




INSERT INTO edificios (nombre) VALUES 
('Campus Tijuana'),
('Campus Mexicali');


INSERT INTO estatus (estatus) VALUES
('Disponible'),
('En mantenimiento'),
('Prestado'),
('Fuera de servicio');
ALTER TABLE estatus AUTO_INCREMENT = 1;



INSERT INTO tipo_material (nombre, categoria, descripcion) VALUES
('Laptop', 'Electrónica', 'Computadora portátil de uso general'),
('Proyector', 'Electrónica', 'Proyector multimedia para presentaciones'),
('Silla', 'Mobiliario', 'Silla de oficina ergonómica');

INSERT INTO proveedores (nombre, telefono, correo) VALUES
('Proveedor A', '6611234567', 'proveedorA@empresa.com'),
('Proveedor B', '6617654321', 'proveedorB@empresa.com');

INSERT INTO personales (nombre, primer_apellido, segundo_apellido, correo, worker_user, worker_password, edificio_id) VALUES
('Juan', 'Lopez', 'Martinez', 'juan.lopez@empresa.com', 'juanito', SHA1('holamundo'), 1),
('Ana', 'Ramirez', 'Sanchez', 'ana.ramirez@empresa.com', 'anita', SHA1('holamundo'), 2);


INSERT INTO materiales (serie, modelo, edificio_id, estatus_id, tipo_material_id) VALUES
('12345A', 'Dell XPS 13', 1, 1, 1),  -- Laptop, Disponible
('67890B', 'Epson 3000', 1, 2, 2),  -- Proyector, En mantenimiento
('11223C', 'Silla Ergo', 2, 3, 3);  -- Silla, Prestado


INSERT INTO prestamos (fecha_salida, fecha_devolucion, notas, personal_id) VALUES
('2024-11-01', '2024-11-10', 'Préstamo de laptop para uso en clase', 1),
('2024-11-05', '2024-11-15', 'Préstamo de proyector para presentación', 2);

INSERT INTO material_prestamos (prestamo_id, material_id, cantidad) VALUES
(1, 4, 1),  -- Prestamo de 1 Laptop
(2, 5, 1);  -- Prestamo de 1 Proyector


INSERT INTO transacciones (tipo_transaccion, fecha_inicio, fecha_final, notas) VALUES
('Ingreso', '2024-11-01 08:00:00', '2024-11-01 09:00:00', 'Ingreso de material de oficina'),
('Egreso', '2024-11-05 10:00:00', NULL, 'Egreso de material para presentación');

INSERT INTO material_transaccion (transaccion_id, material_id, cantidad) VALUES
(1, 4, 5),  -- 5 Laptops ingresadas
(2, 5, 3);  -- 3 Proyectores egresados

INSERT INTO mantenimiento (descripcion, fecha_inicio, fecha_final, personal_id) VALUES
('Mantenimiento preventivo de proyectores', '2024-11-01', '2024-11-03', 1),
('Revisión de sillas ergonómicas', '2024-11-05', NULL, 2);



INSERT INTO mantenimiento_material (mantenimiento_id, material_id) VALUES
(1, 5),  -- Mantenimiento al proyector
(2, 6);  -- Mantenimiento a la silla


CREATE VIEW vista_materiales AS
SELECT 
    m.material_id,
    m.serie,
    m.modelo,
    e.nombre AS edificio,
    s.estatus AS estatus,
    t.nombre AS tipo_material
FROM 
    materiales m
JOIN 
    estatus s ON m.estatus_id = s.estatus_id
JOIN 
    tipo_material t ON m.tipo_material_id = t.tipo_material_id
JOIN 
    edificios e ON m.edificio_id = e.edificio_id;


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
    m.modelo
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
ADD COLUMN usuario_id INT NOT NULL,
ADD FOREIGN KEY (usuario_id) REFERENCES usuarios(usuario_id);
