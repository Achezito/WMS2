document.addEventListener("DOMContentLoaded", function () {
    // Obtén el formulario de registro y el área de mensajes de error
    const registerForm = document.getElementById("registerForm");
    const errorDiv = document.getElementById("error");

    // Escucha el evento submit del formulario
    registerForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Evita el envío normal del formulario

        // Limpiar cualquier mensaje de error previo
        errorDiv.innerText = "";
        errorDiv.classList.remove("error-message", "success-message"); // Elimina clases de estilos previos

        // Crear un objeto FormData con los datos del formulario
        const formData = new FormData(registerForm);

        // Enviar la solicitud AJAX con fetch
        fetch("/WMS2/LandingPage/phpFiles/config/process_register.php", {
            method: "POST",
            body: formData,
        })
            .then((response) => {
                // Verificar si la respuesta es JSON válida
                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then((data) => {
                console.log(data); // Para depurar y ver lo que llega del servidor
                if (data.success) {
                    // Mostrar el mensaje de éxito en el div
                    errorDiv.innerText = "Registro exitoso. Redirigiendo...";
                    errorDiv.classList.add("success-message"); // Aplica estilo de éxito

                    // Redirigir después de 2 segundos
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 2000);
                } else {
                    // Mostrar el mensaje de error en el div
                    errorDiv.innerText = data.message || "Error desconocido en el servidor.";
                    errorDiv.classList.add("error-message"); // Aplica estilo de error
                }
            })
            .catch((error) => {
                console.error("Error en la solicitud:", error);
                errorDiv.innerText = `Error en el servidor: ${error.message}`; // Mensaje en caso de error en la solicitud
                errorDiv.classList.add("error-message"); // Aplica estilo de error
            });
    });
});

