document.addEventListener("DOMContentLoaded", function () {
    const registerForm = document.getElementById("registerForm");
    const errorDiv = document.getElementById("error");

    registerForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Evita el envío normal del formulario

        // Limpiar mensajes de error previos
        errorDiv.innerText = "";
        errorDiv.classList.remove("error-message", "success-message");

        // Validar los campos del formulario
        let isValid = true;
        let errorMessages = [];

        const fullName = document.getElementById('fullName');
        const nameRegex = /^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/;

        if (fullName.value.trim().length < 3) {
            isValid = false;
            errorMessages.push('El nombre completo debe tener al menos 3 caracteres.');
        } else if (!nameRegex.test(fullName.value.trim())) {
            isValid = false;
            errorMessages.push('El nombre completo no debe contener caracteres especiales o números.');
        }

        const password = document.getElementById('newPassword');
        const confirmPassword = document.getElementById('confirmPassword');
        if (password.value.trim().length < 6) {
            isValid = false;
            errorMessages.push('La contraseña debe tener al menos 6 caracteres.');
        }
        if (password.value.trim() !== confirmPassword.value.trim()) {
            isValid = false;
            errorMessages.push('Las contraseñas no coinciden.');
        }

        // Mostrar errores si no es válido
        if (!isValid) {
            errorDiv.innerText = errorMessages.join('\n');
            errorDiv.classList.add("error-message");
            return; // Detén cualquier procesamiento adicional
        }

        // Si todo es válido, enviar con fetch
        const formData = new FormData(registerForm);

        fetch("/WMS2/LandingPage/phpFiles/config/process_register.php", {
            method: "POST",
            body: formData,
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then((data) => {
                if (data.success) {
                    errorDiv.innerText = "Registro exitoso. Redirigiendo...";
                    errorDiv.classList.add("success-message");

                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 2000);
                } else {
                    errorDiv.innerText = data.message || "Error desconocido en el servidor.";
                    errorDiv.classList.add("error-message");
                }
            })
            .catch((error) => {
                errorDiv.innerText = `Error en el servidor: ${error.message}`;
                errorDiv.classList.add("error-message");
            });
    });
});
