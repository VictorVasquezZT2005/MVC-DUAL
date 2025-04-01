<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Nota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Editar Nota</h1>
            <a href="index.php" class="btn btn-outline-secondary">Volver</a>
        </div>
        
        <div id="message"></div>
        
        <form id="notaForm">
            <input type="hidden" id="id">
            <div class="mb-3">
                <label for="modulo" class="form-label">Módulo</label>
                <input type="text" class="form-control" id="modulo" required>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="nota1" class="form-label">Nota 1 (30%)</label>
                    <input type="number" step="0.01" min="0" max="10" class="form-control" id="nota1" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="nota2" class="form-label">Nota 2 (30%)</label>
                    <input type="number" step="0.01" min="0" max="10" class="form-control" id="nota2" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="tarea" class="form-label">Tarea (40%)</label>
                    <input type="number" step="0.01" min="0" max="10" class="form-control" id="tarea" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="estudiante" class="form-label">Estudiante</label>
                <select class="form-select" id="estudiante" required>
                    <option value="">Seleccionar estudiante...</option>
                    <!-- Opciones se cargarán con JavaScript -->
                </select>
            </div>
            <button type="submit" class="btn btn-success">Guardar Cambios</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Obtener ID de la URL
            const urlParams = new URLSearchParams(window.location.search);
            const id = urlParams.get('id');
            
            // Cargar lista de estudiantes
            fetch('../../controllers/EstudianteController.php')
                .then(response => response.json())
                .then(data => {
                    const select = document.getElementById('estudiante');
                    
                    if(data.message) {
                        document.getElementById('message').innerHTML = `
                            <div class="alert alert-danger">${data.message}</div>
                        `;
                        return;
                    }
                    
                    data.records.forEach(estudiante => {
                        const option = document.createElement('option');
                        option.value = estudiante.id;
                        option.textContent = `${estudiante.nombre} (${estudiante.carnet})`;
                        select.appendChild(option);
                    });
                    
                    // Si hay un ID, cargar los datos de la nota
                    if(id) {
                        fetch(`../../controllers/NotaController.php?id=${id}`)
                            .then(response => response.json())
                            .then(data => {
                                document.getElementById('id').value = data.id;
                                document.getElementById('modulo').value = data.modulo;
                                document.getElementById('nota1').value = data.nota1;
                                document.getElementById('nota2').value = data.nota2;
                                document.getElementById('tarea').value = data.tarea;
                                document.getElementById('estudiante').value = data.id_estudiante;
                            })
                            .catch(error => console.error('Error:', error));
                    }
                })
                .catch(error => console.error('Error:', error));
            
            // Manejar envío del formulario
            document.getElementById('notaForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const nota = {
                    id: document.getElementById('id').value,
                    modulo: document.getElementById('modulo').value,
                    nota1: parseFloat(document.getElementById('nota1').value),
                    nota2: parseFloat(document.getElementById('nota2').value),
                    tarea: parseFloat(document.getElementById('tarea').value),
                    id_estudiante: document.getElementById('estudiante').value
                };
                
                fetch('../../controllers/NotaController.php', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(nota)
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
        });
    </script>
</body>
</html>