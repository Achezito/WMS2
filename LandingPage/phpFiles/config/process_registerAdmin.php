<?php

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

    // Inicializa un arreglo para los campos faltantes
    $campos_faltantes = [];
    if (!$nombre_usuario) $campos_faltantes[] = 'nombre_usuario';
    if (!$contrasena) $campos_faltantes[] = 'contrasena';
    if (!$tipo_cuenta) $campos_faltantes[] = 'tipo_cuenta';
    if (!$usuarioEmail) $campos_faltantes[] = 'correo';

    // Si hay campos faltantes, muestra un error detallado
    if (!empty($campos_faltantes)) {
        error_log("Faltan datos obligatorios: " . implode(', ', $campos_faltantes));
        echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios: ' . implode(', ', $campos_faltantes)]);
        exit;
    }

    // Lógica específica para tipo de cuenta
    if ($tipo_cuenta === 'personal') {
        if (!$nombre || !$primer_apellido || !$segundo_apellido || !$telefono) {
            echo json_encode(['success' => false, 'message' => 'Faltan datos para registrar un personal.']);
            exit;
        }
    } elseif ($tipo_cuenta === 'usuario') {
        if (!$usuarioNombre || !$estado || !$edificio_id) {
            echo json_encode(['success' => false, 'message' => 'Faltan datos para registrar un usuario.']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Tipo de cuenta no válido.']);
        exit;
    }

    // Validación de contraseñas
    if ($contrasena !== $confirmar_contrasena) {
        echo json_encode([
            'success' => false,
            'message' => "Las contraseñas no coinciden."
        ]);
        exit; // Asegúrate de salir del script después de devolver el error
    }
    
    if (strlen($contrasena) < 6) {
        echo json_encode([
            'success' => false,
            'message' => "La contraseña debe tener al menos 6 caracteres."
        ]);
        exit; // Asegúrate de salir del script después de devolver el error
    }

    // Encriptar la contraseña después de validarla
    $contrasena_encriptada = hash('sha1', $contrasena);
    error_log("Contraseña encriptada: $contrasena_encriptada");

    // Obtener la conexión a la base de datos
    $connection = Conexion::get_connection();

    // Verificar si el correo ya existe
    $stmt = mysqli_prepare($connection, "SELECT COUNT(*) FROM usuarios WHERE correo = ?");
    mysqli_stmt_bind_param($stmt, "s", $correo);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $correo_count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($correo_count > 0) {
        echo json_encode(['success' => false, 'message' => "El correo ya está registrado."]);
        exit; // Salir si el correo ya existe
    }

    // Verificar si el nombre de usuario ya existe
    $stmt = mysqli_prepare($connection, "SELECT COUNT(*) FROM cuentas WHERE nombre_usuario = ?");
    mysqli_stmt_bind_param($stmt, "s", $nombre_usuario);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $username_count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($username_count > 0) {
        echo json_encode(['success' => false, 'message' => "El nombre de usuario ya está registrado."]);
        exit; // Salir si el nombre de usuario ya existe
    }

    // Iniciar la transacción
    Conexion::begin_transaction($connection);

    // Insertar en la base de datos dependiendo del tipo de cuenta
    if ($tipo_cuenta === 'usuario') {
        $stmt = mysqli_prepare($connection, "INSERT INTO usuarios (nombre, fecha_creacion, estado, correo, edificio_id) VALUES (?, NOW(), ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssss", $usuarioNombre, $estado, $usuarioEmail, $edificio_id);
        if (!mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => false, 'message' => "Error al insertar en 'usuarios'."]);
            exit;
        }
        $usuario_id = mysqli_insert_id($connection);
        $stmt = mysqli_prepare($connection, "INSERT INTO cuentas (nombre_usuario, contraseña, tipo_cuenta, usuario_id, personal_id) VALUES (?, ?, ?, ?, NULL)");
        mysqli_stmt_bind_param($stmt, "sssi", $nombre_usuario, $contrasena_encriptada, $tipo_cuenta, $usuario_id);
        if (!mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => false, 'message' => "Error al insertar en 'cuentas'."]);
            exit;
        }
    } elseif ($tipo_cuenta === 'personal') {
        $stmt = mysqli_prepare($connection, "INSERT INTO personales (nombre, primer_apellido, segundo_apellido, telefono, correo, edificio_id) VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssssi", $nombre, $primer_apellido, $segundo_apellido, $telefono, $correo, $edificio_id);
        if (!mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => false, 'message' => "Error al insertar en 'personal'."]);
            exit;
        }
        $personal_id = mysqli_insert_id($connection);
        $stmt = mysqli_prepare($connection, "INSERT INTO cuentas (nombre_usuario, contraseña, tipo_cuenta, usuario_id, personal_id) VALUES (?, ?, ?, NULL, ?)");
        mysqli_stmt_bind_param($stmt, "sssi", $nombre_usuario, $contrasena_encriptada, $tipo_cuenta, $personal_id);
        if (!mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => false, 'message' => "Error al insertar en 'cuentas'."]);
            exit;
        }
    }

    // Si todo va bien, hacer commit
    Conexion::commit_transaction($connection);
    echo json_encode(['success' => true, 'message' => 'Registro exitoso.', 'redirect' => 'gestionar_usuarios.php']);
} else {
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no válido.']);
}
