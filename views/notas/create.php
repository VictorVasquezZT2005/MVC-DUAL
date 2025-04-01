<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Nota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Nueva Nota</h1>
            <?php if(isset($_GET['id_estudiante'])): ?>
                <a href="index.php?id_estudiante=<?php echo $_GET['id_estudiante']; ?>" class="btn btn-outline-secondary">Volver</a>
            <?php else: ?>
                <a href="index.php" class="btn btn-outline-secondary">Volver</a>
            <?php endif; ?>
        </div>
        
        <div id="message"></div>
        
        <form id="notaForm">
            <input type="hidden" id="id_estudiante" value="<?php echo $_GET['id_estudiante'] ?? ''; ?>">
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
            <?php if(!isset($_GET['id_estudiante'])): ?>
                <div class="mb-3">
                    <label for="estudiante" class="form-label">Estudiante</label>
                    <select class="form-select" id="estudiante" required>
                        <option value="">Seleccionar estudiante...</option>
                        <!-- Opciones se cargarán con JavaScript -->
                    </select>
                </div>
            <?php endif; ?>
            <button type="submit" class="btn btn-success">Guardar</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Si no hay un id_estudiante en el formulario, cargar la lista de estudiantes
            if(!document.getElementById('id_estudiante').value) {
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
                    })
                    .catch(error => console.error('Error:', error));
            }
            
            // Manejar envío del formulario
            document.getElementById('notaForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const nota = {
                    modulo: document.getElementById('modulo').value,
                    nota1: parseFloat(document.getElementById('nota1').value),
                    nota2: parseFloat(document.getElementById('nota2').value),
                    tarea: parseFloat(document.getElementById('tarea').value),
                    id_estudiante: document.getElementById('id_estudiante').value || 
                                   document.getElementById('estudiante').value
                };
                
                fetch('../../controllers/NotaController.php', {
                    method: 'POST',
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
                        document.getElementById('notaForm').reset();
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