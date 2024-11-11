function verificarCamposYRegistrar() {
    // Selecciona todos los campos de entrada requeridos
    const campos = document.querySelectorAll("input[required]");

    // Revisa si alguno está vacío
    for (const campo of campos) {
        if (campo.value.trim() === "") {
            alert("Rellenar los campos pedidos");
            return; // Detiene la función si algún campo está vacío
        }
    }

    // Si todos los campos están llenos, muestra el mensaje de éxito
    alert("Tu cuenta fue registrada, en proceso de aceptación");
}

function iniciarSesion() {
    // Selecciona todos los campos de entrada requeridos
    const campos = document.querySelectorAll("input[required]");

    // Revisa si alguno está vacío
    for (const campo of campos) {
        if (campo.value.trim() === "") {
            alert("Rellenar los campos pedidos");
            return; // Detiene la función si algún campo está vacío
        }
    }

    // Si todos los campos están llenos, redirige a index.html
    window.location.href = "index.html"; // Redirige al index
}

document.addEventListener('DOMContentLoaded', function () {
    const prestamoForm = document.getElementById('prestamo-form');

    if (prestamoForm) {
        prestamoForm.addEventListener('submit', function (event) {
            event.preventDefault(); // Evita el envío del formulario

            // Llama a la función de verificación de campos y muestra el mensaje de éxito
            RegistroPrestamo();
        });
    }
});

function RegistroPrestamo() {
    // Selecciona todos los campos de entrada requeridos
    const campos = document.querySelectorAll("input[required]");

    // Revisa si alguno está vacío
    for (const campo of campos) {
        if (campo.value.trim() === "") {
            alert("Rellenar los campos pedidos");
            return; // Detiene la función si algún campo está vacío
        }
    }

    // Si todos los campos están llenos, muestra el mensaje de éxito
    alert("Registro exitoso, en espera de aceptación");
}


