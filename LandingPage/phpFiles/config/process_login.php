<?php
// Mostrar todos los errores y advertencias
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/config.php';
require_once BASE_PATH . '/phpFiles/Models/cuentas.php';

try {
    // Asegúrate de que el método de solicitud sea POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido. Solo se aceptan solicitudes POST.');
    }

    // Verificar si los parámetros necesarios están presentes
    if (empty($_POST['username']) || empty($_POST['password'])) {
        throw new Exception('Faltan campos requeridos: username o password.');
    }

    // Sanitizar entradas
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

    // Intentar iniciar sesión
    $user = Cuenta::login($username, $password);

    // Validar el tipo de usuario y establecer la sesión
    if ($user instanceof Personal) {
        $_SESSION['user_type'] = 'personal';
        $_SESSION['user_id'] = $user->getPersonalId();
        $_SESSION['edificio_id'] = $user->getEdificioId();
        $_SESSION['fullname'] = $user->getFullname();
        $_SESSION['telefono'] = $user->getTelefono();
        $_SESSION['correo'] = $user->getCorreo();
        $_SESSION['personal_id'] = $user->getPersonalId();

        echo json_encode([
            "success" => true,
            "redirect" => " html/personal/indice/index.php",
            "debug" => "Sesión iniciada correctamente para personal."
        ]);
        exit;
    } elseif ($user instanceof Usuario) {
        $_SESSION['user_type'] = 'usuario';
        $_SESSION['user_id'] = $user->getUsuarioId();
        $_SESSION['username'] = $user->getUsername();
        $_SESSION['correo'] = $user->getCorreo();
        $_SESSION['nombre'] = $user->getNombre();
        $_SESSION['edificio_id'] = $user->getEdificioId();

        echo json_encode([
            "success" => true,
            "redirect" => "html/usuario/usuario.php",
            "debug" => "Sesión iniciada correctamente para usuario."
        ]);
        exit;
    } elseif ($user instanceof Administrador) {
        $_SESSION['user_type'] = 'administrador';
        $_SESSION['user_id'] = $user->getCuentaId();
        $_SESSION['username'] = $user->getUsername();

        echo json_encode([
            "success" => true,
            "redirect" => "html/admin/selectEdificio.php",
            "debug" => "Sesión iniciada correctamente para administrador."
        ]);
        exit;
    } else {
        // Si el login devuelve un mensaje de error
        echo json_encode([
            "success" => false,
            "message" => is_string($user) ? $user : 'Error desconocido en el proceso de autenticación.'
        ]);
        exit;
    }
} catch (Exception $e) {
    // Captura de excepciones y retorno del mensaje
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage(),
        "debug" => $e->getTraceAsString() // Agregar trazas para depuración
    ]);
    exit;
} catch (Error $err) {
    // Captura de errores fatales
    echo json_encode([
        "success" => false,
        "message" => "Se produjo un error interno en el servidor.",
        "debug" => $err->getMessage() . " en " . $err->getFile() . " línea " . $err->getLine()
    ]);
    exit;
}

// Respuesta genérica en caso de que no se cumplan las condiciones
echo json_encode([
    "success" => false,
    "message" => "Ocurrió un error inesperado. Inténtalo más tarde."
]);
?>
