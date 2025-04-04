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
            /* Márgenes superior e inferior de 50px */
        }

        body {
            margin: 0 50px;
            /* Márgenes izquierdo y derecho de 50px */
            font-family: 'Arial, sans-serif';
            border: 2px solid red;
            /* Borde rojo */
            box-sizing: border-box;
            /* Incluye el borde en el tamaño del contenedor */
            padding: 20px;
            /* Relleno interior */
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
            /* Añade espacio al final del contenido para el footer */
        }
    </style>
</head>

<body>

    <div class="header">
        <table class="header-table" style="margin-left: 6.3%; margin-right: 80%; width: 87.4%">
            <tr style="border: 2px solid red;">
                <td style="border-right: 2px solid #000;">
                    <img src="{{ 'storage/header/2.png' }}" alt="" width="670px" height="150px">
                </td>
            </tr>
        </table>
    </div> <br><br><br><br><br><br><br>
    
    <div class="container" >
        <table class="customTable" style="margin-left: 1.3%; margin-right: 76%; width: 90.4%">
            <thead>
                <tr>
                    <th colspan="12"
                        style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; text-align: center;">
                        AUTORIZACIÓN</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="12"
                        style="border: 1px solid black; text-align: justify; margin-left: 50px; margin-right: 50px">
                        <div class="carta">
                            <p><strong>#Yo '.$row['nombres'].'</strong></p>
                            <p>Identificado (a) con cédula de ciudadanía No. <strong>'.$row['cedula'].'</strong></p>
                            <p>Expedida en: <strong>Bogotá D.C</strong></p>
                            <p>Hago constar de manera libre y voluntaria que la información procesada en el presente
                                estudio, obedece a la verdad y <strong>AUTORIZO</strong> plenamente a la empresa
                                <strong>GRUPO DE TAREAS EMPRESARIALES</strong> con NIT <strong>830.142.258-3</strong> para
                                realizar VERIFICACIÓN ACADÉMICA, VERIFICACIÓN JUDICIAL, CENTRAL DE RIESGOS LEY 1266 y LEY
                                1581 del 2012 habeas data. Para tomar las pruebas necesarias y suficientes, a fin de
                                establecer la veracidad de la información suministrada, para que en el momento que se haga
                                necesaria se utilice como prueba. Contemplando en el <strong>DECRETO 1266 DE 2008</strong>.
                            </p>
    
                        </div>
    
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Direccion</td>
                    <td colspan="3" style="border: 1px solid black;">' . $row['direccion'] . '</td>
                    <td colspan="3" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">Barrio
                    </td>
                    <td colspan="3" style="border: 1px solid black;">' . $row['barrio'] . '</td>
    
                </tr>
                <tr>
                    <td colspan="3" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Localidad:</td>
                    <td colspan="3" style="border: 1px solid black;">' . $row['localidad'] . '</td>
                    <td colspan="3" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Teléfono Fijo: </td>
                    <td colspan="3" style="border: 1px solid black;">' . $row['telefono'] . '</td>
    
                </tr>
                <tr>
                    <td colspan="3" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">
                        Celular:</td>
                    <td colspan="3" style="border: 1px solid black;">' . $row['celular'] . '</td>
                    <td colspan="3" style="font-weight: bold;  background-color: #ABABAB; border: 1px solid black; ">Correo
                        electronico </td>
                    <td colspan="3" style="border: 1px solid black;">' . $row['correo'] . '</td>
    
                </tr>
                <tr>
                    <td colspan="12" style="border: 1px solid black; text-align: justify;">
                        <img src="' . $ruta_firma . '" alt="Logo"
                            style="border: 2px solid black; height: 150px; width: 1006px;">
    
                    </td>
                </tr>
            </tbody>
        </table>
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