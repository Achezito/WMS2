document.addEventListener("DOMContentLoaded", function() {
    const loginForm = document.getElementById("loginForm");
    const errorDiv = document.getElementById("error");

    loginForm.addEventListener("submit", function(event) {
        event.preventDefault(); // Evita el envío normal del formulario

        // Limpiar cualquier mensaje de error anterior
        errorDiv.innerText = "";

        // Crear objeto FormData con los datos del formulario
        const formData = new FormData(loginForm);

        // Enviar la solicitud AJAX con fetch
        fetch("../phpFiles/config/process_login.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json()) // Convierte la respuesta en JSON
        .then(data => {
            console.log(data);  // Para ver lo que llega del servidor
            if (data.success) {
                window.location.href = data.redirect; // Redirige si el login es exitoso
            } else {
                // Muestra el mensaje de error en el div con id="error"
                errorDiv.innerText = data.message; 
            }
        })
        .catch(error => {
            console.error("Error en la solicitud:", error);
            errorDiv.innerText = "Error en el servidor. Inténtalo más tarde."; // Mensaje en caso de error en la solicitud
        });
    });
});

