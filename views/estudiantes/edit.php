<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Estudiante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Editar Estudiante</h1>
            <a href="index.php" class="btn btn-outline-secondary">Volver</a>
        </div>
        
        <div id="message"></div>
        
        <form id="estudianteForm">
            <input type="hidden" id="id">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" required>
            </div>
            <div class="mb-3">
                <label for="carnet" class="form-label">Carnet (6 caracteres)</label>
                <input type="text" class="form-control" id="carnet" maxlength="6" required>
            </div>
            <div class="mb-3">
                <label for="carrera" class="form-label">Carrera</label>
                <input type="text" class="form-control" id="carrera" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Obtener ID de la URL
        const urlParams = new URLSearchParams(window.location.search);
        const id = urlParams.get('id');
        
        // Cargar datos del estudiante
        if(id) {
            fetch(`../../controllers/EstudianteController.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('id').value = data.id;
                    document.getElementById('nombre').value = data.nombre;
                    document.getElementById('carnet').value = data.carnet;
                    document.getElementById('carrera').value = data.carrera;
                })
                .catch(error => console.error('Error:', error));
        }
        
        // Manejar envío del formulario
        document.getElementById('estudianteForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const estudiante = {
                id: document.getElementById('id').value,
                nombre: document.getElementById('nombre').value,
                carnet: document.getElementById('carnet').value,
                carrera: document.getElementById('carrera').value
            };
            
            fetch('../../controllers/EstudianteController.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(estudiante)
            })
            .then(response => response.json())
            .then(data => {
                const messageDiv = document.getElementById('message');
                if(data.message.includes('correctamente')) {
                    messageDiv.innerHTML = `
                        <div class="alert alert-success">${data.message}</div>
                    `;
                } else {
                    messageDiv.innerHTML = `
                        <div class="alert alert-danger">${data.message}</div>
                    `;
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>