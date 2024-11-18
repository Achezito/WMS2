

// Función para mostrar/ocultar el menú
function toggleMenu() {
    const menu = document.getElementById("menu");
    menu.style.display = menu.style.display === "block" ? "none" : "block";
}

// Cierra el menú si se hace clic fuera de él
document.addEventListener("click", function(event) {
    const menu = document.getElementById("menu");
    const headerMenu = document.getElementById("header-menu");
    if (!menu.contains(event.target) && !headerMenu.contains(event.target)) {
        menu.style.display = "none";
    }
});



document.addEventListener('DOMContentLoaded', function () {
    init();

    // Selecciona el ícono de cerrar sesión
    const logoutIcon = document.getElementById('logout-icon');

    // Verifica si el ícono existe
    if (logoutIcon) {
        logoutIcon.addEventListener('click', function () {
            // Redirige al login.html cuando se hace clic
            window.location.href = 'login.html'; // Ajusta la ruta si es necesario
        });
    } else {
        console.log('No se encontró el ícono de cerrar sesión');
    }
});

