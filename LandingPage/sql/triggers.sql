DELIMITER $$

CREATE TRIGGER ActualizarEstadoPrestamo
AFTER UPDATE ON prestamos
FOR EACH ROW
BEGIN
    IF NEW.estatus = 'aprobado' THEN
        -- Al aprobar el préstamo, asignar el personal y cambiar el estado
        UPDATE prestamos
        SET personal_id = (
            SELECT personal_id 
            FROM personales 
            WHERE edificio_id = (
                SELECT edificio_id 
                FROM usuarios 
                WHERE usuario_id = NEW.usuario_id 
                LIMIT 1
            ) 
            LIMIT 1
        )
        WHERE prestamo_id = NEW.prestamo_id;
    ELSEIF NEW.estatus = 'rechazado' THEN
        -- Si el préstamo es rechazado, limpiar el personal asignado y la fecha de devolución
        UPDATE prestamos
        SET personal_id = NULL, fecha_devolucion = NULL
        WHERE prestamo_id = NEW.prestamo_id;
    ELSEIF NEW.estatus = 'finalizado' THEN
        -- Si el préstamo se finaliza, asignar la fecha de devolución
        UPDATE prestamos
        SET fecha_devolucion = CURRENT_DATE
        WHERE prestamo_id = NEW.prestamo_id;
    END IF;
END$$

DELIMITER ;


DELIMITER $$

CREATE TRIGGER ActualizarEstadoMaterialMantenimiento
AFTER INSERT ON mantenimiento
FOR EACH ROW
BEGIN
    -- Relacionar materiales con mantenimiento
    INSERT INTO mantenimiento_inventario (mantenimiento_id, material_id)
    SELECT NEW.mantenimiento_id, material_id FROM inventario
    WHERE estatus_id = (SELECT estatus_id FROM estatus WHERE estatus = 'En mantenimiento');

    -- Actualizar el estado de los materiales a 'En mantenimiento'
    UPDATE inventario
    SET estatus_id = (SELECT estatus_id FROM estatus WHERE estatus = 'En mantenimiento')
    WHERE material_id IN (SELECT material_id FROM mantenimiento_inventario WHERE mantenimiento_id = NEW.mantenimiento_id);
END$$

DELIMITER ;


