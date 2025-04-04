<!DOCTYPE html>
<html>
<head>
    <title>Datos del Usuario</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 80%; margin: auto; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Datos del Usuario</h2>
        </div>
        <p><strong>Cédula:</strong> {{ $usuario->cedula }}</p>
        <p><strong>Nombre:</strong> {{ $usuario->nombre }}</p>
    </div>
</body>
</html>
