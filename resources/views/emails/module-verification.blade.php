<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Código de Verificación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #dc3545;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 5px 5px;
        }
        .verification-code {
            background-color: #fff;
            border: 2px solid #dc3545;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 3px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔒 Verificación de Seguridad</h1>
    </div>

    <div class="content">
        <h2>Confirmación requerida para {{ $action }} módulo</h2>

        <p>Se ha solicitado {{ $action }} el módulo <strong>{{ $moduleName }}</strong> en el sistema.</p>

        <p>Para completar esta acción crítica, utiliza el siguiente código de verificación:</p>

        <div class="verification-code">
            {{ $verificationCode }}
        </div>

        <div class="warning">
            <strong>⚠️ Importante:</strong> Este código expira en 10 minutos y solo puede ser usado una vez.
            Si no solicitaste este cambio, contacta inmediatamente al administrador del sistema.
        </div>

        <p>Ingresa este código en el formulario de confirmación para proceder con la operación.</p>
    </div>

    <div class="footer">
        <p>Este es un correo automático del sistema ModuStackPet. No respondas a este mensaje.</p>
        <p>Fecha: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
