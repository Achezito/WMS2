<?php
session_start();
// Incluir el archivo que contiene la función para dibujar el historial
require_once('../../../phpFiles/Models/historiales.php');
// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'personal') {
    header('location: /WMS2/LandingPage/html/login.php');
    exit();
}

// Actualizar el tiempo de último acceso
$_SESSION['ultimo_acceso'] = time();

if (isset($_SESSION['edificio_id'])) {
    $edificio_id = $_SESSION['edificio_id'];
} else {
    echo "Error: No se ha asignado un edificio al usuario actual.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cista</title>
    <link href="../../../css/fontawesome/fontawesome.css" rel="stylesheet" />
    <link href="../../../css/fontawesome/solid.css" rel="stylesheet" />
    <script src="../../../js/index.js"></script>
    <link rel="stylesheet" href="../../../css/hom2.css">
    <link rel="stylesheet" href="../../../css/historiales.css">
    <script>
        function cambiarHistorial(index) {
            // Ocultar todos los historiales
            const historiales = document.querySelectorAll('.historial');
            historiales.forEach(historial => historial.style.display = 'none');

            // Mostrar el historial seleccionado
            document.getElementById('historial-' + index).style.display = 'block';

            // Establecer el tipo de operación según el índice
            let tipoOperacion = '';
            switch (index) {
                case 0:
                    tipoOperacion = 'Operación';
                    break;
                case 1:
                    tipoOperacion = 'Préstamo';
                    break;
                case 2:
                    tipoOperacion = 'Mantenimiento';
                    break;
                case 3:
                    tipoOperacion = 'Transacción';
                    break;
            }

            // Asignar tipo de operación a los botones del historial correspondiente
            const buttons = document.querySelectorAll('#historial-' + index + ' .modal-button');
            buttons.forEach(button => {
                if (index == 0) {
                    // No asignamos tipo de operación ya que se extrae del HTML
                } else {
                    button.dataset.tipoOperacion = tipoOperacion;
                }
            });
        }

        function abrirModal(event) {
            const button = event.currentTarget;
            let tipoOperacion = button.dataset.tipoOperacion;
            const operacionId = button.dataset.operacionId;

            if (!tipoOperacion) {
                // Si no hay tipoOperacion en el dataset, obtener del texto del botón en la tabla del historial de operaciones
                tipoOperacion = button.closest('tr').querySelector('td:first-child').innerText.trim();
            }

            const modalOverlay = document.getElementById('modal-overlay');
            const modalBody = document.getElementById('modal-body');

            modalBody.innerHTML = `<p>Cargando detalles para la operación...</p>`;
            obtenerMateriales(tipoOperacion, operacionId);

            modalOverlay.style.display = 'flex';
        }

        function obtenerMateriales(tipoOperacion, operacionId) {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", `../../../phpFiles/Models/historiales.php?tipo_operacion=${tipoOperacion}&operacion_id=${operacionId}`, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.getElementById('modal-body').innerHTML = xhr.responseText;
                } else {
                    document.getElementById('modal-body').innerHTML = `<p>Error al cargar los datos.</p>`;
                }
            };
            xhr.onerror = function() {
                document.getElementById('modal-body').innerHTML = `<p>Error de red al intentar cargar los datos.</p>`;
            };
            xhr.send();
        }

        function cerrarModal() {
            // Oculta el modal y limpia el contenido del modal
            const modalOverlay = document.getElementById('modal-overlay');
            modalOverlay.style.display = 'none';
            document.getElementById('modal-body').innerHTML = ''; // Limpiar el contenido del modal
        }

        // Al cargar la página, mostrar solo el historial 0 (Actividad Personal)
        window.onload = function() {
            cambiarHistorial(0);
            document.getElementById('close-modal').onclick = cerrarModal;
        };
    </script>

</head>

<body>
    <div class="container">




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
        echo $_SESSION['fullname'];

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
            
        <li class="dropdown">
        <span class="dropdown-toggle">Formularios</span>
            <ul class="dropdown-menu">
                <li><a href="/WMS2/LandingPage/formularios/prestamos.php">Préstamos</a></li>
                <li><a href="/WMS2/LandingPage/formularios/transacciones.php">Transacciones</a></li>
                <li><a href="/WMS2/LandingPage/formularios/mantenimiento.php">Mantenimiento</a></li>
            </ul>
        </li>


        <li><a href="/WMS2/LandingPage/html/personal/users/users.php">
            <label class="linkLabel">
                Usuarios</label> 
        </a></li>

          <li><a href="/WMS2/LandingPage/html/personal/history/history.php">
            <label class="linkLabel">
                Historiales</label> 
          </a></li>

        </ul>
      </nav>
    </aside>
    </div>

    <main class="main-content">
    <section class="content">

        <!-- Botones para diferentes tipos de historial -->
        <div id="button-cards-container">
            <div class="button-card" data-index="0" onclick="cambiarHistorial(0)">
                <i class="fas fa-user-clock"></i> Actividad Personal
            </div>
            <div class="button-card" data-index="1" onclick="cambiarHistorial(1)">
                <i class="fas fa-clipboard-list"></i> Préstamos
            </div>
            <div class="button-card" data-index="2" onclick="cambiarHistorial(2)">
                <i class="fas fa-tools"></i> Mantenimientos
            </div>
            <div class="button-card" data-index="3" onclick="cambiarHistorial(3)">
                <i class="fas fa-truck"></i> Transacciones
            </div>
        </div>
    
        <!-- Recuadro grande para mostrar contenido -->
        
            <div class="content-box">
    
                <!-- Historial 0: Actividad Personal -->
                <div id="historial-0" class="historial" style="display:none;">
                    <?php
                    dibujar_historial_operaciones($edificio_id);
                    ?>
                </div>
    
                <!-- Historial 1: Préstamos -->
                <div id="historial-1" class="historial" style="display:none;">
                    <?php
                    dibujar_historial_prestamos($edificio_id);
                    ?>
                </div>
    
                <!-- Historial 2: Mantenimientos -->
                <div id="historial-2" class="historial" style="display:none;">
                    <?php
                    dibujar_historial_mantenimientos($edificio_id);
                    ?>
                </div>
    
                <!-- Historial 3: Transacciones -->
                <div id="historial-3" class="historial" style="display:none;">
                    <?php
                    dibujar_historial_transacciones($edificio_id);
                    ?>
                </div>
    
    
            </div>
        
        <div id="modal-overlay" style="display: none;">
            <div id="modal-content">
                <span id="close-modal">&times;</span>
                <div id="modal-body">
                    <!-- Contenido dinámico del modal irá aquí -->
                    <p>El contenido de "Ver Más" aparecerá aquí.</p>
                </div>
            </div>
        </div>

    </section>
    </main>

    
</body>

</html>