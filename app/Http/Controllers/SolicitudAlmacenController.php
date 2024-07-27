<?php

namespace App\Http\Controllers;

use App\Models\ItemSalida;
use App\Models\Movimiento;
use App\Models\Producto;
use App\Models\SalidaProducto;
use App\Models\SolicitudAlmacen;
use App\Models\SolicitudDependencia;
use App\Models\SolicitudUnidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//Exportar excel
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

//Exportar en pdf
use Barryvdh\DomPDF\Facade\Pdf;

//Exportar en Word
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;


class SolicitudAlmacenController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:lista-solicitudalmacen|completo-solicitudalmacen|aprobar-solicitudalmacen|rechazar-solicitudalmacen|detalle-solicitudalmacen', ['only' => ['index', 'completo']]);
        $this->middleware('permission:aprobar-solicitudalmacen', ['only' => ['approve']]);
        $this->middleware('permission:rechazar-solicitudalmacen', ['only' => ['reject']]);
        $this->middleware('permission:detalle-solicitudalmacen', ['only' => ['show']]);
        $this->middleware('permission:completo-solicitudalmacen', ['only' => ['completo']]);
    }

    public function index()
    {
        try {
            $solicitudes = SolicitudDependencia::orderByRaw("CASE WHEN estado = 'pendiente' THEN 1 ELSE 2 END")
                ->orderBy('created_at', 'desc')
                ->get();
            return view('solicitud_almacen.index', compact('solicitudes'));
        } catch (\Exception $e) {
            return redirect()->route('solicitud_almacen.index')->with('error', 'Ocurrió un error al cargar los datos: ' . $e->getMessage());
        }
    }

    public function approve($id)
    {
        try {
            $solicitud = SolicitudDependencia::findOrFail($id);

            // Recoger todos los productos que no tienen suficiente stock
            $productosSinStock = collect();

            foreach ($solicitud->productos as $productoSolicitud) {
                $producto = Producto::findOrFail($productoSolicitud->producto_id);
                if ($producto->cantidad < $productoSolicitud->cantidad) {
                    $productosSinStock->push($producto);
                }
            }

            // Si hay productos sin stock, redirigir de vuelta a la vista con un mensaje de error
            if ($productosSinStock->isNotEmpty()) {
                return redirect()->route('solicitud_almacen.show', $id)
                    ->withErrors(['error' => 'No hay suficiente stock para los siguientes productos: ' . $productosSinStock->pluck('nombre')->implode(', ')]);
            }

            // Crear la salida de productos
            $salidaProducto = new SalidaProducto();
            $salidaProducto->created_at = now();
            $salidaProducto->save();



            // Generar la salida de productos y actualizar el stock
            foreach ($solicitud->productos as $productoSolicitud) {
                $producto = Producto::findOrFail($productoSolicitud->producto_id);
                $stock_anterior = $producto->cantidad; //guardamos el stock anterior
                $producto->cantidad -= $productoSolicitud->cantidad;
                $producto->save();

                $itemSalida = ItemSalida::create([
                    'salida_producto_id' => $salidaProducto->id,
                    'productos_id' => $productoSolicitud->producto_id,
                    'cantidad' => $productoSolicitud->cantidad,
                    'p_unitario' => $producto->precio_actual,
                    'costo_total' => $producto->precio_actual * $productoSolicitud->cantidad,
                ]);

                Movimiento::create([
                    'producto_id' => $producto->id,
                    'tipo' => 'salida',
                    'item_entrada_id' => 0,
                    'item_salida_id' => $itemSalida->id, // Relacionamos con ItemSalida
                    'stock_anterior' => $stock_anterior,
                    'cantidad' => $productoSolicitud['cantidad'],
                ]);
            }


            //Crear solicitud almacen (registro)
            $solicitudAlmacen = new SolicitudAlmacen();
            $solicitudAlmacen->created_at = now();
            $solicitudAlmacen->solicitud_dependencia_id = $solicitud->id;
            $solicitudAlmacen->salida_producto_id = $salidaProducto->id;
            $solicitudAlmacen->save();


            // Actualizar el estado de la solicitud
            $solicitud->estado = 'aprobada';
            $solicitud->save();

            return redirect()->route('solicitud_almacen.index')->with('success', 'Solicitud aprobada y productos despachados correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('solicitud_almacen.index')->with('error', 'Ocurrió un error al aprobar la solicitud: ' . $e->getMessage());
        }
    }


    public function reject($id)
    {
        try {
            $solicitud = SolicitudDependencia::findOrFail($id);
            $solicitud->estado = 'rechazada';
            $solicitud->save();

            return redirect()->route('solicitud_almacen.index')->with('success', 'Solicitud rechazada!');
        } catch (\Exception $e) {
            return redirect()->route('solicitud_almacen.index')->with('error', 'Ocurrió un error al rechazar la solicitud: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            // Cargar la solicitud de dependencia
            $solicitud = SolicitudDependencia::findOrFail($id);

            // Cargar la solicitud de unidad asociada
            $solicitud_unidad = SolicitudUnidad::where('id', $solicitud->solicitud_unidad_id)->first();

            // Cargar la solicitud de almacén asociada
            $solicitud_almacen = SolicitudAlmacen::where('solicitud_dependencia_id', $id)->first();

            // Cargar los productos asociados a la solicitud de dependencia
            $solicitud->load('productos');

            // Pasar los datos a la vista
            return view('solicitud_almacen.show', compact('solicitud', 'solicitud_unidad', 'solicitud_almacen'));
        } catch (\Exception $e) {
            return redirect()->route('solicitud_almacen.index')->with('error', 'Ocurrió un error al mostrar la solicitud: ' . $e->getMessage());
        }
    }


    public function generarPDF($id)
    {
        try {
            $solicitud = SolicitudDependencia::with(['unidad.dependencia', 'jefe', 'productos'])->findOrFail($id);
            $solicitud_almacen = SolicitudAlmacen::where('solicitud_dependencia_id', $id)->get();
            $pdf = PDF::loadView('solicitud_almacen.almacen_pdf', compact('solicitud'));
            return $pdf->download('solicitud_almacen_' . $id . '.pdf');
        } catch (\Exception $e) {
            return redirect()->route('solicitud_almacen.index')->with('error', 'Ocurrió un error al mostrar la solicitud: ' . $e->getMessage());
        }
    }

    public function formatoPdf($id)
    {
        try {
            // Cargar la solicitud de dependencia
            $solicitud = SolicitudDependencia::findOrFail($id);

            // Cargar la solicitud de unidad asociada
            $solicitud_unidad = SolicitudUnidad::where('id', $solicitud->solicitud_unidad_id)->first();

            // Cargar la solicitud de almacén asociada
            $solicitud_almacen = SolicitudAlmacen::where('solicitud_dependencia_id', $id)->first();


            $pdf = PDF::loadView('salidas.formato_pdf', [
                'solicitud' => $solicitud,
                'solicitud_almacen' => $solicitud_almacen,
                'solicitud_unidad' => $solicitud_unidad,
            ]);

            // Descargar el PDF
            return $pdf->download('FUR_solicitud_' . $solicitud->id . '.pdf');
        } catch (\Exception $e) {
            return redirect()->route('solicitud_almacen.index')->with('error', 'Ocurrió un error al exportar los datos a PDF: ' . $e->getMessage());
        }
    }
}
