<?php
define('ENVIRONMENT', 'XAMPP'); // Cambia a 'LAMP' cuando estés en el entorno LAMP

if (ENVIRONMENT === 'XAMPP') {
    define('BASE_PATH', 'C:/xampp/htdocs/WMS2/LandingPage');
} elseif (ENVIRONMENT === 'LAMP') {
    define('BASE_PATH', '/var/www/html/WMS2/LandingPage');
} else {
    die('El entorno no está definido correctamente.');
}
?>
