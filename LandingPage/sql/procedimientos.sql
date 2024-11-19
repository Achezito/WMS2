DELIMITER $$

CREATE PROCEDURE SolicitarPrestamo(
    IN p_usuario_id INT,
    IN p_material_id INT,
    IN p_cantidad INT,
    IN p_notas TEXT
)
BEGIN
    -- Verificar si hay suficiente cantidad de material en inventario
    DECLARE cantidad_disponible INT;
    SELECT cantidad INTO cantidad_disponible
    FROM inventario
    WHERE material_id = p_material_id;
    
    IF cantidad_disponible < p_cantidad THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Cantidad de material insuficiente en inventario';
    ELSE
        -- Insertar solicitud de préstamo en la tabla prestamos con estado 'pendiente'
        INSERT INTO prestamos (fecha_salida, estatus, notas, usuario_id)
        VALUES (CURRENT_DATE, 'pendiente', p_notas, p_usuario_id);

        -- Obtener el ID del préstamo recién insertado
        DECLARE p_prestamo_id INT;
        SET p_prestamo_id = LAST_INSERT_ID();

        -- Insertar los materiales solicitados en la tabla inventario_prestamos
        INSERT INTO inventario_prestamos (prestamo_id, material_id, cantidad)
        VALUES (p_prestamo_id, p_material_id, p_cantidad);
    END IF;
END$$

DELIMITER ;


DELIMITER $$

CREATE PROCEDURE InsertarTransaccion(
    IN tipo_transaccion VARCHAR(50),
    IN cantidad INT,
    IN material_id INT,
    IN notas TEXT
)
BEGIN
    -- Verificar que la cantidad no sea negativa
    IF cantidad <= 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La cantidad debe ser positiva';
    ELSE
        DECLARE transaccion_id INT;

        -- Insertar la transacción en la tabla transacciones
        INSERT INTO transacciones (tipo_transaccion, fecha_inicio, notas)
        VALUES (tipo_transaccion, CURRENT_TIMESTAMP, notas);

        -- Obtener el ID de la transacción recién insertada
        SET transaccion_id = LAST_INSERT_ID();

        -- Registrar los movimientos del inventario
        INSERT INTO inventario_transacciones (transaccion_id, material_id, cantidad)
        VALUES (transaccion_id, material_id, cantidad);
    END IF;
END$$

DELIMITER ;


DELIMITER $$

CREATE PROCEDURE ActualizarTransaccion(
    IN p_transaccion_id INT,
    IN p_tipo_transaccion VARCHAR(50),
    IN p_notas TEXT
)
BEGIN
    -- Verificar que la transacción exista
    IF NOT EXISTS (SELECT 1 FROM transacciones WHERE transaccion_id = p_transaccion_id) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Transacción no encontrada';
    ELSE
        -- Actualizar la transacción con el nuevo tipo y notas
        UPDATE transacciones
        SET tipo_transaccion = p_tipo_transaccion, notas = p_notas
        WHERE transaccion_id = p_transaccion_id;
    END IF;
END$$

DELIMITER ;


DELIMITER $$

CREATE PROCEDURE InsertarMantenimiento(
    IN p_descripcion VARCHAR(250),
    IN p_fecha_inicio DATE,
    IN p_fecha_final DATE,
    IN p_notas TEXT,
    IN p_personal_id INT,
    IN p_material_id INT
)
BEGIN
    DECLARE mantenimiento_id INT;

    -- Insertar el mantenimiento en la tabla mantenimiento
    INSERT INTO mantenimiento (descripcion, fecha_inicio, fecha_final, notas, personal_id)
    VALUES (p_descripcion, p_fecha_inicio, p_fecha_final, p_notas, p_personal_id);

    -- Obtener el ID del mantenimiento recién insertado
    SET mantenimiento_id = LAST_INSERT_ID();

    -- Registrar la relación del mantenimiento con el material
    INSERT INTO mantenimiento_inventario (mantenimiento_id, material_id)
    VALUES (mantenimiento_id, p_material_id);
    
END$$

DELIMITER ;


DELIMITER $$

CREATE PROCEDURE ActualizarMantenimiento(
    IN p_mantenimiento_id INT,
    IN p_descripcion VARCHAR(250),
    IN p_fecha_inicio DATE,
    IN p_fecha_final DATE,
    IN p_notas TEXT
)
BEGIN
    -- Verificar que el mantenimiento exista
    IF NOT EXISTS (SELECT 1 FROM mantenimiento WHERE mantenimiento_id = p_mantenimiento_id) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Mantenimiento no encontrado';
    ELSE
        -- Actualizar los datos del mantenimiento
        UPDATE mantenimiento
        SET descripcion = p_descripcion, fecha_inicio = p_fecha_inicio, 
            fecha_final = p_fecha_final, notas = p_notas
        WHERE mantenimiento_id = p_mantenimiento_id;
    END IF;
END$$

DELIMITER ;


DELIMITER $$

CREATE PROCEDURE RegistrarTransaccion(
    IN p_material_id INT,
    IN p_cantidad INT,
    IN p_tipo_transaccion VARCHAR(50),
    IN p_notas TEXT,
    IN p_proveedor_id INT
)
BEGIN
    -- Validar cantidad positiva
    IF p_cantidad <= 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La cantidad debe ser positiva';
    ELSE
        -- Insertar transacción en la tabla de transacciones
        INSERT INTO transacciones (tipo_transaccion, fecha_inicio, notas)
        VALUES (p_tipo_transaccion, CURRENT_TIMESTAMP, p_notas);

        -- Obtener el ID de la transacción recién insertada
        DECLARE p_transaccion_id INT;
        SET p_transaccion_id = LAST_INSERT_ID();

        -- Registrar en la tabla ppt la relación de transacción, proveedor y personal
        INSERT INTO ppt (transaccion_id, proveedor_id, personal_id)
        VALUES (p_transaccion_id, p_proveedor_id, (
            SELECT personal_id 
            FROM personales 
            WHERE edificio_id = (
                SELECT edificio_id 
                FROM usuarios 
                WHERE usuario_id = p_proveedor_id 
                LIMIT 1
            ) 
            LIMIT 1
        ));

        -- Actualizar las cantidades en inventario
        IF p_tipo_transaccion = 'entrada' THEN
            UPDATE inventario
            SET cantidad = cantidad + p_cantidad
            WHERE material_id = p_material_id;
        ELSEIF p_tipo_transaccion = 'salida' THEN
            UPDATE inventario
            SET cantidad = cantidad - p_cantidad
            WHERE material_id = p_material_id;
        END IF;
    END IF;
END$$

DELIMITER ;
