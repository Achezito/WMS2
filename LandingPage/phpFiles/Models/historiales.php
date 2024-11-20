<?php
require_once('../../../phpFiles/config/conexion.php');

// Función para obtener y mostrar cualquier historial
function dibujar_historial($tabla, $columnas, $campos, $edificio_id) {
    // Conexión a la base de datos
    $connection = Conexion::get_connection();

    // Consulta a la vista específica, filtrando por edificio_id
    $query = "SELECT $campos FROM $tabla WHERE edificio_id = ?";
    $command = $connection->prepare($query);
    $command->bind_param('i', $edificio_id);
    $command->execute();
    $resultado = $command->get_result();

    // Almacenar los resultados en un array
    $historial = [];
    while ($row = $resultado->fetch_assoc()) {
        $historial[] = $row;
    }

    // Cerrar la conexión
    $connection->close();

    // Verificar si se encontraron resultados
    if (!empty($historial)) {
        // Generar un ID único para la tabla
        $idTabla = 'tabla_' . $tabla;

        echo "<table id='$idTabla' class='display' style='width:100%'>
            <thead>
                <tr>";
        // Dibujar las cabeceras de la tabla
        foreach ($columnas as $columna) {
            echo "<th>" . htmlspecialchars($columna) . "</th>";
        }
        echo "</tr>
            </thead>
            <tbody>";

        // Dibujar cada fila de historial
        foreach ($historial as $registro) {
            echo "<tr>";
            foreach ($registro as $campo) {
                echo "<td>" . htmlspecialchars($campo) . "</td>";
            }
            echo "</tr>";
        }

        echo "</tbody>
            </table>";
    } else {
        echo "<p>No se encontraron registros para este edificio.</p>";
    }
}


// Funciones específicas para cada historial
function dibujar_historial_operaciones($edificio_id) {
    $columnas = ['Operación', 'Fecha Inicio', 'Fecha Final', 'Descripción', 'Responsable'];
    $campos = 'tipo_operacion, fecha_inicio, fecha_final, notas, responsable';
    dibujar_historial('historial_operaciones', $columnas, $campos, $edificio_id);
}

function dibujar_historial_prestamos($edificio_id) {
    $columnas = ['Notas', 'Solicitado Por', 'Estado', 'Responsable', 'Fecha Salida', 'Fecha Devolucion'];
    $campos = 'notas, Solicitado_Por, estatus, Responsable, fecha_salida, fecha_devolucion';
    dibujar_historial('historial_prestamos', $columnas, $campos, $edificio_id);
}

function dibujar_historial_mantenimientos($edificio_id) {
    $columnas = ['Tipo Material', 'Categoría', 'Descripción', 'Notas', 'Serie', 'Modelo', 'Fecha Inicio', 'Fecha Final', 'Responsable'];
    $campos = 'tipo_material_nombre, tipo_material_categoria, descripcion, notas, serie, modelo, fecha_inicio, fecha_final, responsable';
    dibujar_historial('historial_mantenimientos', $columnas, $campos, $edificio_id);
}

function dibujar_historial_transacciones($edificio_id) {
    $columnas = ['Tipo Transacción', 'Fecha Inicio', 'Fecha Final', 'Notas', 'Proveedor', 'Teléfono Proveedor', 'Correo Proveedor', 'Responsable'];
    $campos = 'tipo_transaccion, fecha_inicio, fecha_final, notas, proveedor_nombre, proveedor_telefono, proveedor_correo, personal_nombre';
    dibujar_historial('historial_transacciones', $columnas, $campos, $edificio_id);
}
?>
