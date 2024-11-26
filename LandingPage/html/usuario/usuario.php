<?php
session_start();
require_once __DIR__ . '/../../config/config.php';
require_once BASE_PATH . '/phpFiles/Models/inventario.php';
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'usuario') {
    header('Location: /WMS2/LandingPage/html/login.php');
    exit();
}

// Verificar el tiempo de inactividad
$limite_inactividad = 100000; // Tiempo en segundos (puedes ajustarlo)

if (isset($_SESSION['ultimo_acceso'])) {
    $inactividad = time() - $_SESSION['ultimo_acceso'];
    if ($inactividad > $limite_inactividad) {
        session_unset();
        session_destroy();
        header("Location: /WMS2/LandingPage/html/login.php?sesion=expirada");
        exit();
    }
}

$_SESSION['ultimo_acceso'] = time(); // Actualizar el último acceso




// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_type'])) {
    header('location: /WMS2/LandingPage/html/login.php');
    exit();
}

if (isset($_SESSION['edificio_id'])) {
    $edificio_id = $_SESSION['edificio_id'];
    $materiales = Inventario::obtenerMaterialesPorEdificioYEstatus($edificio_id);
} else {
    echo "Error: No se ha asignado un edificio al usuario actual.";
    exit();
}

?>

        
      


<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Principal</title>
    <link rel="stylesheet" href="/WMS2/LandingPage/css/index.css">
   
    <link rel="stylesheet" href="/WMS2/LandingPage/css/hom2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="/WMS2/LandingPage/js/index.js"></script>
    <script src="/WMS2/LandingPage/js/inventario_prestamoAJAX.js"></script>
</head>
<body>
  <div class="container">
    <!-- Barra lateral -->
    <aside class="sidebar">
        <div class="logo-container">
            <!-- Contenedor para logo y nombre -->
            <h1 class="app-title">CISTA</h1>
            
          </div>
      <div class="profile">
      
          <!-- Perfil del usuario -->
          <img class="user-avatar" src="/WMS2/LandingPage/img/Users/User.jpg" alt="User Avatar">

        <h3 class="titleName">
          <?php
       
          echo $_SESSION['username'];
          ?>
        </h3>
        <p class="titleMail">
        <?php 
      echo $_SESSION['correo'];
        ?>

        
        </p>
      </div>
      <nav>
        <ul>
            
        <li>
            
        <a href="/WMS2/LandingPage/html/usuario/usuario.php">
           <label class="linkLabel">
                Solicitar</label> 
        </a></li>

        <li><a href="/WMS2/LandingPage/html/usuario/prestamosUser.php">
            <label class="linkLabel">
                Ver prestamos</label> 
        </a></li>

        <li><a href="/WMS2/LandingPage/phpFiles/config/logout.php">
            <label class="linkLabel">
                Logout</label> 
        </a></li>

    

        </ul>
      </nav>
    </aside>

    <!-- Contenido principal -->

    <main class="main-content">
    <section class="content">
        <h2>Formulario de Solicitud</h2>
        <form id="solicitudForm">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo $_SESSION['nombre']; ?>" readonly>
            </div>

            <input type="hidden" name="id" value="<?php echo $_SESSION['user_id']; ?>">

            <div class="form-group">
                <label for="material">Material(es)</label>
                <select id="material" name="material[]" multiple required>
                    <?php
                    if (!empty($materiales)) {
                        foreach ($materiales as $material) {
                            echo '<option value="' . htmlspecialchars($material['material_id']) . '">' . htmlspecialchars($material['modelo']) . ' (' . htmlspecialchars($material['tipo_material']) . ')</option>';
                        }
                    } else {
                        echo '<option value="">No hay materiales disponibles</option>';
                    }
                    ?>
                </select>
                <small style="color: #000;">Ctrl + Click para seleccionar múltiples materiales</small>
            </div>

            <div class="form-group">
                <label for="comentarios">Notas</label>
                <textarea name="comentarios" placeholder="Escribe tu comentario aqui" rows="4" cols="50" style="resize: none; outline:none;" required></textarea>
            </div>

            <div class="form-group">
                <label for="fecha">Fecha de Solicitud</label>
                <input type="date" id="fecha" name="fecha" value="<?php echo date('Y-m-d'); ?>" readonly>
            </div>

            <button type="submit" class="submit-btn">Enviar Solicitud</button>
            <div id="message"></div>

        </form>
    </section>
</main>

<script>
    // Obtener la fecha actual en formato YYYY-MM-DD
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); // Los meses empiezan desde 0
    var yyyy = today.getFullYear();

    today = yyyy + '-' + mm + '-' + dd; // Formato correcto para el input type="date"

    // Establecer la fecha actual en el campo de fecha
    document.getElementById('fecha').value = today;
</script>

    </main>
  </div>
</body>
</html>
