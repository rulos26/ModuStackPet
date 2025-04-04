<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <title>Reporte Usuarios</title>
    <style>
        @page {
            margin: 50px 0;
        }

        body {
            margin: 0 25px;
            font-family: 'Arial, sans-serif';
            border: 2px solid red;
            box-sizing: border-box;
            padding: 0px;
        }

        h1 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 10px;
            text-align: left;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50px;
            background-color: #f1f1f1;
            text-align: center;
            line-height: 50px;
            font-size: 12px;
            border-top: 1px solid #000;
        }

        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 50px;
            background-color: #ffffff;
            text-align: center;
            line-height: 50px;
            font-size: 14px;
        }

        .content {
            margin-bottom: 60px;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="{{ 'storage/header/2.png' }}" alt="" width="700px" height="150px">
        <table class="customTable" style="margin-left: 1.3%; margin-right: 76%; width: 680px">
            <thead>
                <tr>
                    <th colspan="12"
                        style="font-weight: bold; background-color: #ABABAB; border: 1px solid black; text-align: center;">
                        AUTORIZACIÓN
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="12" style="border: 1px solid black; text-align: justify; font-size: x-small;">
                        <div class="carta">
                            <p>Yo {{ $afiliadoArray[0]['nombres'] ?? 'Nombre no disponible' }}</p>
                            <p>Identificado (a) con cédula de ciudadanía No. {{ $afiliadoArray[0]['cedula'] ?? 'No
                                disponible' }}</p>
                            <p>Expedida en: {{ $afiliadoArray[0]['municipio']['nombre'] ?? 'No disponible' }} </p>
                            <p>Hago constar de manera libre y voluntaria que la información procesada en el presente
                                estudio, obedece a la verdad y <strong>AUTORIZO</strong> plenamente a la empresa
                                <strong>GRUPO DE TAREAS EMPRESARIALES</strong> con NIT <strong>830.142.258-3</strong>
                                para realizar VERIFICACIÓN ACADÉMICA, VERIFICACIÓN JUDICIAL, CENTRAL DE RIESGOS LEY 1266
                                y LEY 1581 del 2012 habeas data. Para tomar las pruebas necesarias y suficientes, a fin
                                de establecer la veracidad de la información suministrada, para que en el momento que se
                                haga necesaria se utilice como prueba. Contemplando en el <strong>DECRETO 1266 DE
                                    2008</strong>.
                            </p>
                        </div>
                    </td>
                </tr>
              <tr>
                    <td colspan="3"
                        style="font-size: x-small; font-weight: bold; background-color: #ABABAB; border: 1px solid black;">
                        Dirección
                    </td>
                    <td colspan="3" style="font-size: x-small; border: 1px solid black;">
                        <p>{{ $afiliadoArray[0]['direccion'] ?? 'No disponible' }}</p>
                    </td>
                    <td colspan="3"
                        style="font-size: x-small; font-weight: bold; background-color: #ABABAB; border: 1px solid black;">
                        Barrio
                    </td>
                    <td colspan="3" style="font-size: x-small; border: 1px solid black;">
                        <p>{{ $afiliadoArray[0]['barrio'] ?? 'No disponible' }}</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="3"
                        style="font-size: x-small; font-weight: bold; background-color: #ABABAB; border: 1px solid black;">
                        Localidad
                    </td>
                    <td colspan="3" style="font-size: x-small; border: 1px solid black;">
                        <p>{{ $afiliadoArray[0]['localidad'] ?? 'No disponible' }}</p>
                    </td>
                    <td colspan="3"
                        style="font-size: x-small; font-weight: bold; background-color: #ABABAB; border: 1px solid black;">
                        Teléfono Fijo
                    </td>
                    <td colspan="3" style="font-size: x-small; border: 1px solid black;">
                        <p>{{ $afiliadoArray[0]['telefono_fijo'] ?? 'No disponible' }}</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="3"
                        style="font-size: x-small; font-weight: bold; background-color: #ABABAB; border: 1px solid black;">
                        Celular
                    </td>
                    <td colspan="3" style="font-size: x-small; border: 1px solid black;">
                        <p>{{ $afiliadoArray[0]['móvil'] ?? 'No disponible' }}</p>
                    </td>
                    <td colspan="3"
                        style="font-size: x-small; font-weight: bold; background-color: #ABABAB; border: 1px solid black;">
                        Correo Electrónico
                    </td>
                    <td colspan="3" style="font-size: x-small; border: 1px solid black;">
                        <p>{{ $afiliadoArray[0]['correo'] ?? 'No disponible' }}</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="6"
                        style="font-size: x-small; font-weight: bold; background-color: #ABABAB; border: 1px solid black;">
                        Firma
                    </td>
                    <td colspan="6" style="border: 1px solid black; text-align: center;">
                      @if (!empty($firmaArray[0]['imagen']))
                        <img src="{{ $firmaArray[0]['imagen'] }}" alt="Firma"
                            style="border: 2px solid black; height: 150px; width: 306px;">
                        @else
                        <p>Firma no disponible</p>
                        @endif
                    </td>
                </tr> 
            </tbody>
        </table> 


    </div>
</body>

</html>