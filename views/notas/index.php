<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Notas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        body { padding-top: 20px; }
        .table-responsive { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <?php if(isset($_GET['id_estudiante'])): ?>
                <h1>Notas del Estudiante</h1>
                <a href="create.php?id_estudiante=<?php echo $_GET['id_estudiante']; ?>" class="btn btn-success"><i class="bi bi-plus-circle"></i> Nueva Nota</a>
            <?php else: ?>
                <h1>Lista de Notas</h1>
                <a href="create.php" class="btn btn-success"><i class="bi bi-plus-circle"></i> Nueva Nota</a>
            <?php endif; ?>
        </div>
        
        <div id="message"></div>
        
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Módulo</th>
                        <th>Nota 1 (30%)</th>
                        <th>Nota 2 (30%)</th>
                        <th>Tarea (40%)</th>
                        <th>Promedio</th>
                        <th>Estudiante</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="notasTable">
                    <!-- Datos se cargarán con JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal de confirmación -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar esta nota?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Obtener parámetros de la URL
            const urlParams = new URLSearchParams(window.location.search);
            const idEstudiante = urlParams.get('id_estudiante');
            
            // Cargar notas
            if(idEstudiante) {
                loadNotas(`?id_estudiante=${idEstudiante}`);
            } else {
                loadNotas();
            }
            
            // Configurar el modal de confirmación
            var confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
            var deleteId = 0;
            
            // Manejar clic en botones de eliminar
            document.addEventListener('click', function(e) {
                if(e.target.classList.contains('delete-btn')) {
                    deleteId = e.target.getAttribute('data-id');
                    confirmModal.show();
                }
            });
            
            // Confirmar eliminación
            document.getElementById('confirmDelete').addEventListener('click', function() {
                deleteNota(deleteId);
                confirmModal.hide();
            });
        });
        
        function loadNotas(query = '') {
            fetch(`../../controllers/NotaController.php${query}`)
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.getElementById('notasTable');
                    tableBody.innerHTML = '';
                    
                    if(data.message) {
                        document.getElementById('message').innerHTML = `
                            <div class="alert alert-info">${data.message}</div>
                        `;
                        return;
                    }
                    
                    data.records.forEach(nota => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${nota.id}</td>
                            <td>${nota.modulo}</td>
                            <td>${nota.nota1}</td>
                            <td>${nota.nota2}</td>
                            <td>${nota.tarea}</td>
                            <td><strong>${nota.promedio.toFixed(2)}</strong></td>
                            <td>${nota.nombre_estudiante || 'N/A'}</td>
                            <td>
                                <a href="edit.php?id=${nota.id}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="${nota.id}"><i class="bi bi-trash"></i></button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                })
                .catch(error => console.error('Error:', error));
        }
        
        function deleteNota(id) {
            fetch('../../controllers/NotaController.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                const messageDiv = document.getElementById('message');
                if(data.message.includes('correctamente')) {
                    messageDiv.innerHTML = `
                        <div class="alert alert-success">${data.message}</div>
                    `;
                    // Recargar notas
                    const urlParams = new URLSearchParams(window.location.search);
                    const idEstudiante = urlParams.get('id_estudiante');
                    if(idEstudiante) {
                        loadNotas(`?id_estudiante=${idEstudiante}`);
                    } else {
                        loadNotas();
                    }
                } else {
                    messageDiv.innerHTML = `
                        <div class="alert alert-danger">${data.message}</div>
                    `;
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>