<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formato de entrada de almacén</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 20px;
      font-size: 12px;
    }

    .container {
      max-width: 800px;
      margin: 0 auto;
    }

    .title {
      text-align: center;
      font-size: 16px;
      font-weight: bold;
      border: 1px solid #000;
      padding: 10px;
      margin-bottom: 20px;
    }

    .form-group {
      margin-bottom: 10px;
    }

    .form-group label {
      font-weight: bold;
      display: inline-block;
      width: 150px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    th,
    td {
      border: 1px solid #000;
      padding: 5px;
      text-align: left;
    }

    .signatures {
      display: flex;
      justify-content: space-between;
      margin-top: 50px;
    }

    .signature {
      text-align: center;
      width: 200px;
    }

    .signature-line {
      border-top: 1px solid #000;
      margin-bottom: 5px;
    }

    @media print {
      body {
        font-size: 10px;
      }

      .title {
        font-size: 14px;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <h1 class="title">FORMATO DE ENTRADA DE ALMACEN</h1>

    <div style="padding-left: 25px;">
      <div>
        <label for="nro"><strong>Nro:</strong></label>
        <span id="nro" placeholder="Número de entrada" style="text-decoration: underline;">{{ $entradaProducto->id }}</span>
      </div>
      <br>
      <div>
        <label for="fechaHora"><strong>Fecha y Hora:</strong></label>
        <span id="fechaHora" placeholder="Fecha y hora de la entrada" style="text-decoration: underline;">{{ $entradaProducto->created_at }}</span>
      </div>
      <br>
      <div>
        <label for="tipoEntrada"><strong>Tipo de Entrada:</strong></label>
        <span id="tipoEntrada" placeholder="Tipo de entrada">{{ $entradaProducto->tipo_entrada }}</span>
      </div>
      <br>
      <div>
        <label for="procedencia"><strong>Procedencia:</strong></label>
        <span id="procedencia" placeholder="Procedencia de las mercancías" style="text-decoration: underline;">{{ $entradaProducto->procedencia }}</span>
      </div>
      <br>
      <div>
        <label for="nroGuia"><strong>N° Guia:</strong></label>
        <span id="nroGuia" placeholder="Número de guía" style="text-decoration: underline;">{{ $entradaProducto->guia_remision }}</span>
      </div>
    </div>
    <br><br>

    <table>
      <thead>
        <tr>
          <th>Item</th>
          <th>Descripción</th>
          <th>Unid Medida</th>
          <th>Marca</th>
          <th>Cantidad</th>
        </tr>
      </thead>
      <tbody>
        @foreach($entradaProducto->itemsEntrada as $index => $itemEntrada)
        <tr>
          <td>{{ $index + 1 }}</td>
          <td>{{ $itemEntrada->producto->nombre }}</td>
          <td>{{ $itemEntrada->producto->unidadMedida->descripcion }}</td>
          <td>{{ $entradaProducto->proveedor->razon_social }}</td>
          <td>{{ $itemEntrada->cantidad }}</td>
        </tr>
        @endforeach
      </tbody>
      <tr>
        <th colspan="5">Observaciones:</th>
      </tr>
      <tr>
        <td colspan="5"></td>
      </tr>
      <tr>
        <td colspan="5"></td>
      </tr>
    </table>
    <div class="signatures">
      <div class="signature">
        <div class="signature-line"></div>
        {{ $entradaProducto->audit ? ($entradaProducto->audit->userCreated ? $entradaProducto->audit->userCreated->email : 'N/A') : 'N/A' }}
        <span>Abastecimiento</span>
      </div>
      <br>
      <div class="signature">
        <div class="signature-line"></div>
        {{ $entradaProducto->audit ? ($entradaProducto->audit->userCreated ? $entradaProducto->audit->userCreated->email : 'N/A') : 'N/A' }}
        <span>Responsable del Almacén</span>
      </div>
    </div>
  </div>
</body>

</html>