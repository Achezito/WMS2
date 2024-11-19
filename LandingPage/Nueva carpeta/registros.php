<?php
// Iniciar sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('location: /WMS2/LandingPage/html/login.php');
    exit();
}

// Importar la clase de conexión
require_once '../phpFiles/config/conexion.php';

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $usuario_id = $_POST['personasID'];
    $personal_id = $_POST['personalesID'];
    $fecha_salida = $_POST['fechaSalida'];
    $fecha_devolucion = $_POST['fechaDevolucion'] ?? null;
    $notas = $_POST['notas'] ?? null;

    // Conectar a la base de datos
    $conn = Conexion::get_connection();

    // Crear la consulta SQL para insertar el registro
    $sql = "INSERT INTO prestamos (fecha_salida, fecha_devolucion, notas, personal_id, usuario_id) 
            VALUES (?, ?, ?, ?, ?)";

    // Preparar la consulta
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Error al preparar la consulta: " . $conn->error;
        exit();
    }

    // Vincular parámetros
    $stmt->bind_param("sssii", $fecha_salida, $fecha_devolucion, $notas, $personal_id, $usuario_id);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Registro exitoso: mostrar mensaje
        $mensaje = "¡Préstamo realizado con éxito!";
    } else {
        // Error al ejecutar: mostrar mensaje de error
        $mensaje = "Error al registrar el préstamo: " . $stmt->error;
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Préstamo</title>
    <meta http-equiv="refresh" content="5;url=/WMS2/LandingPage/formularios/prestamos.php">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        .mensaje {
            text-align: center;
            padding: 20px;
            border: 1px solid #ccc;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .mensaje h1 {
            font-size: 24px;
            color: #333;
        }
        .mensaje a {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            color: #fff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
        }
        .mensaje a:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
<header>
        <div id="header-left">
            <div id="header-menu" onclick="toggleMenu()" >
                <i class="fa fa-bars"></i>
            </div>
            <div id="header-logo">
                <img src="../img/Logos/LineLogo.png" >
            </div>
            <h1>CISTA</h1>
        </div>
        <div id="header-right">
            <div id="user-photo">
                <img src="../img/Users/User.jpg" alt="User Photo">
            </div>
            <div id="header-logos">
                <i class="fas fa-cog"></i>
                <i class="fas fa-globe"></i>
                <i class="fas fa-sign-out-alt" id="logout-icon"></i>
            </div>
        </div>
    </header>
    <div class="mensaje">
        <h1><?php echo $mensaje; ?></h1>
        <a href="/WMS2/LandingPage/formularios/prestamos.php">Volver</a>
    </div>
</body>
</html>
