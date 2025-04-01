<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Estudiantes</title>
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
            <h1>Lista de Estudiantes</h1>
            <a href="create.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Nuevo Estudiante</a>
        </div>
        
        <div id="message"></div>
        
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Carnet</th>
                        <th>Carrera</th>
                        <th>Fecha Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="estudiantesTable">
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
                    ¿Estás seguro de que deseas eliminar este estudiante?
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
            loadEstudiantes();
            
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
                deleteEstudiante(deleteId);
                confirmModal.hide();
            });
        });
        
        function loadEstudiantes() {
            fetch('../../controllers/EstudianteController.php')
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.getElementById('estudiantesTable');
                    tableBody.innerHTML = '';
                    
                    if(data.message) {
                        document.getElementById('message').innerHTML = `
                            <div class="alert alert-info">${data.message}</div>
                        `;
                        return;
                    }
                    
                    data.records.forEach(estudiante => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${estudiante.id}</td>
                            <td>${estudiante.nombre}</td>
                            <td>${estudiante.carnet}</td>
                            <td>${estudiante.carrera}</td>
                            <td>${estudiante.fecha_registro}</td>
                            <td>
                                <a href="edit.php?id=${estudiante.id}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="${estudiante.id}"><i class="bi bi-trash"></i></button>
                                <a href="../notas/index.php?id_estudiante=${estudiante.id}" class="btn btn-sm btn-info"><i class="bi bi-journal-text"></i></a>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                })
                .catch(error => console.error('Error:', error));
        }
        
        function deleteEstudiante(id) {
            fetch('../../controllers/EstudianteController.php', {
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
                    loadEstudiantes();
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