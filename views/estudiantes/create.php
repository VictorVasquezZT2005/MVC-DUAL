<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Estudiante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Nuevo Estudiante</h1>
            <a href="index.php" class="btn btn-outline-secondary">Volver</a>
        </div>
        
        <div id="message"></div>
        
        <form id="estudianteForm">
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
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('estudianteForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const estudiante = {
                nombre: document.getElementById('nombre').value,
                carnet: document.getElementById('carnet').value,
                carrera: document.getElementById('carrera').value
            };
            
            fetch('../../controllers/EstudianteController.php', {
                method: 'POST',
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
                    document.getElementById('estudianteForm').reset();
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