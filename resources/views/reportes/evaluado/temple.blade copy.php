<!doctype html>
<html lang="es">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <title>Reporte Usaurios</title>
    <style>
        @page {
            margin: 50px 0;
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            /* Márgenes superior e inferior de 50px */
        }

        body {
            margin: 0 25px;
            /* Márgenes izquierdo y derecho de 50px */
            font-family: 'Arial, sans-serif';
            border: 2px solid red;
            /* Borde rojo */
            box-sizing: border-box;
            /* Incluye el borde en el tamaño del contenedor */
            padding: 0px;
            /* Relleno interior */
            font-family: "Times New Roman", Times, serif;
            font-size: 10pt;
        }

        h1 {
            color: #333;
        }

        /* table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 10px;
            text-align: left;
        } */

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
            font-size: 12px;

        }

        .content {
            margin-bottom: 60px;
            /* Añade espacio al final del contenido para el footer */
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>

storage\app\public\header\2.png

    <div class="container">
        <div class="header">
        
            <table class="header-table" style="margin-left: 6.3%; margin-right: 80%; width: 87.4%">
                <tr style="border: 2px solid red;">
                    <td style="border-right: 2px solid #000;">
                        <img src="{{ asset('storage/header/2.png') }}" alt="Imagen de header">

                    </td>
                </tr>
            </table>
        </div> 

        <table style="border-collapse: collapse; border: 1px solid black; width: 707px; height:106px; margin-top: 20px">
            <tr>
                <td rowspan="2" style="width: 20%; border: 1px solid black; padding: 10px; text-align: center;">
                    <img src="{{ 'storage/header/header.png' }}" alt="Logo" style="width:100%; height:auto;">
                </td>
                <td rowspan="2"
                    style="width: 20%; border: 1px solid black; padding: 10px; text-align: center; font-weight: bold;">
                    REVISIÓN DE SEGURIDAD<br>SEL COLOMBIA
                </td>
                <td style="width: 10%; border: 1px solid black; padding: 10px; text-align: center;">
                    Versión No. 02<br>FRF-001
                </td>
                <td style="width: 10%; border: 1px solid black; padding: 10px; text-align: center;">
                    Fecha de Edición:<br>Diciembre de 2023
                </td>
            </tr>
            <tr>
                <td style="width: 10%; border: 1px solid black; padding: 10px; text-align: center;">
                    Revisado
                </td>
                <td style="width: 10%; border: 1px solid black; padding: 10px; text-align: center;">
                    Página 1 de 4
                </td>
            </tr>
        </table>
        <table style=" border: 1px solid black; width: 707px; height:106px; margin-top: 20px">
            <tr>
                <th colspan="6" style="background-color: #ccc; text-align: left; padding: 8px; border: 1px solid #ddd;">
                    INFORMACIÓN DEL ASPIRANTE (O) CANDIDATO</th>
            </tr>
            <tr>
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">NOMBRES</th>
                <td colspan="5" style="padding: 8px; border: 1px solid #ddd;">JULIETH ALEXANDRA ACUÑA BERMUDEZ</td>
            </tr>
            <tr>
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">CC</th>
                <td style="padding: 8px; border: 1px solid #ddd;">52958750</td>
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">EXPEDIDA</th>
                <td colspan="3" style="padding: 8px; border: 1px solid #ddd;">02 JULIO 2002</td>
            </tr>
            <tr>
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">FECHA DE NACIMIENTO</th>
                <td colspan="5" style="padding: 8px; border: 1px solid #ddd;">
                    <table style="border-collapse: collapse;">
                        <tr>
                            <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">DÍA</th>
                            <td style="padding: 8px; border: 1px solid #ddd;">24</td>
                            <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">MES</th>
                            <td style="padding: 8px; border: 1px solid #ddd;">enero</td>
                            <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">AÑO</th>
                            <td style="padding: 8px; border: 1px solid #ddd;">1984</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">LUGAR</th>
                <td colspan="2" style="padding: 8px; border: 1px solid #ddd;">Bogotá D.C.</td>
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">EDAD</th>
                <td colspan="2" style="padding: 8px; border: 1px solid #ddd;">39 años</td>
            </tr>
            <tr>
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">DIRECCIÓN</th>
                <td colspan="2" style="padding: 8px; border: 1px solid #ddd;">Transversal 14J # 69-04 sur</td>
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">BARRIO</th>
                <td colspan="2" style="padding: 8px; border: 1px solid #ddd;">Aurora II</td>
            </tr>
            <tr>
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">TELÉFONO</th>
                <td colspan="2" style="padding: 8px; border: 1px solid #ddd;">
                    <table style="border-collapse: collapse;">
                        <tr>
                            <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">FJO</th>
                            <td style="padding: 8px; border: 1px solid #ddd;">7526934</td>
                            <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">MÓVIL</th>
                            <td style="padding: 8px; border: 1px solid #ddd;">3017330180</td>
                        </tr>
                    </table>
                </td>
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">TIEMPO PERMANENCIA</th>
                <td colspan="2" style="padding: 8px; border: 1px solid #ddd;">
                    <table style="border-collapse: collapse;">
                        <tr>
                            <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">DESDE</th>
                            <td style="padding: 8px; border: 1px solid #ddd;">No informa</td>
                            <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">HASTA</th>
                            <td style="padding: 8px; border: 1px solid #ddd;">actual</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        
        {{-- <img src="{{ 'storage/header/2.png' }}" alt="" style="width: 707px; height:106px; margin-top: 20px">
        --}} {{-- <table style="border: 1px solid black; width: 700px; margin-right: 1px">
            <tr>
                <td style="border: 1px solid black;  padding: 10px; width: 40%; text-align: left;">
                    <img src={{ $fotoArray['nombre'] }} alt="Logo"
                        style="border: 2px solid black; height: 140px; width: 160px;">
                </td>
                <td style="border: 1px solid black;  padding: 10px; width: 30%;"></td>
                <td style="border: 1px solid black; padding: 10px; width: 30%; text-align: left;">
                    <div class="row" style="border: 1px solid rgb(216, 219, 9); ">
                        <div class="col-12" style="border: 1px solid black;   width: 83%;">
                            {{ $autorizacionArray['nombres'] ?? 'No disponible' }}
                        </div>
                        <div class="col-12" style="border: 1px solid black;   width: 83%;">
                            {{ $autorizacionArray['cedula'] ?? 'No disponible' }}
                        </div>
                        <div class="col-12" style="border: 1px solid black;   width: 83%;">
                            {{ $evalauadoArray['edad'] ?? 'No disponible' }}
                        </div>
                        <div class="col-12" style="border: 1px solid black;   width: 83%;">
                            Fecha visita: {{$today}}
                        </div>
                    </div>
                </td>
            </tr>
        </table> --}}
        {{-- tabla de informacion personal --}}
        {{-- <table style="border: 1px solid black; width: 700px; margin-right: 1px">
            <thead>
                <tr>
                    <th colspan="12"
                        style=" width: 100%; border: 1px solid black; text-align: center; background-color: #ABABAB;">
                        INFORMACIÓN PERSONAL</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="3" style="border: 1px solid black; text-align: center; background-color: #ABABAB;">
                        Nombres Completo</td>
                    <td colspan="9" style="border: 1px solid black;">{{ $autorizacionArray['nombres'] ?? 'No disponible'
                        }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Tipo de Documento</td>
                    <td colspan="4" style="border: 1px solid black;">{{ $tipoDocumento ?? 'N/A' }}</td>
                    <td colspan="3" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">No.
                        Documento </td>
                    <td colspan="2" style="border: 1px solid black;">{{ $autorizacionArray['cedula'] ?? 'No disponible'
                        }}</td>

                </tr>
                <tr>
                    <td colspan="3" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Lugar de expedición</td>
                    <td colspan="4" style="border: 1px solid black;">{{ $lugarExpedicion ?? 'No disponible' }}</td>
                    <td colspan="3" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Edad </td>
                    <td colspan="2" style="border: 1px solid black;">{{ $evalauadoArray['edad'] ?? 'No disponible' }}
                    </td>

                </tr>
                <tr>
                    <td colspan="3" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Fecha de Nacimiento</td>
                    <td colspan="4" style="border: 1px solid black;"> {{ $evalauadoArray['fecha_nacimiento'] ?? 'No
                        disponible' }}</td>
                    <td colspan="3" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Lugar de Nacimiento</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $lugarNacimento ?? 'No disponible' }}</td>


                </tr>
                <tr>
                    <td colspan="3" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Grupo Sanguíneo </td>
                    <td colspan="1" style="border: 1px solid black;">{{ $rh ?? 'No disponible' }}</td>
                    <td colspan="2" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Estatura</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $estatura ?? 'No disponible' }}</td>
                    <td colspan="2" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Peso</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $evalauadoArray['peso_kg'] ?? 'No disponible' }}
                    </td>

                </tr>
                <tr>
                    <td colspan="3" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Estado Civil actual </td>
                    <td colspan="2" style="border: 1px solid black;">{{ $estadoCivil ?? 'No disponible' }}</td>
                    <td colspan="2" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Hace cuánto tiempo </td>
                    <td colspan="2" style="border: 1px solid black;">{{ $evalauadoArray['hacer_cuanto'] ?? 'No
                        disponible' }}</td>
                    <td colspan="2" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">N°
                        de Hijos </td>
                    <td colspan="1" style="border: 1px solid black;">{{ $evalauadoArray['numero_hijos'] ?? 'No
                        disponible' }}</td>

                </tr>
                <tr>
                    <td colspan="3" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Dirección de Residencia</td>
                    <td colspan="3" style="border: 1px solid black;">{{ $autorizacionArray['direccion'] ?? 'No
                        disponible' }}</td>
                    <td colspan="3" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Localidad </td>
                    <td colspan="3" style="border: 1px solid black;">{{ $autorizacionArray['localidad'] ?? 'No
                        disponible' }}</td>


                </tr>
                <tr>
                    <td colspan="3" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Barrio</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $autorizacionArray['barrio'] ?? 'No disponible'
                        }}</td>
                    <td colspan="2" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Ciudad </td>
                    <td colspan="2" style="border: 1px solid black;">{{ $lugarExpedicion ?? 'No disponible' }}</td>
                    <td colspan="2" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Estrato </td>
                    <td colspan="1" style="border: 1px solid black;">{{ $estrato ?? 'No disponible' }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Teléfono Fijo</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $autorizacionArray['telefono'] ?? 'No
                        disponible' }}</td>
                    <td colspan="3" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Celular </td>
                    <td colspan="4" style="border: 1px solid black;">{{ $autorizacionArray['celular'] ?? 'No disponible'
                        }} / {{ $evalauadoArray['celular_2'] ?? 'No disponible' }}
                    </td>

                </tr>
                <tr>
                    <td colspan="3" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">E.
                        Mail </td>
                    <td colspan="9" style="border: 1px solid black;">{{ $autorizacionArray['correo'] ?? 'No disponible'
                        }}</td>


                </tr>
                <tr>
                    <td colspan="3" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Cargo Actual</td>
                    <td colspan="9" style="border: 1px solid black;">{{ $evalauadoArray['cargo'] ?? 'No disponible' }}
                    </td>


                </tr>
                <tr>
                    <td colspan="3" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Observaciones</td>
                    <td colspan="9" style="border: 1px solid black;">{{ $evalauadoArray['observacion'] ?? 'No
                        disponible' }}</td>


                </tr>
            </tbody>
        </table> --}}
        {{-- fin tabla de informacion personal --}}
        {{-- tabla cmara comercio --}}
        {{-- <table class="customTable"
            style="border: 1px solid black; width: 700px; margin-right: 1px; margin-top: 15px;">
            <thead>
                <tr>
                    <th colspan="12"
                        style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; text-align: center;">
                        Camara de Comercio</th>

                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="6" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        ¿Tiene Camara de Comercio?</td>
                    <td colspan="6" style="border: 1px solid black;text-align: center; ">{{ $tieneCamara ?? 'No
                        disponible'
                        }}</td>

                </tr>
                <tr>
                    <td colspan="6" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Nombre de la Empresa</td>
                    <td colspan="6" style="border: 1px solid black; text-align: center;">{{ $camaraArray['nombre'] ??
                        'No disponible'}}
                    </td>
                </tr>
                <tr>
                    <td colspan="6" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Razon social</td>
                    <td colspan="6" style="border: 1px solid black; text-align: center;">{{ $camaraArray['razon'] ?? 'No
                        disponible'
                        }}</td>

                </tr>
                <tr>
                    <td colspan="6" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Actividad</td>
                    <td colspan="6" style="border: 1px solid black; text-align: center;">{{ $camaraArray['activdad'] ??
                        'No disponible'
                        }}
                    </td>

                </tr>
                <tr>
                    <td colspan="12"
                        style="text-align: center; font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        OBSERVACIONES</td>

                </tr>
                <tr>
                    <td colspan="12" style="text-align: justify; border: 1px solid black; text-align: center;">
                        <p>{{ $camaraArray['observacion'] ?? 'No disponible'
                            }}</p>
                    </td>

                </tr>
            </tbody>
        </table> --}}
        {{-- fin tabla cmara comercio --}}
        {{-- <div class="page-break"></div> --}}
        {{-- inicio estadod e salud --}}
        {{-- <table class="customTable"
            style="border: 1px solid black; width: 700px; margin-right: 1px; margin-top: 15px;">
            <thead>
                <tr>
                    <th colspan="6"
                        style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; text-align: center;">
                        Estado de salud del Aspirante</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="3" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Estado de salud</td>
                    <td colspan="3" style="border: 1px solid black; text-align: center;">{{ $estado_salud ?? 'No
                        disponible'
                        }}</td>

                </tr>
                <tr>
                    <td colspan="3" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        ¿Padece algún tipo de enfermedad?</td>
                    <td colspan="1" style="border: 1px solid black; text-align: center;">{{
                        $saluArray['id_tipo_enfermedad'] == 1 ? 'No' : 'Sí' }} </td>
                    <td colspan="2" style="border: 1px solid black; text-align: center;">{{
                        $saluArray['tipo_enfermedad_cual'] ?? 'No disponible'}}</td>

                </tr>
                <tr>
                    <td colspan="3" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        ¿Tiene alguna limitación física?</td>
                    <td colspan="1" style="border: 1px solid black; text-align: center;">{{
                        $saluArray['id_limitacion_fisica'] == 1 ? 'No' : 'Sí' }} </td>
                    <td colspan="2" style="border: 1px solid black; text-align: center;">{{
                        $saluArray['limitacion_fisica_cual'] ?? 'No disponible'}}</td>
                </tr>
                <tr>
                    <td colspan="3" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        ¿Toma algún tipo de medicamento ?</td>
                    <td colspan="1" style="border: 1px solid black; text-align: center;">{{
                        $saluArray['id_tipo_medicamento'] == 1 ? 'No' : 'Sí' }} </td>
                    <td colspan="2" style="border: 1px solid black; text-align: center;">{{
                        $saluArray['tipo_medicamento_cual'] ?? 'No disponible'}} </td>
                </tr>

                <tr>
                    <td colspan="3" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        ¿Ingiere alcohol?</td>
                    <td colspan="1" style="border: 1px solid black; text-align: center;">{{
                        $saluArray['id_ingiere_alcohol'] == 1 ? 'No' : 'Sí' }} </td>
                    <td colspan="2" style="border: 1px solid black; text-align: center;">{{
                        $saluArray['ingiere_alcohol_cual'] ?? 'No disponible'}} </td>
                </tr>
                <tr>
                    <td colspan="3" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        ¿Fuma?</td>
                    <td colspan="3" style="border: 1px solid black; text-align: center;">{{ $saluArray['id_fuma'] == 1 ?
                        'No' : 'Sí' }} </td>
                </tr>
                <tr>
                    <td colspan="3" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Observaciones</td>
                    <td colspan="3" style="border: 1px solid black; text-align: center;">{{ $saluArray['observacion'] ??
                        'No disponible'}}</td>
                </tr>
            </tbody>
        </table> --}}
        {{-- fin de estado salud --}}
        {{-- inicio compsoion familiar --}}
        {{-- <table class="table table-bordered"
            style="border: 1px solid black; width: 700px; margin-right: 1px; margin-top: 15px;">
            <thead>
                <tr>
                    <th colspan="6"
                        style="font-weight: bold; background-color: #ABABAB; border: 1px solid black; text-align: center;">
                        COMPOSICION FAMILIAR (con quién vive, y familia de origen)</th>
                </tr>
                <tr>
                    <th style="border: 1px solid black; text-align: center; font-weight: bold;">Nombre</th>
                    <th style="border: 1px solid black; text-align: center; font-weight: bold;">Parentesco</th>
                    <th style="border: 1px solid black; text-align: center; font-weight: bold;">Edad</th>
                    <th style="border: 1px solid black; text-align: center; font-weight: bold;">Ocupación</th>
                    <th style="border: 1px solid black; text-align: center; font-weight: bold;">Teléfono</th>
                    <th style="border: 1px solid black; text-align: center; font-weight: bold;">Conviven</th>


                </tr>
            </thead>
            <tbody>
                @foreach($familiarArray as $familiarArray)
                <tr>
                    <td style="border: 1px solid black; text-align: center;"> {{$familiarArray['nombre' ] ?? 'No
                        disponible'}}</td>
                    <td style="border: 1px solid black; text-align: center;"> {{$familiarArray['parentesco']['nombre']}}
                    </td>

                    <td style="border: 1px solid black; text-align: center;"> {{$familiarArray['edad'] ?? 'No
                        disponible'}}</td>
                    <td style="border: 1px solid black; text-align: center;"> {{$familiarArray['ocupacione']['nombre']
                        ?? 'No disponible'}}</td>
                    <td style="border: 1px solid black; text-align: center;"> {{$familiarArray['telefono'] ?? 'No
                        disponible'}}</td>
                    <td style="border: 1px solid black; text-align: center;"> {{$familiarArray['id_conviven'] == 1 ?
                        'No' : 'Sí'}}</td>

                </tr>
                <tr>
                    <td colspan="6" style="border: 1px solid black; text-align: justify;">
                        {{$familiarArray['observacion'] ?? 'No disponible'}}</td>
                </tr>
                @endforeach
            </tbody>
        </table> --}}
        {{-- fin composion familiar --}}
        {{-- informacion de pareja --}}
        {{-- <table class="table table-bordered"
            style="border: 1px solid black; width: 700px; margin-right: 1px; margin-top: 15px;">
            <thead>
                <tr>
                    <th colspan="7"
                        style="font-weight: bold; background-color: #ABABAB; border: 1px solid black; text-align: center;">
                        Información de la pareja (Conyuge, compañera sentimental)</th>
                </tr>
                <tr>

                    <th style="border: 1px solid black; text-align: center; font-weight: bold;">Cédula</th>
                    <th style="border: 1px solid black; text-align: center; font-weight: bold;">Tipo Documento</th>
                    <th style="border: 1px solid black; text-align: center; font-weight: bold;">Cédula Expedida</th>
                    <th style="border: 1px solid black; text-align: center; font-weight: bold;">Nombres</th>
                    <th style="border: 1px solid black; text-align: center; font-weight: bold;">Edad</th>
                    <th style="border: 1px solid black; text-align: center; font-weight: bold;">Género</th>
                    <th style="border: 1px solid black; text-align: center; font-weight: bold;">Nivel Académico</th>

                </tr>
            </thead>
            <tbody>
                <td style="border: 1px solid black; text-align: center;">{{ $parejaArray['cedula_pareja'] ?? 'No
                    disponible'}} </td>
                <td style="border: 1px solid black; text-align: center;">{{ $tipoDocumentoP ?? 'No disponible'}} </td>
                <td style="border: 1px solid black; text-align: center;">{{ $nombreMunipio ?? 'No disponible'}} </td>
                <td style="border: 1px solid black; text-align: center;">{{ $parejaArray['nombres'] ?? 'No disponible'}}
                </td>
                <td style="border: 1px solid black; text-align: center;">{{ $parejaArray['edad'] ?? 'No disponible'}}
                </td>
                <td style="border: 1px solid black; text-align: center;">{{ $genero ?? 'No disponible'}} </td>
                <td style="border: 1px solid black; text-align: center;">{{ $nivelAcademico ?? 'No disponible'}} </td>

            </tbody>
        </table> --}}
        {{-- fin de informacion pareja --}}
        {{-- vivienda --}}
        {{-- <table class="customTable"
            style="border: 1px solid black; width: 700px; margin-right: 1px; margin-top: 15px;">
            <thead>
                <tr>
                    <th colspan="12"
                        style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; text-align: center;">
                        TIPO DE VIVENDA</th>

                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Tipo de Vivienda</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $tipoVivienda ?? 'No disponible'}}
                    </td>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Sector</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $sector ?? 'No disponible'}} </td>

                </tr>
                <tr>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Propietario</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $propiedad ?? 'No disponible'}}</td>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Número de Familias que habitan la vivienda</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $viviendaArray['numero_de_familia'] }}</td>

                </tr>
                <tr>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Número de hogares habitan en la vivienda</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $viviendaArray['personas_nucleo_familiar'] ??
                        'No disponible'}}
                    </td>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Tiempo en años de Residencia en el Sector</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $viviendaArray['tiempo_sector'] ?? 'No
                        disponible'}} </td>

                </tr>

                <tr>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Número de Pisos de la Vivienda:</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $viviendaArray['numero_de_pisos'] ?? 'No
                        disponible'}}</td>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Estado de la vivienda:</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $viviendaArray['id'] ?? 'No disponible'}}</td>

                </tr>

            </tbody>
        </table> --}}
        {{-- fin de vivienda --}}
        {{-- incio de inventario --}}
        {{-- <table class="customTable"
            style="border: 1px solid black; width: 700px; margin-right: 1px; margin-top: 15px;">
            <thead>
                <tr>
                    <th colspan="12"
                        style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; text-align: center;">
                        INVENTARIO DE ENSERES</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Televisor</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $inventarioArray['televisor'] == 1 ? 'No' :
                        'Sí'}}</td>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        D.V.D</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $inventarioArray['dvd'] == 1 ? 'No' : 'Sí'}}
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Teatro en Casa</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $inventarioArray['teatro_en_casa'] == 1 ? 'No' :
                        'Sí'}}</td>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Equipo de Sonido</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $inventarioArray['equipo_de_sonido'] == 1 ? 'No'
                        : 'Sí'}}</td>
                </tr>
                <tr>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Computador</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $inventarioArray['computador'] == 1 ? 'No' :
                        'Sí'}}</td>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Impresora</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $inventarioArray['impresora'] == 1 ? 'No' :
                        'Sí'}}</td>
                </tr>
                <tr>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Movil</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $inventarioArray['movil'] == 1 ? 'No' : 'Sí'}}
                    </td>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Estufa</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $inventarioArray['estufa'] == 1 ? 'No' : 'Sí'}}
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Nevera</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $inventarioArray['nevera'] == 1 ? 'No' : 'Sí'}}
                    </td>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Lavadora</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $inventarioArray['lavadora'] == 1 ? 'No' :
                        'Sí'}}</td>
                </tr>
                <tr>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Microondas</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $inventarioArray['microondas'] == 1 ? 'No' :
                        'Sí'}}</td>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Moto</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $inventarioArray['moto'] == 1 ? 'No' : 'Sí'}}
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Carro</td>
                    <td colspan="8" style="border: 1px solid black;">{{ $inventarioArray['carro'] == 1 ? 'No' : 'Sí'}}
                    </td>
                </tr>
                <tr>
                    <td colspan="12"
                        style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; text-align: center  ">
                        observaciones</td>
                </tr>
                <tr>
                    <td colspan="12" style="border: 1px solid black; text-align: justify">{{
                        $inventarioArray['observaciones'] ?? 'No disponible'}}</td>
                </tr>

            </tbody>
        </table> --}}
        {{-- fin de invetario --}}
        {{-- inicio servicos publico --}}
        {{-- <table class="customTable"
            style="border: 1px solid black; width: 700px; margin-right: 1px; margin-top: 15px;">
            <thead>
                <tr>
                    <th colspan="12"
                        style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; text-align: center;">
                        SERVICIOS PÚBLICOS Y OTROS</th>

                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Agua</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $serviciosArray['agua'] == 1 ? 'No' : 'Sí'}}
                    </td>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Luz</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $serviciosArray['luz'] == 1 ? 'No' : 'Sí'}}
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Gas</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $serviciosArray['gas'] == 1 ? 'No' : 'Sí'}}</td>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Alcantarillado</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $serviciosArray['alcantarillado'] == 1 ? 'No' :
                        'Sí'}}</td>
                </tr>
                <tr>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Internet</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $serviciosArray['internet'] == 1 ? 'No' : 'Sí'}}
                    </td>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Administración</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $serviciosArray['administracion'] == 1 ? 'No' :
                        'Sí'}}</td>
                </tr>
                <tr>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Parqueadero</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $serviciosArray['parqueadero'] == 1 ? 'No' :
                        'Sí'}}</td>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Telefono</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $serviciosArray['telefono'] == 1 ? 'No' : 'Sí'}}
                    </td>

                </tr>
                <tr>
                    <td colspan="12"
                        style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; text-align: center  ">
                        observaciones</td>
                </tr>
                <tr>
                    <td colspan="12" style="border: 1px solid black; text-align: justify">
                        {{$serviciosArray['observacion'] ?? 'No disponible'}}</td>
                </tr>
            </tbody>
        </table> --}}
        {{-- fin servicos publicos --}}
        {{-- inicio patrimonio --}}
        {{-- <table class="customTable"
            style="border: 1px solid black; width: 700px; margin-right: 1px; margin-top: 15px;">
            <thead>
                <tr>
                    <th colspan=" 12"
                        style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; text-align: center;">
                        PATRIMONIO
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Valor Vivienda</td>
                    <td colspan="2" style="border: 1px solid black;">{{$patrimonioArray['valor_vivienda'] ?? 'No
                        disponible'}}</td>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Dirección</td>
                    <td colspan="2" style="border: 1px solid black;">{{$patrimonioArray['direccion'] ?? 'No
                        disponible'}}</td>
                </tr>
                <tr>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Vehiculo</td>
                    <td colspan="2" style="border: 1px solid black;">{{$patrimonioArray['id_vehiculo'] ?? 'No
                        disponible'}}</td>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Marca</td>
                    <td colspan="2" style="border: 1px solid black;">{{$patrimonioArray['id_marca'] ?? 'No disponible'}}
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Modelo</td>
                    <td colspan="2" style="border: 1px solid black;">{{$patrimonioArray['id_modelo'] ?? 'No
                        disponible'}}</td>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Ahorro (CDT, Inversiones)</td>
                    <td colspan="2" style="border: 1px solid black;">{{$patrimonioArray['ahorro'] ?? 'No disponible'}}
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black;  ">
                        Otros</td>
                    <td colspan="8" style="border: 1px solid black;">{{$patrimonioArray['otros'] ?? 'No disponible'}}
                    </td>
                </tr>
                <tr>
                    <td colspan="12"
                        style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; text-align: center  ">
                        observaciones</td>
                </tr>
                <tr>
                    <td colspan="12" style="border: 1px solid black; text-align: justify">
                        {{$patrimonioArray['observacion'] ?? 'No disponible'}}</td>
                </tr>
            </tbody>
        </table>' --}}
        {{-- fin patrimonio --}}
        {{-- <table class="customTable" style="margin-left: 1.3%; margin-right: 76%; width: 680px">
            <thead>
                <tr>
                    <th colspan="12"
                        style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; text-align: center;">
                        AUTORIZACIÓN </th>

                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="12"
                        style="border: 1px solid black; text-align: justify; margin-left: 50px; margin-right: 50px; font-size: x-small;">
                        <div class="carta">
                            <p>Yo {{ $autorizacionArray['nombres'] ?? 'No disponible' }}</p>
                            <p>Identificado (a) con cédula de ciudadanía No. {{ $autorizacionArray['cedula'] ?? 'No
                                disponible' }}</p>
                            <p>Expedida en:{{ $municipioArray['nombre'] ?? 'No disponible' }} el dia {{
                                $autorizacionArray['fecha'] ?? 'No disponible' }}</p>
                            <p>Hago constar de manera libre y voluntaria que la información procesada en el presente
                                estudio, obedece a la verdad y <strong>AUTORIZO</strong> plenamente a la empresa
                                <strong>GRUPO DE TAREAS EMPRESARIALES</strong> con NIT <strong>830.142.258-3</strong>
                                para
                                realizar VERIFICACIÓN ACADÉMICA, VERIFICACIÓN JUDICIAL, CENTRAL DE RIESGOS LEY 1266 y
                                LEY
                                1581 del 2012 habeas data. Para tomar las pruebas necesarias y suficientes, a fin de
                                establecer la veracidad de la información suministrada, para que en el momento que se
                                haga
                                necesaria se utilice como prueba. Contemplando en el <strong>DECRETO 1266 DE
                                    2008</strong>.
                            </p>

                        </div>

                    </td>
                </tr>
                <tr>
                    <td colspan="3"
                        style="font-size: x-small;font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Direccion</td>
                    <td colspan="3" style="font-size: x-small;border: 1px solid black;">
                        <p>{{ $autorizacionArray['direccion'] ?? 'No disponible' }}</p>
                    </td>
                    <td colspan="3"
                        style="font-size: x-small;font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Barrio
                    </td>
                    <td colspan="3" style="font-size: x-small;border: 1px solid black;">
                        <p>{{ $autorizacionArray['barrio'] ?? 'No disponible' }}</p>
                    </td>

                </tr>
                <tr>
                    <td colspan="3"
                        style="font-size: x-small;font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Direccion</td>
                    <td colspan="3" style="font-size: x-small;border: 1px solid black;">
                        <p>{{ $autorizacionArray['direccion'] ?? 'No disponible' }}</p>
                    </td>
                    <td colspan="3"
                        style="font-size: x-small;font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Barrio
                    </td>
                    <td colspan="3" style="font-size: x-small;border: 1px solid black;">
                        <p>{{ $autorizacionArray['barrio'] ?? 'No disponible' }}</p>
                    </td>

                </tr>
                <tr>
                    <td colspan="3"
                        style="font-size: x-small;font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Localidad:</td>
                    <td colspan="3" style="font-size: x-small;border: 1px solid black;">
                        <p>{{ $autorizacionArray['localidad'] ?? 'No disponible' }}</p>
                    </td>
                    <td colspan="3"
                        style="font-size: x-small;font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Teléfono Fijo: </td>
                    <td colspan="3" style="font-size: x-small;border: 1px solid black;">
                        <p>{{ $autorizacionArray['telefono'] ?? 'No disponible' }}</p>
                    </td>

                </tr>
                <tr>
                    <td colspan="3"
                        style="font-size: x-small;font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Celular:</td>
                    <td colspan="3" style="font-size: x-small;border: 1px solid black;">
                        <p>{{ $autorizacionArray['celular'] ?? 'No disponible' }}</p>
                    </td>
                    <td colspan="3"
                        style="font-size: x-small;font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Correo
                        electronico </td>
                    <td colspan="3" style="font-size: x-small;border: 1px solid black;">
                        <p>{{ $autorizacionArray['correo'] ?? 'No disponible' }}</p>
                    </td>

                </tr>
                <tr>
                    <td colspan="12" style="border: 1px solid black; text-align: center;">
                        <img src="{{ $firmaArray['nombre'] }}" alt="Logo"
                            style="border: 2px solid black; height: 150px; width: 506px;">

                    </td>
                </tr>
            </tbody>
        </table>
        <div class="page-break"></div>
        <table class="customTable" style="margin-left: 1.3%; margin-right: 76%; width: 680px">
            <thead>
                <tr>
                    <th colspan="12"
                        style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; text-align: center;">
                        UBICACIÓN EN TIEMPO REAL</th>
                </tr>
            </thead>
            <tbody>
                <tr>

                    <td style="border: 1px solid black; text-align: center; 165px; width: 265px;">

                        <img src={{ $fotoArray['nombre'] }} alt="Logo"
                            style="border: 2px solid black; height: 140px; width: 160px;">
                    </td>
                    <td style="border: 1px solid black; text-align: center; 165px; width: 265px;">
                        <img src="' . $foto1 . '" alt="Logo"
                            style="border: 2px solid black; height: 145px; width: 166px;">

                    </td>

                </tr>

            </tbody>
        </table> --}}



        <div class="footer">
            Página {PAGE_NUM} de {PAGE_COUNT}
        </div>



    </div>
    <div class="footer">
        Página {PAGE_NUM} de {PAGE_COUNT}
    </div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
    </script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
    -->
</body>

</html>