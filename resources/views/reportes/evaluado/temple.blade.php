<!DOCTYPE html>
<html lang="es-CO">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil del Afiliado</title>
    <!-- Vinculación con Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Estilos personalizados -->
    <style>
        body {
            background-color: #ffffff;
            font-family: 'Arial', sans-serif;
        }

        .container {
            margin-top: 0px;
            margin-bottom: 0px;
            margin-left: 0px;
            margin-right: 0px;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
            width: 700px;
        }

        .card-header {
            background-color: #007bff;
            color: white;
            font-size: 1.25rem;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        .card-body {
            background-color: white;
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
        }

        .row {
            margin-bottom: 15px;
        }

        .row .col-md-6 {
            padding: 10px;
        }

        .row .col-md-6 p {
            margin: 0;
            font-size: 0.9rem;
        }

        .row .col-md-6 p strong {
            font-weight: bold;
        }

        .btn-custom {
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            padding: 10px 20px;
            text-decoration: none;
        }

        .btn-custom:hover {
            background-color: #218838;
        }
        .p{
            border-bottom: 1px solid #ccc;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-primary text-white text-center">
                <h4>Datos basicos del Afiliado</h4>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tbody>
                        <tr style="border-bottom: 1px solid #ccc;">
                            <td><strong>Nombre Completo:</strong></td>
                            <td>{{ $registrosArray['nombre_afiliado'] }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                            <td><strong>Cédula:</strong></td>
                            <td>{{ $registrosArray['cedula_numero'] }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                            <td><strong>Caso:</strong></td>
                            <td>{{ $registrosArray['caso'] }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                            <td><strong>Fecha:</strong></td>
                            <td>{{ $registrosArray['fecha'] }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                            <td><strong>Estado Civil:</strong></td>
                            <td>{{ $registrosArray['estado_civil'] }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                            <td><strong>Amparo:</strong></td>
                            <td>{{ $registrosArray['amparo'] }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                            <td><strong>Tipo de Convivencia:</strong></td>
                            <td>{{ $registrosArray['tipo_de_convivencia']['nombre'] ?? 'N/A' }} </td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                            <td><strong>Otro:</strong></td>
                            <td>{{ $registrosArray['otro'] ?? 'N/A' }}</td>
                        </tr>
                    </tbody>
                </table>
                
            </div>
            
        </div>
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h4>Datos Complementarios del Afiliado</h4>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tbody>
                     
                        <tr style="border-bottom: 1px solid #ccc;">
                            <td><strong>Edad:</strong></td>
                            <td>{{ $afiliadoArray['edad'] }} Años</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                            <td><strong>Fecha de Nacimiento:</strong></td>
                            <td>{{ \Carbon\Carbon::parse($afiliadoArray['fecha_nacimiento'])->format('d/m/Y') }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                            <td><strong>Departamento:</strong></td>
                            <td>{{  $departamentoArray['nombre']  }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                            <td><strong>Municipio:</strong></td>
                            <td>{{ $municipioArray['nombre'] }}</td>
                        </tr>
                        <tr>
                            <td><strong>Fecha de Expedición:</strong></td>
                            <td>{{ \Carbon\Carbon::parse($afiliadoArray['fecha_expedicion'])->format('d/m/Y') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h4>Datos Del siniestro del Afiliado</h4>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr style="border-bottom: 1px solid #ccc;">
                            <td><strong>Cédula del Afiliado:</strong></td>
                            <td>{{ $siniestroArray['cedula_numero'] }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                            <td><strong>Fecha del Siniestro:</strong></td>
                            <td>{{ \Carbon\Carbon::parse($siniestroArray['fecha_siniestro'])->format('d/m/Y') }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                            <td><strong>Lugar del Siniestro:</strong></td>
                            <td>{{ $siniestroArray['lugar'] }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                            <td><strong>Departamento:</strong></td>
                            <td>{{  $departamentoArray_siniestro['nombre']  }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                            <td><strong>Municipio:</strong></td>
                            <td>{{ $municipioArray_siniestro['nombre'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card mb-4">
            <<div class="card-header bg-primary text-white text-center">
                <h4>Datos convivencia del Afiliado</h4>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tbody>
                        <tr style="border-bottom: 1px solid #ccc;">
                            <td><strong>Cédula Número:</strong></td>
                            <td>{{ $convivenciaArray['cedula_numero'] }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                            <td><strong>Estado Civil al Siniestro:</strong></td>
                            <td>{{ $convivenciaArray['estado_civil_al_siniestro'] }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                            <td><strong>Desde Estado Civil:</strong></td>
                            <td>{{ $convivenciaArray['desde_estado_civil'] }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                            <td><strong>Hasta Estado Civil:</strong></td>
                            <td>{{ $convivenciaArray['hasta_estado_civil'] }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                            <td><strong>Relación Con:</strong></td>
                            <td>{{ $convivenciaArray['relacion_con'] }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                            <td><strong>Quién Convivía:</strong></td>
                            <td>{{ $convivenciaArray['quien_convivía'] }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                            <td><strong>Tiempo de Convivencia:</strong></td>
                            <td>{{ $convivenciaArray['tiempo_convivencia'] }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                            <td><strong>Desde Convivencia:</strong></td>
                            <td>{{ $convivenciaArray['desde_convivencia'] }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                            <td><strong>Hasta Convivencia:</strong></td>
                            <td>{{ $convivenciaArray['hasta_convivencia'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        
    </div>
    <div class="container">
      
        <!-- Datos Personales -->
    {{--     <div class="card">
            <div class="card-header">
                Datos Personales
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nombre Completo:</strong> GABRIEL ANTONIO MEDINA TRIANA</p>
                        <p><strong>Cédula:</strong> 80933188</p>
                        <p><strong>Edad:</strong> 30 Años</p>
                        <p><strong>Fecha de Nacimiento:</strong> 22/12/1985</p>
                        <p><strong>Lugar de Nacimiento:</strong> Bogotá D.C</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Fecha de Expedición:</strong> 10/12/199</p>
                        <p><strong>Fecha del Siniestro:</strong> 22/12/2015</p>
                        <p><strong>Lugar del Siniestro:</strong> Hospital el Guavio, Bogotá D.C</p>
                        <p><strong>Estado Civil al Siniestro:</strong> Soltero</p>
                        <p><strong>Hijos:</strong> 1 (11 Años)</p>
                    </div>
                </div>

            </div>
        </div> --}}

<!-- Datos Personales -->
        <div class="card">
            <div class="card-header">
                Datos Personales {{$registrosArray['nombre_afiliado']}}
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tbody>
                        <tr style=" border-bottom: 1px solid #ccc;">
                            <td><strong>Nombre Completo:</strong></td>
                            <td>GABRIEL ANTONIO MEDINA TRIANA</td>
                        </tr>
                        <tr style=" border-bottom: 1px solid #ccc;">
                            <td><strong>Cédula:</strong></td>
                            <td>80933188</td>
                        </tr>
                        <tr style=" border-bottom: 1px solid #ccc;">
                            <td><strong>Edad:</strong></td>
                            <td>30 Años</td>
                        </tr>
                        <tr style=" border-bottom: 1px solid #ccc;">
                            <td><strong>Fecha de Nacimiento:</strong></td>
                            <td>22/12/1985</td>
                        </tr>
                        <tr style=" border-bottom: 1px solid #ccc;">
                            <td><strong>Lugar de Nacimiento:</strong></td>
                            <td>Bogotá D.C</td>
                        </tr>
                        <tr style=" border-bottom: 1px solid #ccc;">
                            <td><strong>Fecha de Expedición:</strong></td>
                            <td>10/12/199</td>
                        </tr>
                        <tr style=" border-bottom: 1px solid #ccc;">
                            <td><strong>Fecha del Siniestro:</strong></td>
                            <td>22/12/2015</td>
                        </tr>
                        <tr style=" border-bottom: 1px solid #ccc;">
                            <td><strong>Lugar del Siniestro:</strong></td>
                            <td>Hospital el Guavio, Bogotá D.C</td>
                        </tr>
                        <tr style=" border-bottom: 1px solid #ccc;">
                            <td><strong>Estado Civil al Siniestro:</strong></td>
                            <td>Soltero</td>
                        </tr>
                        <tr style=" border-bottom: 1px solid #ccc;">
                            <td><strong>Hijos:</strong></td>
                            <td>1 (11 Años)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        

        <!-- Información Laboral -->
        <div class="card">
            <div class="card-header">
                Información Laboral
            </div>
            <div class="card-body">
                <p style=" border-bottom: 1px solid #ccc;"><strong>Empresa:</strong> COLEGIO ANGLO COLOMBIANO</p>
                <p style=" border-bottom: 1px solid #ccc;"><strong>Cargo:</strong> Auxiliar de Mantenimiento</p>
                <p style=" border-bottom: 1px solid #ccc;"><strong>Tiempo en el Cargo:</strong> 1 año y 6 meses</p>
                <p style=" border-bottom: 1px solid #ccc;"><strong>Salario:</strong> $777.000</p>
                <p style=" border-bottom: 1px solid #ccc;"><strong>Teléfono:</strong> 2595700 ext. 1009</p>
            </div>
        </div>

        

        <!-- Cobertura y Afiliación -->
        <div class="card">
            <div class="card-header">
                Cobertura y Afiliación
            </div>
            <div class="card-body">
                <p style=" border-bottom: 1px solid #ccc;"><strong>Cobertura de Salud:</strong> CAFESALUD</p>
                <p style=" border-bottom: 1px solid #ccc;"><strong>Tipo de Afiliación:</strong> Cotizante</p>
                <p style=" border-bottom: 1px solid #ccc;"><strong>Régimen:</strong> Contributivo</p>
                <p style=" border-bottom: 1px solid #ccc;"><strong>Fecha de Afiliación:</strong> 02/07/2011</p>
                <p style=" border-bottom: 1px solid #ccc;"><strong>Beneficiarios Registrados:</strong> No</p>
            </div>
        </div>

        <!-- Observaciones -->
        <div class="card">
            <div class="card-header">
                Observaciones
            </div>
            <div class="card-body">
                <p>(Diligenciar)</p>
            </div>
        </div>

        <!-- Reclamación de Prestaciones Sociales -->
        <div class="card">
            <div class="card-header">
                Reclamación de Prestaciones Sociales
            </div>
            <div class="card-body">
                <p><strong>¿Quién reclama las prestaciones sociales?</strong></p>
                <p>Hallazgos: Presenta sentencia</p>
                <hr>
                <h5>Sentencia</h5>
                <ol>
                    <li>Privar de los derechos de la patria potestad a la señora <strong>CARMEN ROSA HERNADEZ
                            GIRALDO</strong>, sobre su menor hijo <strong>BRAYAN ANDREY MEDINA HERNANDEZ</strong>.</li>
                    <li>Se designa a la señora <strong>SILVIA CONSUELO TRIANA</strong> identificada con CC 51.655.665 de
                        Bogotá, como guardadora del niño <strong>BRAYAN ANDREY MEDINA HERNANDEZ</strong>. A quien se le
                        notifica en estrados y, si acepta, tómesele posesión del cargo y se le exonera de prestar
                        caución.</li>
                </ol>
            </div>
        </div>

        <!-- Hechos de la Ocurrencia del Fallecimiento -->
        <div class="card mt-4">
            <div class="card-header">
                Hechos de la Ocurrencia del Fallecimiento
            </div>
            <div class="card-body">
                <p style=" border-bottom: 1px solid #ccc;"><strong>Fecha y Hora:</strong> 22/12/2015, hora no establecida</p>
                <p style=" border-bottom: 1px solid #ccc;"><strong>Lugar:</strong> Hospital el Guavio, Bogotá D.C</p>
                <p style=" border-bottom: 1px solid #ccc;"><strong>Causa del Fallecimiento:</strong> Homicidio</p>
                <p style=" border-bottom: 1px solid #ccc;"><strong>Detalles Adicionales:</strong> Shock Hipovolémico debido a lesiones ocasionadas por proyectil
                    de arma de fuego</p>
            </div>
        </div>

        <!-- Conclusiones y Observaciones -->
        <div class="card mt-4">
            <div class="card-header">
                Conclusiones y Observaciones
            </div>
            <div class="card-body">
                <p style=" border-bottom: 1px solid #ccc;"><strong>Documentos Aportados:</strong> [Indicar si los documentos son auténticos o no]</p>
                <pstyle=" border-bottom: 1px solid #ccc;"><strong>Nexos de Parentesco:</strong> [Indicar si existen nexos de parentesco entre el afiliado y la
                    reclamante]</pstyle=>
                <pstyle=" border-bottom: 1px solid #ccc;"><strong>Informe:</strong> Se deja rendido el presente informe para su conocimiento, análisis y fines
                    pertinentes, teniendo en cuenta que la muerte tiene origen común: [Indicar si es común o
                    profesional] según la circunstancia del fallecimiento.</pstyle=>
                <pstyle=" border-bottom: 1px solid #ccc;"><strong>Estado Civil del Afiliado al Momento del Siniestro:</strong> [Indicar estado civil: Soltero,
                    Casado, Unión Libre, Otros]</pstyle=>
                <pstyle=" border-bottom: 1px solid #ccc;"><strong>Núcleo Familiar del Afiliado:</strong> Reclamante: [Nombre del reclamante]; Tutor Madre:
                    [Nombre de la madre]; Hijo: [Nombre del hijo].</pstyle=>
                <pstyle=" border-bottom: 1px solid #ccc;"><strong>Descendientes del Afiliado:</strong> [Indicar si el afiliado deja descendientes: Sí/No].
                    Descendientes de la relación: [Indicar si hay descendientes de la relación: Sí/No]. Descendiente
                    presente en la reclamación: [Nombre del descendiente], de [edad] años, quien no presenta condición
                    de discapacidad.</pstyle=>
                <pstyle=" border-bottom: 1px solid #ccc;"><strong>Observaciones:</strong> [Incluir observaciones pertinentes]</pstyle=>
            </div>
        </div>

        <!-- Núcleo Familiar al Momento del Siniestro -->
       

        <div class="card mt-4">
            <div class="card-header">
                Núcleo Familiar al Momento del Siniestro
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Columna 1 -->
                    <div class="col-md-6">
                        <h5 class="card-title">Reclamante</h5>
                        <p><strong>Nombre:</strong> Señora Silvia Consuelo Triana</p>
                        <p><strong>Cédula de Ciudadanía:</strong> 51655665 de Bogotá</p>
                        <p><strong>Fecha de Nacimiento:</strong> 24/08/1959</p>
                        <p><strong>Edad al Momento del Siniestro:</strong> 56 años</p>
                        <p><strong>Estado Civil:</strong> Soltera</p>
                        <p><strong>Tiempo de Convivencia con el Afiliado:</strong> 10 años</p>
                        <p><strong>Dirección al Momento del Siniestro:</strong> Calle 1 bis # 11 D Este, Bogotá</p>
                        <p><strong>Dirección Actual:</strong> Calle 1 bis # 11 D Este, Barrio El Consuelo, Bogotá</p>
                        <p><strong>Vivienda:</strong> Propia</p>
                        <p><strong>Teléfono Móvil:</strong> 3172133050</p>
                        <p><strong>Situación Laboral al Momento del Siniestro:</strong> Activa</p>
                        <p><strong>Ocupación:</strong> Oficios Varios en Empresa Independiente</p>
                        <p><strong>Salario:</strong> $322,175</p>
                        <p><strong>Tiempo de Trabajo:</strong> 2 años</p>
                        <p><strong>Vinculación a Seguridad Social:</strong> EPS Capital Salud, régimen Subsidiado desde 01/01/2016 hasta 04/10/2017</p>
                        <p><strong>Estado de Afiliación:</strong> Activo</p>
                        <p><strong>Beneficiarios en EPS:</strong> [Indicar beneficiarios]</p>
                    </div>
                    <!-- Columna 2 -->
                    <div class="col-md-6">
                        <h5 class="card-title">Hijos</h5>
                        <p><strong>Nombre:</strong> Brayan Andrey Medina Hernández</p>
                        <p><strong>Tipo de Documento:</strong> Tarjeta de Identidad</p>
                        <p><strong>Número de Documento:</strong> 1013101835 de Bogotá</p>
                        <p><strong>Fecha de Nacimiento:</strong> 12/09/2004</p>
                        <p><strong>Edad al Momento del Siniestro:</strong> 11 años</p>
                        <p><strong>Edad Actual:</strong> 13 años</p>
                        <p><strong>Estado Civil:</strong> Soltero</p>
                        <p><strong>Ocupación:</strong> Estudiante</p>
                        <p><strong>Grado Escolar Actual:</strong> 7° grado</p>
                        <p><strong>Institución Educativa:</strong> Colegio Aulas Colombianas San Luis, Bogotá</p>
                        <p><strong>Vinculación a Seguridad Social:</strong> EPS Capital Salud, régimen Subsidiado desde 13/05/2016</p>
                        <p><strong>Estado de Afiliación:</strong> Activo</p>
                        <p><strong>Observaciones:</strong> No presenta condición de discapacidad</p>
                    </div>
                </div>
            </div>
        </div>
        

        <!-- Versión de la Información Familiar -->
        

        <div class="container mt-4">
            <div class="card">
                <div class="card-header">
                    Versiones
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Columna 1 -->
                        <div class="col-md-6">
                            <h5 class="card-title">Reclamante</h5>
                            <p><strong>Nombre:</strong> Silvia Consuelo Triana</p>
                            <p><strong>Estado Civil:</strong> [Diligenciar]</p>
                            <p><strong>Fecha de Convivencia con el Afiliado:</strong> Desde [Diligenciar] hasta [Diligenciar]</p>
                            <p><strong>¿Quedaron descendientes?:</strong> Sí</p>
                            <p><strong>Nombre del Descendiente:</strong> Brayan Andrey Medina Hernández</p>
                            <p><strong>Edad del Descendiente:</strong> [Diligenciar]</p>
                            <p><strong>Dependencia Económica:</strong> Brayan dependía económicamente del hijo Gabriel.</p>
                            <p><strong>Patria Potestad:</strong> Fue otorgada el 25 de mayo de 2017 ante el Juzgado Primero de Familia en la Ciudad de Bogotá.</p>
                            <p><strong>Confirmación de Reclamos:</strong> No existen otras personas que puedan reclamar en calidad de excompañeras, exparejas, o ex cónyuges, ni otros hijos pendientes por reconocer o con derecho a reclamar.</p>
                        </div>
                        <!-- Columna 2 -->
                        <div class="col-md-6">
                            <h5 class="card-title">Primo del Afiliado - Andrés Alfonso Medina</h5>
                            <p><strong>Cédula:</strong> 1010169391</p>
                            <p><strong>Teléfono:</strong> 3004495335</p>
                            <p><strong>Información al Momento del Siniestro:</strong> El afiliado se encontraba soltero, y su núcleo familiar estaba conformado por la reclamante, su madre y tutor, Silvia Consuelo Triana, y su único descendiente, Brayan Andrey Medina Hernández (11 años, sin discapacidad).</p>
                            <p><strong>Confirmación de Reclamos:</strong> No existen otros hijos biológicos o personas con derecho a reclamar, ni excompañeras ni ex cónyuges.</p>
        
                            <h5 class="card-title mt-4">Hermana del Afiliado - Sandra Lisbel Medina</h5>
                            <p><strong>Cédula:</strong> 53.050.212</p>
                            <p><strong>Teléfono:</strong> 3166976816</p>
                            <p><strong>Información al Momento del Siniestro:</strong> El afiliado estaba soltero y convivía con la reclamante, Silvia Consuelo Triana, y su único descendiente, Brayan Andrey Medina Hernández. No existen otros hijos biológicos, naturales, reconocidos, ni excompañeras o ex cónyuges con derecho a reclamar.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

        <!-- Verificaciones -->
      

        <div class="container mt-4">
            <div class="card">
                <div class="card-header">
                    Verificaciones
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Columna 1 -->
                        <div class="col-md-6">
                            <!-- Cédula del Afiliado -->
                            <h5 class="card-title">Cédula del Afiliado</h5>
                            <p><strong>Cédula de Ciudadanía:</strong> 80.933.188</p>
                            <p><strong>Fecha de Expedición:</strong> 17 DE FEBRERO DE 2004</p>
                            <p><strong>Lugar de Expedición:</strong> BOGOTÁ D.C. - CUNDINAMARCA</p>
                            <p><strong>A nombre de:</strong> GABRIEL ANTONIO MEDINA TRIANA</p>
                            <p><strong>Estado:</strong> CANCELADA POR MUERTE</p>
                            <p><strong>Resolución:</strong> 29</p>
                            <p><strong>Fecha Resolución:</strong> 05/01/2016</p>
                            <p><strong>Serial R.C.D:</strong> 0008863975</p>
                        </div>
                        <!-- Columna 2 -->
                        <div class="col-md-6">
                            <!-- Registro Civil de Nacimiento Afiliado -->
                            <h5 class="card-title">Registro Civil de Nacimiento Afiliado</h5>
                            <p><strong>Registraduría Nacional del Estado Civil:</strong> Certifica que el nacimiento de Gabriel Antonio Medina Triana está registrado en la Notaría 8 de Bogotá D.C., con el serial 0010291745.</p>
                            <p><strong>Fecha de Nacimiento:</strong> 07 DE ENERO DE 1986</p>
                            <p><strong>Número de Identificación Personal:</strong> 85122234024</p>
        
                            <!-- Registro de Defunción Afiliado -->
                            <h5 class="card-title mt-4">Registro de Defunción Afiliado</h5>
                            <p><strong>Registraduría del Estado Civil:</strong> Certifica que el registro civil de defunción de Gabriel Antonio Medina Triana está registrado bajo el serial 08863975.</p>
                            <p><strong>Fecha de Defunción:</strong> 22 DE DICIEMBRE DE 2015</p>
                            <p><strong>Certificado de Defunción:</strong> 815177295</p>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <!-- Columna 1 -->
                        <div class="col-md-6">
                            <!-- Cédula del Reclamante -->
                            <h5 class="card-title">Cédula del Reclamante (Tutor)</h5>
                            <p><strong>Cédula de Ciudadanía:</strong> 51.655.665</p>
                            <p><strong>Fecha de Expedición:</strong> 20 DE NOVIEMBRE DE 1980</p>
                            <p><strong>Lugar de Expedición:</strong> BOGOTÁ D.C. - CUNDINAMARCA</p>
                            <p><strong>A nombre de:</strong> SILVIA CONSUELO TRIANA</p>
                            <p><strong>Estado:</strong> VIGENTE</p>
                        </div>
                        <!-- Columna 2 -->
                        <div class="col-md-6">
                            <!-- Registro Civil de Nacimiento del Descendiente -->
                            <h5 class="card-title">Registro Civil de Nacimiento del Descendiente (Hijo)</h5>
                            <p><strong>Registraduría Nacional del Estado Civil:</strong> Certifica que el nacimiento de Brayan Andrey Medina Hernández está registrado en la Notaría 53 de Bogotá D.C., con el serial 0037417768.</p>
                            <p><strong>Fecha de Nacimiento:</strong> 15 DE OCTUBRE DE 2004</p>
                            <p><strong>Número Único de Identificación Personal:</strong> 1013101835</p>
                            <p><strong>Nota Marginal:</strong> Mediante sentencia de fecha mayo 25/2017, el Juzgado Primero de Familia de Bogotá otorga la patria potestad a Silvia Consuelo Triana sobre Brayan Andrey Medina Hernández.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        


        <!-- Botón de Acción -->
        <div class="text-center">
            <a href="#" class="btn-custom">Actualizar Información</a>
        </div>
    </div>

    <!-- Vinculación con Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>