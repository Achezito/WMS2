<?php

require_once('conexion.php'); // Tu conexión a la base de datos
header('Content-Type: application/json');

// Agregar log para depuración
error_log("Iniciando el proceso de registro de usuario.");

require_once('conexion.php'); // Tu conexión a la base de datos
header('Content-Type: application/json');

// Agregar log para depuración
error_log("Iniciando el proceso de registro de usuario.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir los datos del formulario
    $nombre = $_POST['nombre'] ?? null; // Solo se llena si tipoCuenta es "personal"
    $usuarioNombre = $_POST['usuarioNombre'] ?? null; // Solo se llena si tipoCuenta es "usuario"
    $primer_apellido = $_POST['firstName'] ?? null; // Solo para personal
    $segundo_apellido = $_POST['lastName'] ?? null; // Solo para personal
    $usuarioEmail = $_POST['usuarioEmail'] ?? null; // Solo para usuario
    $telefono = $_POST['telefono'] ?? null; // Solo para personal
    $estado = $_POST['estado'] ?? 'alta'; // Estado por defecto es 'alta'
    $correo = $_POST['email'] ?? null;
    $edificio_id = $_POST['edificio'] ?? null;
    $nombre_usuario = $_POST['username'] ?? null;
    $contrasena = $_POST['password'] ?? null;
    $confirmar_contrasena = $_POST['confirmPassword'] ?? null;
    $tipo_cuenta = $_POST['tipoCuenta'] ?? null;

    // Validar datos básicos
    if (!$nombre_usuario || !$contrasena || !$tipo_cuenta || !$correo) {
        error_log("Faltan datos obligatorios para registrar al usuario.");
        echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
        exit;
    }

    // Aquí puedes agregar lógica específica para cada tipo de cuenta
    if ($tipo_cuenta === 'personal') {
        if (!$nombre || !$primer_apellido || !$segundo_apellido || !$telefono) {
            error_log("Faltan datos para registrar un personal.");
            echo json_encode(['success' => false, 'message' => 'Faltan datos para registrar un personal.']);
            exit;
        }
        // Procesar registro de "personal"
    } elseif ($tipo_cuenta === 'usuario') {
        if (!$usuarioNombre || !$estado || !$usuarioEmail ||  !$edificio_id) {
            error_log("Faltan datos para registrar un usuario.");
            echo json_encode(['success' => false, 'message' => 'Faltan datos para registrar un usuario.']);
            exit;
        }
        // Procesar registro de "usuario"
    } else {
        error_log("Tipo de cuenta no válido: $tipo_cuenta");
        echo json_encode(['success' => false, 'message' => 'Tipo de cuenta no válido.']);
        exit;
    }


    try {
        // **Validar contraseñas**
        if ($contrasena !== $confirmar_contrasena) {
            throw new Exception("Las contraseñas no coinciden.");
        }
        if (strlen($contrasena) < 6) {
            throw new Exception("La contraseña debe tener al menos 6 caracteres.");
        }

        // Encriptar la contraseña después de validarla
        $contrasena_encriptada = hash('sha1', $contrasena);
        error_log("Contraseña encriptada: $contrasena_encriptada");

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

        // Depuración: Verificar si el correo existe
        error_log("Correo existente: $correo_count");

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

        // Depuración: Verificar si el nombre de usuario existe
        error_log("Nombre de usuario existente: $username_count");

        if ($username_count > 0) {
            throw new Exception("El nombre de usuario ya está registrado.");
        }

        // Iniciar la transacción
        Conexion::begin_transaction($connection);

        // Si el tipo de cuenta es 'usuario', insertar en la tabla `usuarios`
        if ($tipo_cuenta === 'usuario') {
            // Insertar en la tabla `usuarios`
            $stmt = mysqli_prepare($connection, "INSERT INTO usuarios (nombre, fecha_creacion, estado, correo, edificio_id) 
                                                 VALUES (?, NOW(), ?, ?, ?)");
            if ($stmt === false) {
                throw new Exception("Error al preparar la consulta para insertar en 'usuarios'.");
            }

            mysqli_stmt_bind_param($stmt, "ssss", $usuarioNombre, $estado, $usuarioEmail, $edificio_id);
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Error al insertar datos en la tabla 'usuarios'.");
            }

            // Obtener el ID del usuario insertado
            $usuario_id = mysqli_insert_id($connection);
            error_log("Usuario insertado con ID: $usuario_id");

            // Insertar en la tabla `cuentas` con `usuario_id` y `personal_id` como NULL
            $stmt = mysqli_prepare($connection, "INSERT INTO cuentas (nombre_usuario, contraseña, tipo_cuenta, usuario_id, personal_id) 
                                                 VALUES (?, ?, ?, ?, NULL)");
            if ($stmt === false) {
                throw new Exception("Error al preparar la consulta para insertar en 'cuentas'.");
            }

            mysqli_stmt_bind_param($stmt, "sssi", $nombre_usuario, $contrasena_encriptada, $tipo_cuenta, $usuario_id);
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Error al insertar datos en la tabla 'cuentas'.");
            }

        // Si el tipo de cuenta es 'personal', insertar en la tabla `personal`
        } elseif ($tipo_cuenta === 'personal') {
            // Insertar en la tabla `personal`
            $stmt = mysqli_prepare($connection, "INSERT INTO personales (nombre, primer_apellido, segundo_apellido, telefono, correo, edificio_id) 
                                                 VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt === false) {
                throw new Exception("Error al preparar la consulta para insertar en 'personal'.");
            }

            mysqli_stmt_bind_param($stmt, "sssssi", $nombre, $primer_apellido, $segundo_apellido, $telefono, $correo, $edificio_id);
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Error al insertar datos en la tabla 'personal'.");
            }

            // Obtener el ID del personal insertado
            $personal_id = mysqli_insert_id($connection);
            error_log("Personal insertado con ID: $personal_id");

            // Insertar en la tabla `cuentas` con `personal_id` y `usuario_id` como NULL
            $stmt = mysqli_prepare($connection, "INSERT INTO cuentas (nombre_usuario, contraseña, tipo_cuenta, usuario_id, personal_id) 
                                                 VALUES (?, ?, ?, NULL, ?)");
            if ($stmt === false) {
                throw new Exception("Error al preparar la consulta para insertar en 'cuentas'.");
            }

            mysqli_stmt_bind_param($stmt, "sssi", $nombre_usuario, $contrasena_encriptada, $tipo_cuenta, $personal_id);
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Error al insertar datos en la tabla 'cuentas'.");
            }

        } else {
            throw new Exception("Tipo de cuenta inválido.");
        }

        // Confirmar la transacción
        Conexion::commit_transaction($connection);
        error_log("Transacción confirmada, usuario registrado exitosamente.");

        // Respuesta JSON para la redirección
        echo json_encode(['success' => true, 'message' => 'Registro exitoso.', 'redirect' => 'html/admin/gestionar_usuarios.php']);

    } catch (Exception $e) {
        // Si ocurre un error, revertir la transacción
        Conexion::rollback_transaction($connection);
        // Responder con el mensaje de error en formato JSON
        error_log("Error en el proceso de registro: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>
