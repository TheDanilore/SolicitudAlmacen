<?php

namespace App\Http\Controllers;

use App\Models\SalidaProducto;
use App\Models\Producto;
use App\Models\ItemSalida;
use App\Models\Movimiento;
use App\Models\SolicitudAlmacen;
use App\Models\SolicitudDependencia;
use App\Models\SolicitudUnidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

//Exportar excel
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

//Exportar en pdf
use Barryvdh\DomPDF\Facade\Pdf;

//Exportar en Word
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class SalidaProductoController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:lista-salidaproducto|crear-salidaproducto|detalle-salidaproducto', ['only' => ['index']]);
        $this->middleware('permission:crear-salidaproducto', ['only' => ['create', 'store']]);
        $this->middleware('permission:detalle-salidaproducto', ['only' => ['show']]);
    }


    public function index()
    {
        try {
            $salidas = SalidaProducto::all();
            return view('salidas.index', compact('salidas'));
        } catch (\Exception $e) {
            return redirect()->route('salidas.index')->with('error', 'Ocurrió un error al cargar los datos: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $productos = Producto::where('estado_producto_id', '1')->get();
            return view('salidas.create', compact('productos'));
        } catch (\Exception $e) {
            return redirect()->route('salidas.index')->with('error', 'Ocurrió un error al cargar la vista de creación: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'productos' => 'required|array',
                'productos.*.producto_id' => 'required|integer|exists:productos,id',
                'productos.*.cantidad' => 'required|integer|min:1',
                'productos.*.precio_unitario' => 'required|numeric|min:0.01',
            ]);

            $productosSinStock = [];

            // Verificar si hay suficiente stock
            foreach ($validatedData['productos'] as $productoData) {
                $producto = Producto::find($productoData['producto_id']);
                if ($producto->cantidad < $productoData['cantidad']) {
                    $productosSinStock[] = $producto->nombre;
                }
            }

            if (!empty($productosSinStock)) {
                return redirect()->back()->withErrors([
                    'error' => 'No hay suficiente stock para los siguientes productos: ' . implode(', ', $productosSinStock)
                ])->withInput();
            }

            DB::transaction(function () use ($validatedData) {
                $salida = new SalidaProducto();
                $salida->created_at = now();
                $salida->save();

                foreach ($validatedData['productos'] as $productoData) {
                    $itemSalida = new ItemSalida([
                        'salida_producto_id' => $salida->id,
                        'productos_id' => $productoData['producto_id'],
                        'cantidad' => $productoData['cantidad'],
                        'p_unitario' => $productoData['precio_unitario'],
                        'costo_total' => $productoData['cantidad'] * $productoData['precio_unitario'],
                    ]);

                    $itemSalida->save();

                    $producto = Producto::find($productoData['producto_id']);
                    $stock_anterior = $producto->cantidad; //guardamos el stock anterior
                    $producto->cantidad -= $productoData['cantidad'];
                    $producto->precio_actual = $productoData['precio_unitario'];
                    $producto->save();

                    Movimiento::create([
                        'producto_id' => $producto->id,
                        'tipo' => 'salida',
                        'item_entrada_id' => 0,
                        'item_salida_id' => $itemSalida->id, // Relacionamos con ItemSalida
                        'stock_anterior' => $stock_anterior,
                        'cantidad' => $productoData['cantidad'],
                    ]);
                }
            });

            return redirect()->route('salidas.index')->with('success', 'Salida creada exitosamente!');
        } catch (\Exception $e) {
            return redirect()->route('salidas.index')->with('error', 'Ocurrió un error al crear la Salida: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            // Obtener la salida de producto por su ID
            $salida = SalidaProducto::findOrFail($id);

            // Obtener los items de salida con sus productos y movimientos
            $itemsSalida = ItemSalida::with(['producto', 'movimiento'])->where('salida_producto_id', $id)->get();

            return view('salidas.show', compact('salida', 'itemsSalida'));
        } catch (\Exception $e) {
            return redirect()->route('salidas.index')->with('error', 'Ocurrió un error al mostrar la salida: ' . $e->getMessage());
        }
    }

    public function exportSingleToPdf($id)
    {
        try {
            // Buscar la salida específica con sus relaciones
            $salidaProducto = SalidaProducto::with(['itemsSalida.producto', 'itemsSalida.movimiento', 'audit.usercreated', 'audit.userupdated'])
                ->findOrFail($id);

            // Cargar la vista 'pdf_single' con los datos
            $pdf = PDF::loadView('salidas.pdf_single', compact('salidaProducto'));

            // Personalizar la configuración del documento PDF
            $pdf->setPaper('A4', 'portrait'); // Configurar orientación vertical y tamaño A4

            // Descargar el PDF con un nombre de archivo específico
            return $pdf->download('salida_' . $salidaProducto->id . '.pdf');
        } catch (\Exception $e) {
            return redirect()->route('salidas.index')->with('error', 'Ocurrió un error al exportar los datos a PDF: ' . $e->getMessage());
        }
    }

    public function formatoPdf($id)
    {
        try {
            // Cargar la solicitud de dependencia
            $salida = SalidaProducto::with('audit.userCreated', 'audit.userUpdated')->findOrFail($id);

            // Obtener los items de salida con sus productos y movimientos
            $itemsSalida = ItemSalida::with(['producto', 'movimiento'])->where('salida_producto_id', $id)->get();

            // Cargar la solicitud de almacén asociada
            $solicitud_almacen = SolicitudAlmacen::with('audit.userCreated', 'audit.userUpdated')
                ->where('salida_producto_id', $salida->id)
                ->firstOrFail();

            // Cargar la solicitud de dependencia asociada
            $solicitud_dependencia = SolicitudDependencia::with('audit.userCreated', 'audit.userUpdated')
                ->where('id', $solicitud_almacen->solicitud_dependencia_id)
                ->firstOrFail();

            // Cargar la solicitud de unidad asociada
            $solicitud_unidad = SolicitudUnidad::with('audit.userCreated', 'audit.userUpdated')
                ->where('id', $solicitud_dependencia->solicitud_unidad_id)
                ->firstOrFail();

            $totalGeneral = 0;
            foreach ($salida->itemsSalida as $itemSalida) {
                $totalGeneral += $itemSalida->costo_total;
            }

            $pdf = PDF::loadView('salidas.formato_pdf', [
                'salida' => $salida,
                'itemsSalida' => $itemsSalida,
                'solicitud_dependencia' => $solicitud_dependencia,
                'solicitud_almacen' => $solicitud_almacen,
                'solicitud_unidad' => $solicitud_unidad,
                'totalGeneral'=> $totalGeneral,
            ]);

            // Descargar el PDF
            return $pdf->download('Comprobante_salida_' . $solicitud_almacen->id . '.pdf');
        } catch (\Exception $e) {
            return redirect()->route('salidas.index')->with('error', 'Ocurrió un error al exportar los datos a PDF: ' . $e->getMessage());
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
            return redirect()->route('salidas.index')->with('error', 'Ocurrió un error al exportar los datos: ' . $e->getMessage());
        }
    }

    public function exportToExcel()
    {
        try {
            // Obtener los items de entrada con sus productos y movimientos
            $itemsSalida = ItemSalida::with(['producto', 'movimiento', 'salidaproducto', 'salidaproducto.audit.usercreated', 'salidaproducto.audit.userupdated'])->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A1', 'ID Salida');
            $sheet->setCellValue('B1', 'ID');
            $sheet->setCellValue('C1', 'Usuario');
            $sheet->setCellValue('D1', 'Producto');
            $sheet->setCellValue('E1', 'Habia');
            $sheet->setCellValue('F1', 'Salió');
            $sheet->setCellValue('G1', 'Precio U.');
            $sheet->setCellValue('H1', 'Total');
            $sheet->setCellValue('I1', 'Fecha');

            $row = 2;
            foreach ($itemsSalida as $itemSalida) {
                $sheet->setCellValue('A' . $row, $itemSalida->salidaproducto->id);
                $sheet->setCellValue('B' . $row, $itemSalida->id);
                $sheet->setCellValue('C' . $row, $itemSalida->salidaproducto->audit ? ($itemSalida->salidaproducto->audit->usercreated ? $itemSalida->salidaproducto->audit->usercreated->email : 'N/A') : 'N/A');
                $sheet->setCellValue('D' . $row, $itemSalida->producto->nombre);
                $sheet->setCellValue('E' . $row, $itemSalida->movimiento->stock_anterior);
                $sheet->setCellValue('F' . $row, $itemSalida->cantidad);
                $sheet->setCellValue('G' . $row, $itemSalida->p_unitario);
                $sheet->setCellValue('H' . $row, $itemSalida->costo_total);
                $sheet->setCellValue('I' . $row, $itemSalida->created_at);
                $row++;
            }

            foreach (range('A', 'I') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $writer = new Xlsx($spreadsheet);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="SalidaProductos.xlsx"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
        } catch (\Exception $e) {
            return redirect()->route('salidas.index')->with('error', 'Ocurrió un error al exportar los datos a Excel: ' . $e->getMessage());
        }
    }

    public function exportToPdf()
    {
        try {
            $itemsSalida = ItemSalida::with(['producto', 'movimiento', 'salidaproducto', 'salidaproducto.audit.usercreated', 'salidaproducto.audit.userupdated'])->get();

            // Cargar la vista 'personas.pdf' con los datos
            $pdf = PDF::loadView('salidas.pdf', compact('itemsSalida'));

            // Personalizar la configuración del documento PDF
            $pdf->setPaper('A4', 'landscape'); // Configurar orientación horizontal y tamaño A4

            // Descargar el PDF con un nombre de archivo específico
            return $pdf->download('salidas.pdf');
        } catch (\Exception $e) {
            return redirect()->route('salidas.index')->with('error', 'Ocurrió un error al exportar los datos a PDF: ' . $e->getMessage());
        }
    }

    public function exportToWord()
    {
        try {
            $itemsSalida = ItemSalida::with(['producto', 'movimiento', 'salidaproducto', 'salidaproducto.audit.usercreated', 'salidaproducto.audit.userupdated'])->get();

            $phpWord = new PhpWord();

            // Ajustar la sección actual para orientación horizontal
            $section = $phpWord->addSection([
                'orientation' => 'landscape', // Orientación horizontal
            ]);

            // Estilo para encabezados en negrita
            $headerStyle = ['bold' => true];

            $section->addText('Todos los Ingresos', ['bold' => true, 'size' => 16]);

            // Configurar tabla con anchos relativos
            $table = $section->addTable([
                'borderSize' => 6,
                'borderColor' => '000000',
                'cellMargin' => 80,
                'width' => 100 * 50, // Ancho de la tabla en puntos
            ]);

            // Encabezados de tabla en negrita y centrados
            $table->addRow();
            $table->addCell(1000)->addText('ID Salida', $headerStyle);
            $table->addCell(1000)->addText('ID', $headerStyle);
            $table->addCell(2000)->addText('Usuario', $headerStyle);
            $table->addCell(2000)->addText('Producto', $headerStyle);
            $table->addCell(2000)->addText('Habia', $headerStyle);
            $table->addCell(2000)->addText('Salió', $headerStyle);
            $table->addCell(2000)->addText('Precio U.', $headerStyle);
            $table->addCell(2000)->addText('Total', $headerStyle);
            $table->addCell(2000)->addText('Fecha', $headerStyle);

            foreach ($itemsSalida as $itemSalida) {
                $table->addRow();
                $table->addCell(1000)->addText($itemSalida->salidaproducto->id);
                $table->addCell(1000)->addText($itemSalida->id);
                $table->addCell(3000)->addText($itemSalida->salidaproducto->audit ? ($itemSalida->salidaproducto->audit->usercreated ? $itemSalida->salidaproducto->audit->usercreated->email : 'N/A') : 'N/A');
                $table->addCell(2000)->addText($itemSalida->producto->nombre);
                $table->addCell(2000)->addText($itemSalida->movimiento->stock_anterior);
                $table->addCell(2000)->addText($itemSalida->cantidad);
                $table->addCell(2000)->addText($itemSalida->p_unitario);
                $table->addCell(2000)->addText($itemSalida->costo_total);
                $table->addCell(2000)->addText($itemSalida->created_at);
            }

            // Crear el objeto de escritura para Word
            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');

            // Configurar cabeceras para la descarga del archivo
            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            header('Content-Disposition: attachment;filename="SalidaProductos.docx"');
            header('Cache-Control: max-age=0');

            // Guardar el documento en la salida
            $objWriter->save('php://output');
        } catch (\Exception $e) {
            return redirect()->route('salidas.index')->with('error', 'Ocurrió un error al exportar los datos a Word: ' . $e->getMessage());
        }
    }
}
