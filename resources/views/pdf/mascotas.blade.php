<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información de la Mascota</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1, h2 { color: #333; text-align: center; }
        .card { border: 1px solid #ddd; border-radius: 5px; margin-bottom: 20px; }
        .card-header { background-color: #007bff; color: white; padding: 10px; border-radius: 5px 5px 0 0; }
        .card-body { padding: 15px; }
        .list-group { list-style: none; padding: 0; }
        .list-group-item { padding: 10px; border-bottom: 1px solid #ddd; }
        .list-group-item:last-child { border-bottom: none; }
        .description-header { font-weight: bold; }
        .description-text { float: right; }
        .avatar { display: block; margin: 0 auto 20px; width: 100px; height: 100px; border-radius: 50%; object-fit: cover; }
        .section-title { color: #007bff; margin-top: 20px; }
    </style>
</head>
<body>
    <h1>Información Detallada de la Mascota</h1>

    <!-- Información del Propietario -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Información del Propietario</h3>
        </div>
        <div class="card-body">
            <ul class="list-group">
                <li class="list-group-item">
                    <span class="description-header">Nombre:</span>
                    <span class="description-text">{{ $mascota->user->name ?? 'No especificado' }}</span>
                </li>
                <li class="list-group-item">
                    <span class="description-header">Correo Electrónico:</span>
                    <span class="description-text">{{ $mascota->user->email ?? 'No especificado' }}</span>
                </li>
                <li class="list-group-item">
                    <span class="description-header">Teléfono:</span>
                    <span class="description-text">{{ $mascota->user->telefono ?? 'No especificado' }}</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Información de la Mascota -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Información de la Mascota</h3>
        </div>
        <div class="card-body">
            <img src="{{ $rutaImagen }}" alt="Avatar de la mascota" class="avatar">
            <ul class="list-group">
                <li class="list-group-item">
                    <span class="description-header">Nombre:</span>
                    <span class="description-text">{{ $mascota->nombre }}</span>
                </li>
                <li class="list-group-item">
                    <span class="description-header">Edad:</span>
                    <span class="description-text">{{ $mascota->edad }} años</span>
                </li>
                <li class="list-group-item">
                    <span class="description-header">Género:</span>
                    <span class="description-text">{{ $mascota->genero }}</span>
                </li>
                <li class="list-group-item">
                    <span class="description-header">Raza:</span>
                    <span class="description-text">{{ $mascota->raza->nombre ?? 'No especificada' }}</span>
                </li>
                <li class="list-group-item">
                    <span class="description-header">Barrio:</span>
                    <span class="description-text">{{ $mascota->barrio->nombre ?? 'No especificado' }}</span>
                </li>
                <li class="list-group-item">
                    <span class="description-header">Fecha de Nacimiento:</span>
                    <span class="description-text">{{ $mascota->fecha_nacimiento ? \Carbon\Carbon::parse($mascota->fecha_nacimiento)->format('d/m/Y') : 'No especificada' }}</span>
                </li>
                <li class="list-group-item">
                    <span class="description-header">Vacunas Completas:</span>
                    <span class="description-text">{{ $mascota->vacunas_completas ? 'Sí' : 'No' }}</span>
                </li>
                <li class="list-group-item">
                    <span class="description-header">Última Vacunación:</span>
                    <span class="description-text">{{ $mascota->ultima_vacunacion ? \Carbon\Carbon::parse($mascota->ultima_vacunacion)->format('d/m/Y') : 'No especificada' }}</span>
                </li>
                <li class="list-group-item">
                    <span class="description-header">Esterilizado:</span>
                    <span class="description-text">{{ $mascota->esterilizado ? 'Sí' : 'No' }}</span>
                </li>
                <li class="list-group-item">
                    <span class="description-header">Dirección:</span>
                    <span class="description-text">{{ $mascota->direccion ?? 'No especificada' }}</span>
                </li>
                <li class="list-group-item">
                    <span class="description-header">Interior/Apartamento:</span>
                    <span class="description-text">{{ $mascota->interior_apto ?? 'No especificado' }}</span>
                </li>
                <li class="list-group-item">
                    <span class="description-header">Comportamiento:</span>
                    <span class="description-text">{{ $mascota->comportamiento ?? 'No especificado' }}</span>
                </li>
                <li class="list-group-item">
                    <span class="description-header">Recomendaciones:</span>
                    <span class="description-text">{{ $mascota->recomendaciones ?? 'No especificadas' }}</span>
                </li>
                <li class="list-group-item">
                    <span class="description-header">Enfermedades:</span>
                    <span class="description-text">{{ $mascota->enfermedades ?? 'Ninguna' }}</span>
                </li>
                <li class="list-group-item">
                    <span class="description-header">Último Examen Médico:</span>
                    <span class="description-text">{{ $mascota->ultimo_examen_medico ? \Carbon\Carbon::parse($mascota->ultimo_examen_medico)->format('d/m/Y') : 'No especificado' }}</span>
                </li>
            </ul>
        </div>
    </div>
</body>
</html>
