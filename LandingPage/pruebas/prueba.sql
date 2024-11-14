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
INSERT INTO edificios (edificio_id, nombre) VALUES
(1, 'docencia 1');

INSERT INTO edificios (nombre) VALUES 
('Campues tijuana');

-- PERSONALES Table
CREATE TABLE personales (
    personal_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    primer_apellido VARCHAR(50) NOT NULL,
    segundo_apellido VARCHAR(50),
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


    INSERT INTO personales (personal_id, nombre, primer_apellido, segundo_apellido, edificio_id) VALUES
(1, 'Juan', 'Pérez', 'Gómez', 1);


INSERT INTO cuentas (cuenta_id, nombre_usuario, contraseña, tipo_cuenta, usuario_id) 
VALUES (3,  'santipro', SHA1('1234'), 'usuario', 3);


INSERT INTO usuarios (nombre, descripcion, estado) 
VALUES ('Juan Pérez', 'Administrador del sistema', 'alta');


INSERT INTO cuentas (cuenta_id, personal_id, nombre_usuario, contraseña, tipo_cuenta) 
VALUES (1, 1, 'juanp', SHA1('holamundo'), 'personal');


    SELECT c.tipo_cuenta AS type, 
               c.cuenta_id AS id, 
               c.nombre_usuario AS username, 
               c.contraseña AS password, 
               p.personal_id AS personal_id, 
               u.usuario_id AS usuario_id
        FROM cuentas c
        LEFT JOIN personales p ON c.personal_id = p.personal_id
        LEFT JOIN usuarios u ON c.usuario_id = u.usuario_id
        WHERE c.nombre_usuario = 'santi';
-- MANTENIMIENTO Table
CREATE TABLE mantenimiento (
    mantenimiento_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    descripcion VARCHAR(250) NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_final DATE,
    personal_id INT,
    FOREIGN KEY (personal_id) REFERENCES personales(personal_id)
);

CREATE INDEX idx_nombre_usuario ON cuentas (nombre_usuario);


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


CREATE TABLE cuentas (
    cuenta_id INT PRIMARY KEY AUTO_INCREMENT,
    nombre_usuario VARCHAR(50) NOT NULL UNIQUE,
    contraseña VARCHAR(255) NOT NULL,
    tipo_cuenta ENUM('usuario', 'personal') NOT NULL,
    
    usuario_id INT,
    personal_id INT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(usuario_id),
    FOREIGN KEY (personal_id) REFERENCES personales(personal_id)
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
ALTER TABLE edificios AUTO_INCREMENT = 1;
ALTER TABLE personales AUTO_INCREMENT = 1;
ALTER TABLE estatus AUTO_INCREMENT = 1;
ALTER TABLE tipo_material AUTO_INCREMENT = 1;
ALTER TABLE materiales AUTO_INCREMENT = 1;
ALTER TABLE usuarios AUTO_INCREMENT = 1;
ALTER TABLE proveedores AUTO_INCREMENT = 1;
ALTER TABLE transacciones AUTO_INCREMENT = 1;
ALTER TABLE material_transaccion AUTO_INCREMENT = 1;
ALTER TABLE ppt AUTO_INCREMENT = 1;
ALTER TABLE prestamos AUTO_INCREMENT = 1;
ALTER TABLE material_prestamos AUTO_INCREMENT = 1;
ALTER TABLE mantenimiento AUTO_INCREMENT = 1;
ALTER TABLE mantenimiento_material AUTO_INCREMENT = 1;




-- Tabla: edificios
INSERT INTO edificios (edificio_id, nombre) VALUES 
(1, 'Edificio Principal'),
(2, 'Edificio Anexo');

-- Tabla: personales
INSERT INTO personales (personal_id, nombre, primer_apellido, segundo_apellido, correo, worker_password, worker_user, edificio_id) VALUES 
(1, 'Juan', 'Pérez', 'Lopez', 'juan.perez@correo.com', 'password1', 'jPerez', 1),
(2, 'Ana', 'Gomez', 'Martinez', 'ana.gomez@correo.com', 'password2', 'aGomez', 2);

-- Tabla: estatus
INSERT INTO estatus (estatus_id, estatus) VALUES 
(1, 'Disponible'),
(2, 'Mantenimiento'),
(3, 'Prestado');

-- Tabla: tipo_material
INSERT INTO tipo_material (tipo_material_id, nombre, categoria, descripcion) VALUES 
(1, 'Laptop', 'Computación', 'Dispositivo portátil'),
(2, 'Proyector', 'Audiovisual', 'Proyector de video');

-- Tabla: materiales
INSERT INTO materiales (material_id, serie, modelo, edificio_id, estatus_id, tipo_material_id) VALUES 
(1, 'ABC123', 'HP Envy', 1, 1, 1),
(2, 'XYZ456', 'Epson X100', 2, 1, 2);

-- Tabla: usuarios
INSERT INTO usuarios (usuario_id, nombre, descripcion, fecha_creacion, estado) VALUES 
(1, 'Mario Lopez', 'Profesor de matemáticas', CURRENT_TIMESTAMP, 'alta'),
(2, 'Sara Sanchez', 'Administrativo', CURRENT_TIMESTAMP, 'alta');

-- Tabla: proveedores
INSERT INTO proveedores (proveedor_id, nombre, telefono, correo) VALUES 
(1, 'Proveedor Uno', '5551234567', 'proveedor1@correo.com'),
(2, 'Proveedor Dos', '5557654321', 'proveedor2@correo.com');

-- Tabla: transacciones
INSERT INTO transacciones (transaccion_id, tipo_transaccion, fecha_inicio, fecha_final, notas) VALUES 
(1, 'Entrada', '2024-11-01 10:00:00', '2024-11-01 11:00:00', 'Ingreso inicial de materiales'),
(2, 'Salida', '2024-11-02 14:00:00', '2024-11-02 15:00:00', 'Material prestado para evento');

-- Tabla: material_transaccion
INSERT INTO material_transaccion (transaccion_id, material_id, cantidad) VALUES 
(1, 1, 5),
(1, 2, 3),
(2, 1, 1);

-- Tabla: ppt (Proveedor - Personal - Transaccion)
INSERT INTO ppt (ppt_id, transaccion_id, personal_id, proveedor_id) VALUES 
(1, 1, 1, 1),
(2, 2, 2, 2);

-- Tabla: prestamos
INSERT INTO prestamos (prestamo_id, fecha_salida, fecha_devolucion, notas, personal_id, usuario_id) VALUES 
(1, '2024-11-01', '2024-11-07', 'Préstamo para clase', 1, 1),
(2, '2024-11-02', '2024-11-10', 'Préstamo para reunión', 2, 2);

-- Tabla: material_prestamos
INSERT INTO material_prestamos (prestamo_id, material_id, cantidad) VALUES 
(1, 1, 1),
(2, 2, 2);

-- Tabla: mantenimiento
INSERT INTO mantenimiento (mantenimiento_id, descripcion, fecha_inicio, fecha_final, personal_id) VALUES 
(1, 'Revisión anual', '2024-10-01', '2024-10-05', 1),
(2, 'Cambio de lámpara', '2024-10-10', '2024-10-12', 2);

-- Tabla: mantenimiento_material
INSERT INTO mantenimiento_material (mantenimiento_id, material_id) VALUES 
(1, 1),
(2, 2);


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
ADD COLUMN usuario_id INT NOT NULL,
ADD FOREIGN KEY (usuario_id) REFERENCES usuarios(usuario_id);
2