<<?php
session_start();
require_once __DIR__ . '/../../config/config.php';
require_once BASE_PATH . '/phpFiles/Models/edificios.php';

// Verificar si el usuario es administrador
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'administrador') {
    header('Location: ../../html/login.php');
    exit();
}

// Verificar el tiempo de inactividad
$limite_inactividad = 100000; // Tiempo en segundos
if (isset($_SESSION['ultimo_acceso'])) {
    $inactividad = time() - $_SESSION['ultimo_acceso'];
    if ($inactividad > $limite_inactividad) {
        session_unset();
        session_destroy();
        header("Location: ../../html/login.php?sesion=expirada");
        exit();
    }
}
$_SESSION['ultimo_acceso'] = time(); // Actualizar el último acceso



  
    $Edificios = Edificios::mostrarTodosLosEdificios();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edificios</title>
   
   
    <link rel="stylesheet" href="../../css/selectAdmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="../../js/index.js"></script>
    <script src="../../js/inventario_prestamoAJAX.js"></script>
</head>
<body>
    <div class="container">
        <h1>Asignar Edificio</h1>
        <label for="options">Selecciona un edificio</label>
        <select id="options">
        <?php
            if (!empty($Edificios)) {
                foreach ($Edificios as $e) {
                    echo '<option value="' . htmlspecialchars($e['edificio_id']) . '">' . htmlspecialchars($e['nombre']) . '</option>';
                }
            } else {
                echo '<option value="">No hay edificios</option>';
            }
        ?>
        </select>
        <button class="btn" onclick="asignarEdificio()">Confirmar</button>
        <p class="note">Una vez seleccionado podrás cambiar de edificio volviendo a iniciar sesión.</p>
    </div>

    <script>
        function asignarEdificio() {
            const selectedOption = document.getElementById('options').value;
            
            if (selectedOption) {
                // Redirigir a otra página con el id del edificio como parámetro
                window.location.href = `../../html/admin/indexAdmin.php?id=${selectedOption}`;
            } else {
                alert('Por favor, selecciona un edificio antes de continuar.');
            }
        }
    </script>
</body>

</html>
</html>
