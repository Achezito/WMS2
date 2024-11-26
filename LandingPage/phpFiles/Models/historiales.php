<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/WMS2/LandingPage/phpFiles/config/conexion.php');

if (isset($_GET['tipo_operacion']) && isset($_GET['operacion_id'])) {
    $tipo_operacion = $_GET['tipo_operacion'];
    $operacion_id = $_GET['operacion_id'];
    dibujar_historial_materiales($tipo_operacion, $operacion_id);
    exit();
}

// Función para obtener y mostrar cualquier historial
function dibujar_historial($tabla, $columnas, $campos, $edificio_id, $historialContexto = null, $ocultarBoton = false)
{
    // Conexión a la base de datos
    $connection = Conexion::get_connection();

    // Consulta a la base de datos
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

    // Dibujar la tabla
    if (!empty($historial)) {
        echo "<table id='tabla_$tabla' class='display' style='width:100%'>
            <thead>
                <tr>";
        foreach ($columnas as $columna) {
            echo "<th>" . htmlspecialchars($columna) . "</th>";
        }

        if (!$ocultarBoton) {
            echo "<th>Acciones</th>";
        }

        echo "</tr>
            </thead>
            <tbody>";

        foreach ($historial as $registro) {
            echo "<tr>";
            foreach ($registro as $campo) {
                echo "<td>" . htmlspecialchars($campo) . "</td>";
            }

            if (!$ocultarBoton) {
                $tipoOperacion = isset($registro['tipo_operacion']) ? htmlspecialchars($registro['tipo_operacion']) : '';
                $operacionId = htmlspecialchars($registro['operacion_id']);
                echo "<td>
                        <button 
                            class='ver-mas-btn'
                            data-tipo-operacion='$tipoOperacion' 
                            data-operacion-id='$operacionId' 
                            data-index='$operacionId' 
                            onclick='abrirModal(event)'>
                            Ver Más
                        </button>
                    </td>";
            }

            echo "</tr>";
        }


        echo "</tbody>
            </table>";
    } else {
        echo "< class='p1'>No se encontraron registros para este edificio.</p>";
    }
}






// Funciones específicas para cada historial
function dibujar_historial_operaciones($edificio_id)
{
    $columnas = ['Operación', 'Operacion Id', 'Fecha Inicio', 'Fecha Final', 'Descripción', 'Responsable'];
    $campos = 'tipo_operacion, operacion_id, fecha_inicio, fecha_final, notas, responsable';
    dibujar_historial('historial_operaciones', $columnas, $campos, $edificio_id);
}

function dibujar_historial_prestamos($edificio_id)
{
    $columnas = ['Prestamo Id', 'Notas', 'Solicitado Por', 'Estado', 'Responsable', 'Fecha Salida', 'Fecha Devolucion'];
    $campos = 'operacion_id, notas, Solicitado_Por, estatus, Responsable, fecha_salida, fecha_devolucion';
    dibujar_historial('historial_prestamos', $columnas, $campos, $edificio_id);
}

function dibujar_historial_mantenimientos($edificio_id)
{
    $columnas = ['Mantenimiento Id', 'Tipo Material', 'Categoría', 'Descripción', 'Notas', 'Serie', 'Modelo', 'Fecha Inicio', 'Fecha Final', 'Responsable'];
    $campos = 'operacion_id, tipo_material_nombre, tipo_material_categoria, descripcion, notas, serie, modelo, fecha_inicio, fecha_final, responsable';
    dibujar_historial('historial_mantenimientos', $columnas, $campos, $edificio_id, null, true);  // Pasar true para ocultar la columna "Acciones"
}



function dibujar_historial_transacciones($edificio_id)
{
    $columnas = ['Transaccion Id', 'Tipo Transacción', 'Fecha Inicio', 'Fecha Final', 'Notas', 'Proveedor', 'Teléfono Proveedor', 'Correo Proveedor', 'Responsable'];
    $campos = 'operacion_id, tipo_transaccion, fecha_inicio, fecha_final, notas, proveedor_nombre, proveedor_telefono, proveedor_correo, personal_nombre';
    dibujar_historial('historial_transacciones', $columnas, $campos, $edificio_id);
}

// Función para obtener y mostrar historial de materiales
function dibujar_historial_materiales($tipo_operacion, $operacion_id)
{
    // Conexión a la base de datos
    $connection = Conexion::get_connection();

    // Consulta ajustada según los parámetros
    $query = "SELECT material_id, material_nombre, serie, modelo 
          FROM historial_materiales
          WHERE CONVERT(tipo_operacion USING utf8mb4) = ? 
          AND operacion_id = ?";
    $command = $connection->prepare($query);
    $command->bind_param('si', $tipo_operacion, $operacion_id);  // 's' para tipo_operacion (string), 'i' para operacion_id (int)

    // Ejecutar la consulta
    $command->execute();
    $resultado = $command->get_result();

    // Almacenar los resultados en un array
    $materiales = [];
    while ($row = $resultado->fetch_assoc()) {
        $materiales[] = $row;
    }

    // Cerrar la conexión
    $connection->close();

    // Verificar si se encontraron resultados
    if (!empty($materiales)) {
        echo "<table id='tabla_historial_materiales' class='display' style='width:100%'>
                <thead>
                    <tr>
                        <th>Material ID</th>
                        <th>Material Nombre</th>
                        <th>Serie</th>
                        <th>Modelo</th>
                    </tr>
                </thead>
                <tbody>";
        foreach ($materiales as $registro) {
            echo "<tr>";
            foreach ($registro as $campo) {
                echo "<td>" . htmlspecialchars($campo) . "</td>";
            }
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No se encontraron materiales.</p>";
    }
}
