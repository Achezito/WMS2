<?php
require_once('../phpFiles/config/conexion.php');

// Función para obtener y mostrar cualquier historial
function dibujar_historial($tabla, $columnas, $campos) {
    // Conexión a la base de datos
    $connection = Conexion::get_connection();

    // Consulta a la vista específica
    $query = "SELECT $campos FROM $tabla";
    $command = $connection->prepare($query);
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
        echo "<p>No se encontraron registros.</p>";
    }
}


// Funciones específicas para cada historial
function dibujar_historial_operaciones() {
    $columnas = ['Operación', 'Fecha Inicio', 'Fecha Final', 'Descripción', 'Responsable'];
    $campos = 'tipo_operacion, fecha_inicio, fecha_final, notas, responsable';
    dibujar_historial('historial_operaciones', $columnas, $campos);
}

function dibujar_historial_prestamos() {
    $columnas = ['Materiales', 'Cantidad', 'Fecha Salida', 'Fecha Devolución', 'Estatus', 'Notas', 'Responsable', 'Solicitado Por'];
    $campos = 'material, cantidad, fecha_salida, fecha_devolucion, estatus, notas, Responsable, Solicitado_Por';
    dibujar_historial('historial_prestamos', $columnas, $campos);
}

function dibujar_historial_mantenimientos() {
    $columnas = ['Tipo Material', 'Categoría', 'Descripción', 'Notas', 'Serie', 'Modelo', 'Fecha Inicio', 'Fecha Final', 'Responsable'];
    $campos = 'tipo_material_nombre, tipo_material_categoria, descripcion, notas, serie, modelo, fecha_inicio, fecha_final, responsable';
    dibujar_historial('historial_mantenimientos', $columnas, $campos);
}

function dibujar_historial_transacciones() {
    $columnas = ['Tipo Transacción', 'Fecha Inicio', 'Fecha Final', 'Notas', 'Modelo', 'Serie', 'Tipo Material', 'Cantidad', 'Proveedor', 'Teléfono Proveedor', 'Correo Proveedor', 'Responsable'];
    $campos = 'tipo_transaccion, fecha_inicio, fecha_final, notas, modelo, serie, tipo_material_nombre, cantidad, proveedor_nombre, proveedor_telefono, proveedor_correo, personal_nombre';
    dibujar_historial('historial_transacciones', $columnas, $campos);
}
?>
