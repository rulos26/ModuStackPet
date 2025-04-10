<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; text-align: center; }
        .card { border: 1px solid #ddd; border-radius: 5px; margin-bottom: 20px; }
        .card-header { background-color: #007bff; color: white; padding: 10px; border-radius: 5px 5px 0 0; }
        .card-body { padding: 15px; }
        .list-group { list-style: none; padding: 0; }
        .list-group-item { padding: 10px; border-bottom: 1px solid #ddd; }
        .list-group-item:last-child { border-bottom: none; }
        .description-header { font-weight: bold; }
        .description-text { float: right; }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Información Detallada de la Mascota</h3>
        </div>
        <div class="card-body">
            <ul class="list-group">
                <li class="list-group-item">
                    <span class="description-header">Nombre:</span>
                    <span class="description-text">Firulais</span>
                </li>
                <li class="list-group-item">
                    <span class="description-header">Edad:</span>
                    <span class="description-text">3 años</span>
                </li>
                <li class="list-group-item">
                    <span class="description-header">Género:</span>
                    <span class="description-text">Macho</span>
                </li>
                <li class="list-group-item">
                    <span class="description-header">Raza:</span>
                    <span class="description-text">Labrador</span>
                </li>
                <li class="list-group-item">
                    <span class="description-header">Barrio:</span>
                    <span class="description-text">Centro</span>
                </li>
            </ul>
        </div>
    </div>
</body>
</html>