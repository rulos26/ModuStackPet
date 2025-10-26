<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información de la Empresa - {{ $empresa->nombre_legal }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
        }

        .logo {
            max-width: 120px;
            max-height: 120px;
            margin-bottom: 15px;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }

        .company-type {
            font-size: 14px;
            color: #6c757d;
            font-style: italic;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #007bff;
            margin: 25px 0 15px 0;
            padding: 10px;
            background-color: #e9ecef;
            border-left: 4px solid #007bff;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: white;
        }

        .info-table th {
            background-color: #007bff;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            font-size: 13px;
        }

        .info-table td {
            padding: 12px;
            border: 1px solid #dee2e6;
            vertical-align: top;
        }

        .info-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .info-table tr:hover {
            background-color: #e9ecef;
        }

        .label {
            font-weight: bold;
            color: #495057;
            width: 30%;
        }

        .value {
            color: #212529;
        }

        .status-active {
            background-color: #d4edda;
            color: #155724;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
        }

        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding-top: 20px;
        }

        .nit-display {
            font-family: 'Courier New', monospace;
            font-size: 14px;
            font-weight: bold;
            color: #007bff;
        }

        .contact-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }

        .location-info {
            background-color: #e7f3ff;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header con Logo y Nombre -->
        <div class="header">
            @if($empresa->logo)
                <img src="{{ public_path('storage/' . $empresa->logo) }}" alt="Logo" class="logo">
            @else
                <div style="width: 120px; height: 120px; background-color: #e9ecef; margin: 0 auto 15px auto; display: flex; align-items: center; justify-content: center; border-radius: 10px;">
                    <span style="font-size: 24px; color: #6c757d;">🏢</span>
                </div>
            @endif

            <div class="company-name">{{ $empresa->nombre_legal }}</div>
            @if($empresa->nombre_comercial)
                <div style="font-size: 16px; color: #6c757d; margin-bottom: 10px;">{{ $empresa->nombre_comercial }}</div>
            @endif
            <div class="company-type">{{ $empresa->tipoEmpresa->nombre ?? 'Tipo de empresa no especificado' }}</div>
        </div>

        <!-- Información de Identificación -->
        <div class="section-title">📋 Datos de Identificación</div>
        <table class="info-table">
            <thead>
                <tr>
                    <th>Campo</th>
                    <th>Información</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="label">NIT</td>
                    <td class="value">
                        <span class="nit-display">{{ $empresa->nit }}-{{ $empresa->dv }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">Representante Legal</td>
                    <td class="value">{{ $empresa->representante_legal }}</td>
                </tr>
                <tr>
                    <td class="label">Sector Económico</td>
                    <td class="value">{{ $empresa->sector->nombre ?? 'No especificado' }}</td>
                </tr>
                <tr>
                    <td class="label">Estado</td>
                    <td class="value">
                        @if($empresa->estado)
                            <span class="status-active">✅ ACTIVA</span>
                        @else
                            <span class="status-inactive">❌ INACTIVA</span>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Información de Contacto -->
        <div class="section-title">📞 Datos de Contacto</div>
        <div class="contact-info">
            <table class="info-table">
                <tbody>
                    <tr>
                        <td class="label">Teléfono</td>
                        <td class="value">{{ $empresa->telefono ?? 'No especificado' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Correo Electrónico</td>
                        <td class="value">{{ $empresa->email ?? 'No especificado' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Información de Ubicación -->
        <div class="section-title">📍 Ubicación</div>
        <div class="location-info">
            <table class="info-table">
                <tbody>
                    <tr>
                        <td class="label">Departamento</td>
                        <td class="value">{{ $empresa->departamento->nombre ?? 'No especificado' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Ciudad</td>
                        <td class="value">{{ $empresa->ciudad->municipio ?? 'No especificado' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Dirección</td>
                        <td class="value">{{ $empresa->direccion ?? 'No especificada' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Información Adicional -->
        <div class="section-title">ℹ️ Información Adicional</div>
        <table class="info-table">
            <tbody>
                <tr>
                    <td class="label">Fecha de Registro</td>
                    <td class="value">{{ $empresa->created_at->format('d/m/Y H:i:s') }}</td>
                </tr>
                <tr>
                    <td class="label">Última Actualización</td>
                    <td class="value">{{ $empresa->updated_at->format('d/m/Y H:i:s') }}</td>
                </tr>
                <tr>
                    <td class="label">ID de Registro</td>
                    <td class="value">#{{ $empresa->id }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p><strong>ModuStackPet</strong> - Sistema de Gestión de Mascotas</p>
            <p>Documento generado el {{ now()->format('d/m/Y H:i:s') }}</p>
            <p>Este documento contiene información confidencial de la empresa</p>
        </div>
    </div>
</body>
</html>
