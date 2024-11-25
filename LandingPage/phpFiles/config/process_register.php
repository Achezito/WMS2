<?php

require_once('conexion.php'); // Tu conexión a la base de datos
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['fullName'];
    $estado = $_POST['estado'] ?? 'alta'; // Estado por defecto es 'alta'
    $correo = $_POST['email'];
    $edificio_id = $_POST['edificio'];
    $nombre_usuario = $_POST['username'];
    $contrasena = hash('sha1', $_POST['password']); // Encriptar la contraseña con SHA-1
    $tipo_cuenta = 'usuario'; // Tipo de cuenta predeterminado

    try {
        // Obtener la conexión a la base de datos
        $connection = Conexion::get_connection();

        // Verificar si el correo ya existe
        $stmt = mysqli_prepare($connection, "SELECT COUNT(*) FROM usuarios WHERE correo = ?");
        if ($stmt === false) {
            throw new Exception("Error al preparar la consulta para verificar el correo.");
        }
        mysqli_stmt_bind_param($stmt, "s", $correo);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $correo_count);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if ($correo_count > 0) {
            throw new Exception("El correo ya está registrado.");
        }

        // Verificar si el nombre de usuario ya existe
        $stmt = mysqli_prepare($connection, "SELECT COUNT(*) FROM cuentas WHERE nombre_usuario = ?");
        if ($stmt === false) {
            throw new Exception("Error al preparar la consulta para verificar el nombre de usuario.");
        }
        mysqli_stmt_bind_param($stmt, "s", $nombre_usuario);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $username_count);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if ($username_count > 0) {
            throw new Exception("El nombre de usuario ya está registrado.");
        }

        // Iniciar la transacción
        Conexion::begin_transaction($connection);

        // Insertar en la tabla `usuarios`
        $stmt = mysqli_prepare($connection, "INSERT INTO usuarios (nombre, fecha_creacion, estado, correo, edificio_id) 
                                             VALUES (?, NOW(), ?, ?, ?)");
        if ($stmt === false) {
            throw new Exception("Error al preparar la consulta para insertar en 'usuarios'.");
        }

        mysqli_stmt_bind_param($stmt, "ssss", $nombre, $estado, $correo, $edificio_id);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error al insertar datos en la tabla 'usuarios'.");
        }

        // Obtener el ID del usuario insertado
        $usuario_id = mysqli_insert_id($connection);

        // Insertar en la tabla `cuentas`
        $stmt = mysqli_prepare($connection, "INSERT INTO cuentas (nombre_usuario, contraseña, tipo_cuenta, usuario_id) 
                                             VALUES (?, ?, ?, ?)");
        if ($stmt === false) {
            throw new Exception("Error al preparar la consulta para insertar en 'cuentas'.");
        }

        mysqli_stmt_bind_param($stmt, "sssi", $nombre_usuario, $contrasena, $tipo_cuenta, $usuario_id);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error al insertar datos en la tabla 'cuentas'.");
        }

        // Confirmar la transacción
        Conexion::commit_transaction($connection);

        // Respuesta JSON para la redirección
        echo json_encode(['success' => true, 'message' => 'Registro exitoso.', 'redirect' => '/WMS2/LandingPage/phpFiles/config/register.php']);

    } catch (Exception $e) {
        // Si ocurre un error, revertir la transacción
        Conexion::rollback_transaction($connection);
        // Responder con el mensaje de error en formato JSON
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>
