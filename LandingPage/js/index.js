function toggleMenu() {
    const menu = document.getElementById("menu");
    menu.style.display = menu.style.display === "block" ? "none" : "block";
}

document.addEventListener("click", function(event) {
    const menu = document.getElementById("menu");
    const headerMenu = document.getElementById("header-menu");
    if (!menu.contains(event.target) && !headerMenu.contains(event.target)) {
        menu.style.display = "none";
    }
});

document.addEventListener('DOMContentLoaded', function () {
    

    const logoutIcon = document.getElementById('logout-icon');

    if (logoutIcon) {
        logoutIcon.addEventListener('click', function () {
            window.location.href = '../html/login.html'; 
        });
    } else {
        console.log('No se encontró el ícono de cerrar sesión');
    }
});