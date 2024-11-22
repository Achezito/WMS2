document.addEventListener("DOMContentLoaded", function() {
    // Obtén el formulario de registro y el área de mensajes de error
    const registerForm = document.getElementById("registerForm");
    const errorDiv = document.getElementById("error");

    // Escucha el evento submit del formulario
    registerForm.addEventListener("submit", function(event) {
        event.preventDefault(); // Evita el envío normal del formulario

        // Limpiar cualquier mensaje de error previo
        errorDiv.innerText = "";

        // Crear un objeto FormData con los datos del formulario
        const formData = new FormData(registerForm);

        // Enviar la solicitud AJAX con fetch
        fetch("/WMS2/LandingPage/phpFiles/config/process_register.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json()) // Convierte la respuesta en formato JSON
        .then(data => {
            console.log(data);  // Para depurar y ver lo que llega del servidor
            if (data.success) {
                // Redirigir a la página proporcionada por el servidor si el registro es exitoso
                window.location.href = data.redirect;
            } else {
                // Mostrar el mensaje de error en el div con id="error"
                errorDiv.innerText = data.message;
            }
        })
        .catch(error => {
            console.error("Error en la solicitud:", error);
            errorDiv.innerText = "Error en el servidor. Inténtalo más tarde."; // Mensaje en caso de error en la solicitud
        });
    });
});
