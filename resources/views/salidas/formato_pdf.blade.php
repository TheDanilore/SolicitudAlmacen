<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Comprobante de Salida</title>

  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 12px;
      margin: 0;
      padding: 20px;
    }

    .container {
      padding: 10px;
    }

    .header {
      text-align: center;
      margin-bottom: 10px;
    }

    .header h2 {
      margin: 0;
      font-size: 16px;
    }

    .header-number {
      float: right;
    }

    .parent-container {
      display: flex;
      justify-content: space-between;
    }

    .date-box {
      padding: 5px;
      width: 200px;
    }

    .date-box table {
      width: 100%;
      border-collapse: collapse;
    }

    .date-box td {
      border: 1px solid #000;
      padding: 2px;
      text-align: center;
    }

    .info-box {
      margin-bottom: 10px;
    }

    .info-box p {
      margin: 5px 0;
      display: flex;
    }

    .info-box p span:first-child {
      width: 200px;
    }

    .oc-box {
      padding: 5px;
      width: 200px;
      margin-left: auto;
      max-height: 39px;
    }

    .oc-box table {
      width: 100%;
      border-collapse: collapse;
    }

    .oc-box td {
      border: 1px solid #000;
      padding: 2px;
      text-align: center;
    }

    .main-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 10px;
    }

    .main-table th,
    .main-table td {
      border: 1px solid #000;
      padding: 5px;
      vertical-align: top;
      text-align: center;
    }

    .main-table tr:last-child td[colspan="8"] {
      border: none;
    }

    .signatures {
      display: flex;
      justify-content: space-between;
    }

    .signature {
      text-align: center;
      width: 30%;
    }

    .signature p {
      border-top: 1px solid #000;
      padding-top: 5px;
      margin-top: 30px;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="header">
      <h2>COMPROBANTE DE SALIDA</h2>
      <div class="header-number">N° {{ $solicitud_almacen->salida_producto_id }}-C</div>
      <div style="padding-left: 60px; padding-top: 10px;"><strong>ALMACÉN</strong></div>
    </div>

    <div class="parent-container">
      <div></div>
      <div class="date-box">
        <table>
          <tr>
            <td rowspan="2">FECHA</td>
            <td>DIA</td>
            <td>MES</td>
            <td>AÑO</td>
          </tr>
          <tr>
            <td>{{ $solicitud_almacen->created_at->format('d') }}</td>
            <td>{{ $solicitud_almacen->created_at->format('m') }}</td>
            <td>{{ $solicitud_almacen->created_at->format('Y') }}</td>
          </tr>
        </table>
      </div>
    </div>

    <div class="parent-container">
      <div class="info-box">
        <p><span>ENTREGAR A:</span> <span>{{ $solicitud_unidad->audit->userCreated->email ?? '' }}</span></p>
        <p><span>DEPENDENCIA SOLICITANTE:</span> <span>{{ $solicitud_dependencia->dependencia->nombre }}</span></p>
        <p><span>PARA USO:</span> <span>OFICINA OBRAS</span></p>
        <p><span>FECHA DE ENTREGA:</span> <span>{{ $solicitud_almacen->created_at->format('d/m/Y') }}</span></p>
      </div>
      <div class="oc-box">
        <table>
          <tr>
            <td rowspan="2">OC</td>
            <td>N°</td>
            <td>DIA</td>
            <td>MES</td>
            <td>AÑO</td>
          </tr>
          <tr>
            <td>{{ $solicitud_almacen->salida_producto_id }}</td>
            <td>{{ $solicitud_almacen->created_at->format('d') }}</td>
            <td>{{ $solicitud_almacen->created_at->format('m') }}</td>
            <td>{{ $solicitud_almacen->created_at->format('Y') }}</td>
          </tr>
        </table>
      </div>
    </div>

    <table class="main-table">
      <tr>
        <th colspan="4">SOLICITADO</th>
        <th colspan="3">DESPACHADO</th>
        <th colspan="2">VALORES</th>
      </tr>
      <tr>
        <th>Item</th>
        <th>Cant</th>
        <th>Medida</th>
        <th>Descripción</th>
        <th>Código</th>
        <th>Cant. Despachad</th>
        <th>Medida</th>
        <th>Unitario</th>
        <th>TOTAL</th>
      </tr>

      @foreach($salida->itemsSalida as $index => $itemSalida)
      <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $itemSalida->cantidad }}</td>
        <td>{{ $itemSalida->producto->unidadMedida->descripcion }}</td>
        <td>{{ $itemSalida->producto->nombre }}</td>
        <td>{{ $itemSalida->producto->id }}</td>
        <td>{{ $itemSalida->cantidad }}</td>
        <td>{{ $itemSalida->producto->unidadMedida->descripcion }}</td>
        <td>S/{{ $itemSalida->p_unitario }}</td>
        <td>S/{{ $itemSalida->costo_total }}</td>
      </tr>
      @endforeach
      <tr>
        <td colspan="8" style="text-align: right;"></td>
        <td><strong>S/{{ $totalGeneral }}</strong></td>
      </tr>
    </table>

    <div class="signatures">
      <p>{{ $solicitud_dependencia->audit->userCreated->email ?? '' }}</p>
      <div class="signature">
        <p>SOLICITANTE</p>
      </div>
      <p>{{ $solicitud_almacen->audit->userCreated->email ?? '' }}</p>
      <div class="signature">
        <p>JEFE DE ALMACEN</p>
      </div>
      <p>{{ $solicitud_unidad->audit->userCreated->email ?? '' }}</p>
      <div class="signature">
        <p>RECIBI CONFORME</p>
      </div>
    </div>
  </div>
</body>

</html>