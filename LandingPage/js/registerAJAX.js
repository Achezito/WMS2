document.addEventListener("DOMContentLoaded", function() {
    const registerForm = document.getElementById("registerForm");
    const errorDiv = document.getElementById("error");

    registerForm.addEventListener("submit", function(event) {
        event.preventDefault(); // Evitar envío normal del formulario

        errorDiv.innerText = ""; // Limpiar mensajes de error

        const formData = new FormData(registerForm); // Crear FormData con datos del formulario

        fetch("../phpFiles/config/process_register.php", {
            method: "POST",
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            const messageDiv = document.getElementById('message');

            if (data.success) {
                messageDiv.style.color = 'green';
                messageDiv.textContent = data.message; // Mostrar mensaje de éxito

                // Redirigir a la URL proporcionada después de 2 segundos
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 2000); // Ajusta el tiempo de espera si es necesario
            } else {
                messageDiv.style.color = 'red';
                messageDiv.textContent = data.message; // Mostrar mensaje de error
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const messageDiv = document.getElementById('message');
            messageDiv.style.color = 'red';
            messageDiv.textContent = 'Hubo un error al procesar la solicitud. Por favor, inténtalo de nuevo.';
        });
    });
});
