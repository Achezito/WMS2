document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('addUserForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Evita que el formulario se envíe de manera tradicional

        var formData = new FormData(this);

        fetch('/WMS2/LandingPage/phpFiles/config/process_registerAdmin.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                // Si la respuesta no es exitosa, lanza un error con el código de estado y el cuerpo de la respuesta
                return response.text().then(text => { // Obtiene la respuesta como texto
                    throw new Error(`Error HTTP: ${response.status}, Respuesta: ${text}`);
                });
            }
            return response.json();  // Intenta procesar la respuesta como JSON si está bien
        })
        .then(data => {
            var messageDiv = document.getElementById('message');
            messageDiv.style.display = 'block';
        
            if (data.success) {
                messageDiv.innerHTML = `<p style="color: green;">${data.message}</p>`;
                window.location.href = data.redirect;
            } else {
                messageDiv.innerHTML = `<p style="color: red;">${data.message}</p>`;
            }
        })
        .catch(error => {
            console.error('Error al registrar el usuario:', error.message);
            // Aquí puedes mostrar el error específico en la UI si lo deseas
            var messageDiv = document.getElementById('message');
            messageDiv.style.display = 'block';
            messageDiv.innerHTML = `<p style="color: red;">${error.message}</p>`;
        });
        
    });
});




