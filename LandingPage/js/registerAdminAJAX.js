document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('addUserForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Evita el envío tradicional del formulario

        const formData = new FormData(this);
        const messageDiv = document.getElementById('message');

        fetch('../../phpFiles/config/process_registerAdmin.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                // Si la respuesta no es exitosa, lanza un error con el código de estado
                return response.text().then(text => {
                    throw new Error(`Error HTTP ${response.status}: ${text}`);
                });
            }
            return response.json(); // Procesa la respuesta como JSON
        })
        .then(data => {
            messageDiv.style.display = 'block';

            if (data.success) {
                // Registro exitoso
                messageDiv.innerHTML = `<p style="color: green;">${data.message}</p>`;
                setTimeout(() => window.location.href = data.redirect, 2000); // Redirige después de 2 segundos
            } else {
                // Muestra los errores enviados por PHP
                messageDiv.innerHTML = `<p style="color: red;">${data.message}</p>`;
            }
        })
        .catch(error => {
            // Errores inesperados o problemas de red
            console.error('Error:', error.message);
            messageDiv.style.display = 'block';
            messageDiv.innerHTML = `<p style="color: red;">Ha ocurrido un error inesperado: ${error.message}</p>`;
        });
    });
});
