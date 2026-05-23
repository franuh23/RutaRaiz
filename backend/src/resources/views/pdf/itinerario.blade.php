<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mi Itinerario - RutaRaíz</title>
    <style>
        /* Estilos globales y tipografía para DomPDF */
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333333;
            line-height: 1.4;
            font-size: 13px;
        }

        /* Cabecera corporativa */
        .header {
            border-bottom: 3px solid #2d5a27;
            /* Verde Bosque */
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header table {
            width: 100%;
        }

        .brand-title {
            color: #2d5a27;
            font-size: 26px;
            font-weight: bold;
            margin: 0;
        }

        .brand-subtitle {
            color: #7c533c;
            /* Tono Tierra */
            font-size: 14px;
            margin: 3px 0 0 0;
        }

        .meta-datos {
            text-align: right;
            font-size: 12px;
            color: #666666;
        }

        /* Tarjeta de resumen de la planificación */
        .resumen-card {
            background-color: #f4f6f3;
            border-left: 4px solid #7c533c;
            padding: 15px;
            margin-bottom: 25px;
        }

        .resumen-card table {
            width: 100%;
        }

        .resumen-label {
            font-weight: bold;
            color: #2d5a27;
            width: 25%;
        }

        .resumen-value {
            color: #333333;
        }

        /* Tabla de Etapas */
        .tabla-etapas {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .tabla-etapas th {
            background-color: #2d5a27;
            color: #ffffff;
            font-weight: bold;
            text-align: left;
            padding: 10px;
            font-size: 12px;
            text-transform: uppercase;
        }

        .tabla-etapas td {
            padding: 12px 10px;
            border-bottom: 1px solid #dddddd;
        }

        /* Filas alternas */
        .tabla-etapas tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .col-dia {
            font-weight: bold;
            color: #7c533c;
            width: 10%;
        }

        .col-tramo {
            width: 75%;
        }

        .col-distancia {
            text-align: right;
            font-weight: bold;
            width: 15%;
            color: #2d5a27;
        }

        .flecha {
            color: #7c533c;
            padding: 0 5px;
        }

        /* Pie de página */
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 11px;
            color: #999999;
            border-top: 1px solid #eeeeee;
            padding-top: 8px;
        }
    </style>
</head>

<body>

    <div class="header">
        <table>
            <tr>
                <td>
                    <h1 class="brand-title">RutaRaíz</h1>
                    <p class="brand-subtitle">Tu planificador personalizado del Camino</p>
                </td>
                <td class="meta-datos">
                    <p><strong>Peregrino:</strong> {{ $planificacion->usuario->nick ?? 'Usuario de RutaRaíz' }}</p>
                    <p><strong>Generado el:</strong> {{ now()->format('d/m/Y') }}</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="resumen-card">
        <table>
            <tr>
                <td class="resumen-label">Ruta Elegida:</td>
                <td class="resumen-value"><strong>{{ $planificacion->ruta->nombre }}</strong></td>
                <td class="resumen-label">Fecha de Salida:</td>
                <td class="resumen-value">{{ $fecha }}</td>
            </tr>
            <tr>
                <td class="resumen-label">Distancia Total:</td>
                <td class="resumen-value">{{ $totalKm }} km</td>
                <td class="resumen-label">Duración:</td>
                <td class="resumen-value">{{ $planificacion->dias_totales }}
                    {{ $planificacion->dias_totales == 1 ? 'día' : 'días' }}</td>
            </tr>
            <tr>
                <td class="resumen-label">Ritmo Configurado:</td>
                <td class="resumen-value">{{ round($planificacion->km_dia, 0) }} km/día máximo</td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>

    <h2 style="color: #2d5a27; font-size: 16px; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;">
        Desglose Oficial de Etapas
    </h2>
    <table class="tabla-etapas">
        <thead>
            <tr>
                <th class="col-dia">Día</th>
                <th class="col-tramo">Tramo de Caminata</th>
                <th class="col-distancia" style="text-align: right;">Distancia</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($planificacion->etapas as $etapa)
                <tr>
                    <td class="col-dia">Día {{ $etapa->dia }}</td>
                    <td class="col-tramo">
                        {{ $etapa->localizacionInicio->nombre }}
                        <span style="color: #7c533c; font-weight: bold; padding: 0 8px;">&gt;</span>
                        <strong>{{ $etapa->localizacionFin->nombre }}</strong>
                    </td>
                    <td class="col-distancia" style="text-align: right;">{{ round($etapa->distancia, 1) }} km</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Documento generado automáticamente por RutaRaíz. ¡Buen Camino, Peregrino!
    </div>

</body>

</html>
