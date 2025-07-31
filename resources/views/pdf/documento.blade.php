<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $nombre }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
        }

        .pagina-centrada {
            display: flex;
            flex-direction: column;
            justify-content: center;
            /* centrado vertical */
            align-items: center;
            /* centrado horizontal */
            height: 100vh;
            /* altura completa de la hoja */
            text-align: center;
        }

        h1 {
            color: rgb(0, 0, 0);
            margin-bottom: 10px;
        }

        img {
            max-width: 300px;
            height: auto;
        }

        .encabezado {
            text-transform: uppercase;
            font-weight: bold;
            font-size: 14pt;
        }

        .titulo2 {
            font-weight: bold;
            font-size: 12pt;
            margin-left: 10px;
        }

        .divunido {
            margin-top: 0px;
            line-height: 0.4;
            /* Menos interlineado */
        }
        .margen20 {
            margin-left: 20px;
        }
    </style>
</head>

<body>

    <div class="pagina-centrada">
        <br><br><br><br><br><br>
        <h1>PLAN DE NEGOCIO</h1>

        <h2>{{ $nombre }}</h2>
        <img src="{{ $logoempresa }}" alt="">
        <br><br><br>
        <h3>{{ $mesyanio }}</h3>

    </div>

    <div style="page-break-before: always;"></div>

    <p class="encabezado">INFORMACIÓN DE LA EMPRESA</p>
   
    <div class="divunido">
        <p class="titulo2">- Datos Generales de la Empresa</p>
        <p class="margen20"><b>Nombre del negocio:</b> {{ $nombre_empresa }}</p>
        <p class="margen20"><b>Nombre Propietario/Representante Legal:</b> {{ $nombre_representante_legal }}</p>
        <p class="margen20"><b>Actividad Económica:</b> {{ $actividad_economica }}</p>
        <p class="margen20"><b>Dirección:</b> {{ $direccion }}</p>
        <p class="margen20"><b>Teléfono:</b> {{ $telefono }}</p>
        <p class="margen20"><b>Correo Electrónico:</b> {{ $email }}</p>

    </div>


</body>

</html>
