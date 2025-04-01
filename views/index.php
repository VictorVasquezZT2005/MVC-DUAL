<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Evaluación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        body { padding-top: 20px; }
        .card { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Sistema de Evaluación</h1>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0"><i class="bi bi-people-fill"></i> Estudiantes</h5>
                    </div>
                    <div class="card-body">
                        <p>Gestión de estudiantes registrados en el sistema.</p>
                        <a href="/evaluacion/views/estudiantes/index.php" class="btn btn-primary">Ver Estudiantes</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0"><i class="bi bi-journal-bookmark-fill"></i> Notas</h5>
                    </div>
                    <div class="card-body">
                        <p>Registro y consulta de notas por módulo.</p>
                        <a href="/evaluacion/views/notas/index.php" class="btn btn-success">Ver Notas</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>