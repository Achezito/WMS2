document.addEventListener('DOMContentLoaded', function () {
document.getElementById('registerForm').addEventListener('submit', function (e) {
    e.preventDefault(); // Evita el envío del formulario hasta validar
    
    let isValid = true; // Variable para rastrear si el formulario es válido
    let errorMessages = []; // Lista para mensajes de error

    // Validar el campo "Edificio"
    const edificio = document.getElementById('edificio');
    if (!edificio.value) {
        isValid = false;
        errorMessages.push('Por favor, selecciona un edificio.');
    }

    // Validar el campo "Nombre completo"
    const fullName = document.getElementById('fullName');
    const nameRegex = /^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/; // Permite letras, tildes y espacios
    
    if (fullName.value.trim().length < 3) {
        isValid = false;
        errorMessages.push('El nombre completo debe tener al menos 3 caracteres.');
    } else if (!nameRegex.test(fullName.value.trim())) {
        isValid = false;
        errorMessages.push('El nombre completo no debe contener caracteres especiales o números.');
    }
    

    // Validar el campo "Correo electrónico"
    const email = document.getElementById('email');
    const emailRegex = /^[^\s@]{1,50}@[^\s@]{3,15}\.[^\s@]{2,10}$/;
    
    // Verificar que el correo no esté vacío
    if (email.value.trim() === "") {
        isValid = false;
        errorMessages.push('Por favor, introduce tu correo electrónico.');
    } 
    // Verificar si el correo cumple con el formato y longitud
    else if (!emailRegex.test(email.value.trim())) {
        isValid = false;
        if (email.value.length > 50) {
            errorMessages.push('El correo electrónico no debe tener más de 50 caracteres.');
        } else {
            errorMessages.push('Por favor, introduce un correo electrónico válido.');
        }
    }

    // Validar el campo "Nombre de usuario"
    const username = document.getElementById('newUsername');
    const usernameRegex = /^[A-Za-z0-9_]{3,15}$/;
    if (!usernameRegex.test(username.value.trim())) {
        isValid = false;
        errorMessages.push('El nombre de usuario debe ser alfanumérico (3-15 caracteres).');
    }

    // Validar el campo "Contraseña"
    const password = document.getElementById('newPassword');
    if (password.value.trim().length < 6) {
        isValid = false;
        errorMessages.push('La contraseña debe tener al menos 6 caracteres.');
    }

    // Validar el campo "Confirmar contraseña"
    const confirmPassword = document.getElementById('confirmPassword');
    if (confirmPassword.value.trim() !== password.value.trim()) {
        isValid = false;
        errorMessages.push('Las contraseñas no coinciden.');
    }

    // Mostrar errores si existen
    if (!isValid) {
        alert(errorMessages.join('\n')); // Muestra los errores en un solo mensaje
        return; // Detén el envío del formulario
    }

    // Si todo es válido, envía el formulario
    alert('Formulario enviado con éxito.');
    this.submit(); // Envía el formulario
});

});