<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\SolicitudDependencia;
use App\Models\SolicitudUnidad;
use App\Models\TipoRequerimiento;
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

class SolicitudUnidadController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:lista-solicitudunidad|crear-solicitudunidad|detalle-solicitudunidad', ['only' => ['index']]);
        $this->middleware('permission:crear-solicitudunidad', ['only' => ['create', 'store']]);
        $this->middleware('permission:detalle-solicitudunidad', ['only' => ['show']]);
    }

    public function index()
    {
        try {
            // Obtener las solicitudes del usuario autenticado a través de la tabla de auditoría
            $userId = Auth::id();
            $solicitudes = SolicitudUnidad::whereHas('audit', function ($query) use ($userId) {
                $query->where('user_id_created', $userId);
            })->orderByRaw("CASE WHEN estado = 'pendiente' THEN 1 ELSE 2 END")
            ->orderBy('created_at', 'desc') 
            ->get();

            return view('solicitud_unidad.index', compact('solicitudes'));
        } catch (\Exception $e) {
            return redirect()->route('solicitud_unidad.index')->with('error', 'Ocurrió un error al cargar los datos: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $tipoRequerimiento = TipoRequerimiento::all();
            $productos = Producto::where('estado_producto_id', '1')->get();
            $user = auth()->user();
            $unidadUsuario = $user->unidad; //Consigue la unidad a la que pertenece el usuario, para enviarlo automaticamente
            return view('solicitud_unidad.create', compact('productos', 'unidadUsuario','tipoRequerimiento'));
        } catch (\Exception $e) {
            return redirect()->route('solicitud_unidad.index')->with('error', 'Ocurrió un error al cargar la vista de creación: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            //mensajes personalizados
            $messages = [
                'tipo_requerimiento_id.required' => 'Elegir el tipo de requerimiento es obligatorio.',
                'productos.required' => 'Elegir el producto es obligatorio.',
                'productos.*.cantidad.required' => 'Digite la cantidad.',
            ];
            $validatedData = $request->validate([
                'tipo_requerimiento_id'=>'required|integer|exists:tipo_requerimiento,id',
                'productos' => 'required|array',
                'productos.*.producto_id' => 'required|integer|exists:productos,id',
                'productos.*.cantidad' => 'required|integer|min:1',
                'unidad_id' => 'required|integer|exists:unidad,id',
            ], $messages);


            $unidad = Unidad::findOrFail($validatedData['unidad_id']);
            $dependencia_id = $unidad->dependencia_id;

            $solicitud = new SolicitudUnidad();
            $solicitud->tipo_requerimiento_id = $validatedData['tipo_requerimiento_id'];
            $solicitud->unidad_id = $validatedData['unidad_id'];
            $solicitud->dependencia_id = $dependencia_id;
            $solicitud->estado = 'pendiente'; // Estado inicial
            $solicitud->created_at = now();
            $solicitud->save();

            foreach ($validatedData['productos'] as $productoData) {
                $solicitud->productos()->attach($productoData['producto_id'], [
                    'cantidad' => $productoData['cantidad'],
                ]);
            }

            return redirect()->route('solicitud_unidad.index')->with('success', 'Solicitud creada exitosamente!');
        } catch (\Exception $e) {
            return redirect()->route('solicitud_unidad.index')->with('error', 'Ocurrió un error al crear la Solicitud: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $solicitud = SolicitudUnidad::findOrFail($id);
            $solicitud_dependencia = SolicitudDependencia::where('solicitud_unidad_id', $id)->get();
            $solicitud->load('productos');
            return view('solicitud_unidad.show', compact('solicitud', 'solicitud_dependencia'));
        } catch (\Exception $e) {
            return redirect()->route('solicitud_unidad.index')->with('error', 'Ocurrió un error al mostrar la Solicitud: ' . $e->getMessage());
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
            $pdf = PDF::loadView('solicitud_unidad.formato', [
                'solicitud' => $solicitud,
                'solicitud_dependencia' => $solicitud_dependencia,
            ]);
    
            // Descargar el PDF
            return $pdf->download('FUR_solicitud_' . $solicitud->id . '.pdf');
        } catch (\Exception $e) {
            return redirect()->route('solicitud_unidad.index')->with('error', 'Ocurrió un error al exportar los datos a PDF: ' . $e->getMessage());
        }
    }
    


    public function export(Request $request)
    {
        try {
            $format = $request->query('format', 'excel'); // Default to excel if no format is specified

            switch ($format) {
                case 'pdf':
                    return $this->exportToPdf();
                case 'word':
                    return $this->exportToWord();
                default:
                    return $this->exportToExcel();
            }
        } catch (\Exception $e) {
            return redirect()->route('solicitud_unidad.index')->with('error', 'Ocurrió un error al exportar los datos: ' . $e->getMessage());
        }
    }

    public function exportToPdf()
    {
        try {
            $solicitud = SolicitudUnidad::with(['audit.usercreated', 'audit.userupdated','tiporequerimiento']);
            $solicitud_dependencia = SolicitudDependencia::where('solicitud_unidad_id')->get();

            // Cargar la vista del PDF con los datos de la solicitud
            $pdf = Pdf::loadView('solicitud_unidad.pdf', [
                'solicitud' => $solicitud,
                'solicitud_dependencia' => $solicitud_dependencia,
            ]);

            // Descargar el PDF
            return $pdf->download('solicitud_' . $solicitud->id . '.pdf');
        } catch (\Exception $e) {
            return redirect()->route('solicitud_unidad.index')->with('error', 'Ocurrió un error al exportar los datos a PDF: ' . $e->getMessage());
        }
    }
}
