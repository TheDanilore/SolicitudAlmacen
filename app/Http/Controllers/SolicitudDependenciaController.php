<?php

namespace App\Http\Controllers;

use App\Models\ItemReq;
use App\Models\Producto;
use App\Models\SolicitudAlmacen;
use App\Models\SolicitudDependencia;
use App\Models\SolicitudDependenciaProducto;
use App\Models\SolicitudUnidad;
use App\Models\Unidad;
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

class SolicitudDependenciaController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:lista-solicituddependencia|aprobar-solicituddependencia|rechazar-solicituddependencia|detalle-solicituddependencia', ['only' => ['index']]);
        $this->middleware('permission:aprobar-solicituddependencia', ['only' => ['approve']]);
        $this->middleware('permission:rechazar-solicituddependencia', ['only' => ['reject']]);
        $this->middleware('permission:detalle-solicituddependencia', ['only' => ['show']]);
        $this->middleware('permission:registro-solicituddependencia', ['only' => ['registro']]);
    }

    public function index()
    {
        try {
            $dependencia_id = Auth::user()->unidad->dependencia_id;
            $solicitudes = SolicitudUnidad::where('dependencia_id', $dependencia_id)
                ->orderByRaw("CASE WHEN estado = 'pendiente' THEN 1 ELSE 2 END")
                ->orderBy('created_at', 'desc') 
                ->get();
            return view('solicitud_dependencia.index', compact('solicitudes'));
        } catch (\Exception $e) {
            return redirect()->route('solicitud_dependencia.index')->with('error', 'Ocurrió un error al cargar los datos: ' . $e->getMessage());
        }
    }


    public function registroAlmacen()
    {
        try {
            $dependencia_id = Auth::user()->unidad->dependencia_id;
            $solicitudes = SolicitudDependencia::where('dependencia_id', $dependencia_id)
                ->orderByRaw("CASE WHEN estado = 'pendiente' THEN 1 ELSE 2 END")
                ->orderBy('created_at', 'desc') 
                ->get();
            return view('solicitud_dependencia_almacen.index', compact('solicitudes'));
        } catch (\Exception $e) {
            return redirect()->route('solicitud_dependencia_almacen.index')->with('error', 'Ocurrió un error al cargar los datos: ' . $e->getMessage());
        }
    }

    public function approve($id)
    {
        try {
            $solicitudUnidad = SolicitudUnidad::findOrFail($id);

            // Verificar que el usuario autenticado es el jefe de la unidad jefatura de la misma dependencia
            $unidadUsuario = Unidad::find(Auth::user()->unidad_id);

            if ($unidadUsuario->dependencia_id !== $solicitudUnidad->unidad->dependencia_id) {
                return redirect()->route('solicitud_dependencia.index')->with('error', 'No tiene permisos para aprobar esta solicitud.');
            }

            $solicitudUnidad->created_at = now();
            $solicitudUnidad->estado = 'aprobada';
            $solicitudUnidad->save();

            // Crear una solicitud de dependencia
            $solicitudDependencia = new SolicitudDependencia();
            $solicitudDependencia->solicitud_unidad_id = $solicitudUnidad->id;
            $solicitudDependencia->unidad_id = $solicitudUnidad->unidad_id; // Guardar la unidad que hizo la solicitud
            $solicitudDependencia->dependencia_id = $solicitudUnidad->dependencia_id;
            $solicitudDependencia->estado = 'pendiente'; // Estado inicial para el jefe para solicitar al almacen
            $solicitudDependencia->save();

            // Crear registros en SolicitudDependenciaProducto
            foreach ($solicitudUnidad->productos as $producto) {
                SolicitudDependenciaProducto::create([
                    'solicitud_dependencia_id' => $solicitudDependencia->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $producto->pivot->cantidad,
                ]);
            }

            return redirect()->route('solicitud_dependencia.index')->with('success', 'Solicitud aprobada exitosamente!');
        } catch (\Exception $e) {
            return redirect()->route('solicitud_dependencia.index')->with('error', 'Ocurrió un error al aprobar la solicitud: ' . $e->getMessage());
        }
    }

    public function reject($id)
    {
        try {
            $solicitud = SolicitudUnidad::findOrFail($id);
            $solicitud->estado = 'rechazada';
            $solicitud->save();

            return redirect()->route('solicitud_dependencia.index')->with('success', 'Solicitud rechazada!');
        } catch (\Exception $e) {
            return redirect()->route('solicitud_dependencia.index')->with('error', 'Ocurrió un error al rechazar la solicitud: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $solicitud = SolicitudUnidad::findOrFail($id);
            $solicitud->load('productos');
            return view('solicitud_dependencia.show', compact('solicitud'));
        } catch (\Exception $e) {
            return redirect()->route('solicitud_dependencia.index')->with('error', 'Ocurrió un error al mostrar la solicitud: ' . $e->getMessage());
        }
    }

    public function showRegistroAlmacen($id)
    {
        try {
            $solicitud = SolicitudDependencia::findOrFail($id);
            $solicitud_almacen = SolicitudAlmacen::where('solicitud_dependencia_id', $id)->get();
            $solicitud->load(['productos']);
            return view('solicitud_dependencia_almacen.show', compact('solicitud', 'solicitud_almacen'));
        } catch (\Exception $e) {
            return redirect()->route('solicitud_dependencia_almacen.index')->with('error', 'Ocurrió un error al mostrar el registro del almacén: ' . $e->getMessage());
        }
    }


    public function formatoPdf($id)
    {
        try {
            // Buscar la solicitud específica con sus relaciones
            $solicitud = SolicitudUnidad::with(['audit.userCreated', 'audit.userUpdated', 'tiporequerimiento'])
                ->findOrFail($id);

            $solicitud_dependencia = SolicitudDependencia::where('solicitud_unidad_id', $id)->get();

            // Cargar la vista del PDF con los datos de la solicitud
            $pdf = PDF::loadView('solicitud_dependencia.formato', [
                'solicitud' => $solicitud,
                'solicitud_dependencia' => $solicitud_dependencia,
            ]);

            // Descargar el PDF
            return $pdf->download('FUR_solicitud_' . $solicitud->id . '.pdf');
        } catch (\Exception $e) {
            return redirect()->route('solicitud_unidad.index')->with('error', 'Ocurrió un error al exportar los datos a PDF: ' . $e->getMessage());
        }
    }
}
