document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('solicitudForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Evitar el envío tradicional del formulario

        var formData = new FormData(this); // Obtener los datos del formulario
        const errorDiv = document.getElementById("error");

        fetch('/WMS2/LandingPage/phpFiles/Models/inventario_prestamo.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            var messageDiv = document.getElementById('message');
            if (data.success) {
                messageDiv.style.color = 'green';
                messageDiv.textContent = data.message; // Mostrar mensaje de éxito
            } else {
                messageDiv.style.color = 'red';
                messageDiv.textContent = data.message; // Mostrar mensaje de error
            }
        })
        .catch(error => {
            console.error('Error:', error);
            var messageDiv = document.getElementById('message');
            messageDiv.style.color = 'red';
            messageDiv.textContent = 'Hubo un error al procesar la solicitud. Por favor, inténtalo de nuevo.';
        });
    });
});