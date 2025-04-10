<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; text-align: center; }
        p { line-height: 1.6; }
        .content { border: 1px solid #ddd; padding: 15px; border-radius: 5px; }
        .highlight { background-color: #f0f0f0; padding: 10px; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <div class="content">
        <p>Este es un ejemplo de PDF generado con DomPDF en Laravel. Aquí puedes ver diferentes estilos aplicados.</p>
        <div class="highlight">
            <p>Texto resaltado con un fondo diferente.</p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Columna 1</th>
                    <th>Columna 2</th>
                    <th>Columna 3</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Dato 1</td>
                    <td>Dato 2</td>
                    <td>Dato 3</td>
                </tr>
                <tr>
                    <td>Dato 4</td>
                    <td>Dato 5</td>
                    <td>Dato 6</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>