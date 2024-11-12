






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


