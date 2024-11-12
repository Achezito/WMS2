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
