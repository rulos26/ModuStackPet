<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Olvidé mi contraseña</title>
</head>
<body>
    <h1>Olvidé mi contraseña</h1>
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <label for="email">Correo electrónico:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <button type="submit">Enviar enlace</button>
    </form>
</body>
</html>
