<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Generado</title>
</head>
<body>
    <h2>Código QR</h2>
    {!! $qr !!}
    <br>
    <a href="{{ url()->previous() }}" class="btn btn-secondary">Volver</a>
</body>
</html>
