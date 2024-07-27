<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formato Unico de Requerimiento</title>
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

    .header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 20px;
    }

    .header h1 {
      margin: 0;
      font-size: 16px;
      white-space: nowrap;
      flex-grow: 1;
      text-align: center;
    }

    .header-right {
      text-align: right;
      min-width: 200px;
    }

    .header-right div {
      margin-bottom: 5px;
      display: flex;
      justify-content: flex-end;
      align-items: center;
    }

    .header-right label {
      margin-right: 5px;
      white-space: nowrap;
    }

    .header-right input {
      width: 100px;
    }

    .box {
      border: 1px solid #000;
      padding: 5px;
      margin-bottom: 10px;
    }

    .destinado-a {
      border: 1px solid #000;
      padding: 10px;
      margin-bottom: 10px;
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
      width: 30%;
    }

    .signature-line {
      border-top: 1px solid #000;
      margin-top: 30px;
      padding-top: 5px;
    }

    @media print {
      body {
        font-size: 10px;
      }

      .header h1 {
        font-size: 14px;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="header">
      <h1>FORMATO UNICO DE REQUERIMIENTO</h1>
      <div class="header-right">
        <div>
          <label>FECHA:</label>
          <input type="text" value="{{ $solicitud->created_at->format('d/m/Y') }}" readonly>
        </div>
        <div>
          <label>N° REQUERIMIENTO:</label>
          <input type="text" value="{{ $solicitud->id }}" readonly>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label for="dependencia">Dependencia Solicitante:</label>
      <input id="dependencia" type="text" value="{{ $solicitud->unidad->dependencia->nombre }}" readonly style="width: 70%;">
    </div><br>

    <div class="box">
      TIPO DE REQUERIMIENTO: {{ $solicitud->tiporequerimiento->descripcion }}
    </div>

    <div class="destinado-a">
      DESTINADO A: (ESPECIFICAR LA ACTIVIDAD, SERVICIO, OTROS)
      <p>____________________________________________________</p>
    </div>

    <table>
      <tr>
        <th>ITEM</th>
        <th>DESCRIPCIÓN</th>
        <th>UNIDAD DE MEDIDA</th>
        <th>CANTIDAD</th>
      </tr>
      @foreach($solicitud->productos as $index => $producto)
      <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $producto->nombre }}</td>
        <td>{{ $producto->unidadMedida->descripcion }}</td>
        <td>{{ $producto->pivot->cantidad }}</td>
      </tr>
      @endforeach
    </table>

    <div class="form-group">
      <label for="observacion">OBSERVACIÓN:</label>
      <input id="observacion" type="text" value="N/A" readonly style="width: 80%;">
    </div>

    <div class="signatures">
      <div class="signature">
        <div class="signature-line">DEPENDENCIA SOLICITANTE<br>FIRMA Y SELLO</div>
      </div>
      <div class="signature">
        <div class="signature-line">UNIDAD SOLICITANTE<br>FIRMA Y SELLO</div>
      </div>
      <div class="signature">
        <div class="signature-line">RECEPCIONADO<br>FIRMA Y SELLO</div>
      </div>
    </div>
  </div>
</body>

</html>
