document.addEventListener('DOMContentLoaded', () => {
    const cerrarSesionBtn = document.getElementById('cerrarSesionBtn');

    if (cerrarSesionBtn) {
        cerrarSesionBtn.addEventListener('click', async () => {
            if (confirm('¿Estás seguro de que quieres cerrar sesión?')) {
                try {
                    const response = await fetch('php/logout.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        // Si manejas tokens JWT, podrías enviarlo aquí para invalidar
                        // body: JSON.stringify({ token: localStorage.getItem('authToken') }) 
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        alert('Sesión cerrada correctamente.');
                        // Limpiar cualquier token o dato de sesión del cliente
                        localStorage.removeItem('authToken'); 
                        window.location.href = 'index.html'; // Redirigir a la página de inicio
                    } else {
                        alert(data.message || 'Error al cerrar sesión.');
                    }
                } catch (error) {
                    console.error('Error durante el cierre de sesión:', error);
                    alert('Ocurrió un error en el servidor. Inténtalo de nuevo más tarde.');
                }
            }
        });
    }

    // Aquí podrías añadir lógica para los botones de INGRESAR de cada opción
    document.querySelectorAll('.menu-item .btn-custom').forEach(button => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            const action = event.target.dataset.action;
            alert(`Navegando a la sección: ${action}`);
            // En una aplicación real, aquí redirigirías a la página correspondiente
            // window.location.href = `/${action}.html`; 
        });
    });
});