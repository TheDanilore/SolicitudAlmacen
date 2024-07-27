<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarjeta Kardex</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .kardex {

            max-width: 900px;
            margin: 0 auto;
        }
        .header {
            background-color: red;
            color: white;
            padding: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            margin: 0 auto;
            font-size: 24px;
            background-color: yellow;
            color: black;
            font-weight: bold;
            padding: 5px 20px;
            width: 70%;
            text-align: center;
        }
        .number {
            color: white;
            margin-right: 10px;
        }
        .info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            padding: 20px;
            background-color: white;
        }
        .info-left, .info-right {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 5px 10px;
            align-items: center;
        }
        .info label {
            font-weight: bold;
            text-align: right;
        }
        .info span {
            background-color: #ffcccb;
            padding: 2px 5px;
            display: inline-block;
            width: 100%;
        }
        .tables-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .table {
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }
        .table th {
            background-color: yellow;
            
        }
        .table-left {
            width: 60%;
            margin-right: 20px; 
        }
        .table-right {
            width: 38%;
        }
        .table-right th:first-child {
            background-color: white;
            border: none;
            font-weight: bold;
            text-align: center;
        }
        span{
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="kardex">
        <div class="header">
            &nbsp;&nbsp;&nbsp;&nbsp;<h1>TARJETA KARDEX</h1>
            <span class="number" style="border: black 4px solid; padding: 5px;">N° 0010</span>
        </div>
        <div class="info">
            <div class="info-left">
                <label>Artículo:</label>
                <span>{{ $producto->nombre}}</span>
                <label>Marca:</label>
                <span>{{ $producto->proveedor}}</span>
                <label>Orden de compra:</label>
                <span>N°91-2023</span>
            </div>
            <div class="info-right">
                <label>Inventario Inicial:</label>
                <span>140</span>
                <label>Unidad de Medida:</label>
                <span>PAQUETES</span>
            </div>
        </div>
        <div class="tables-container">
            <table class="table table-left">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Área</th>
                        <th>Nombres</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>18/08/2023</td>
                        <td>Oficina de Obras</td>
                        <td>Raúl Vásquez Elías</td>
                    </tr>
                    <!-- Añade más filas según sea necesario -->
                </tbody>
            </table>
            <table class="table table-right" style="padding-left: 50px;">
                <thead>
                    <tr>
                        <th style="background-color: yellow; border-top: black 1px solid; border-left: black 1px solid;">N°</th>
                        <th colspan="5">UNIDADES</th>
                    </tr>
                    <tr>
                        <th style="background-color: yellow; border-left: black 1px solid;">PECOSA</th>
                        <th>Inv. Inicial</th>
                        <th>Costo Unitario</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                        <th>Inv. Final</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1313</td>
                        <td>140</td>
                        <td>20.00</td>
                        <td></td>
                        <td>3</td>
                        <td>137</td>
                    </tr>
                    <!-- Añade más filas según sea necesario -->
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>