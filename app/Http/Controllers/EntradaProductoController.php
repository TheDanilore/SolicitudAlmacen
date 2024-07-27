<?php

namespace App\Http\Controllers;

use App\Models\EntradaProducto;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\ItemEntrada;
use App\Models\Movimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


//Exportar excel
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

//Exportar en pdf
use Barryvdh\DomPDF\Facade\Pdf;

//Exportar en Word
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class EntradaProductoController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:lista-entradaproducto|crear-entradaproducto|detalle-entradaproducto', ['only' => ['index']]);
        $this->middleware('permission:crear-entradaproducto', ['only' => ['create', 'store']]);
        $this->middleware('permission:detalle-entradaproducto', ['only' => ['show']]);
    }

    public function index()
    {
        try {
            $entradas = EntradaProducto::all();
            $proveedores = Proveedor::all();
            return view('entradas.index', compact('entradas', 'proveedores'));
        } catch (\Exception $e) {
            return redirect()->route('entradas.index')->with('error', 'Ocurrió un error al cargar los datos: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $ultimoGuia = EntradaProducto::latest('guia_remision')->first();
            $nuevoGuia = $ultimoGuia ? 'guia-' . (intval(str_replace('guia-', '', $ultimoGuia->guia_remision)) + 1) : 'guia-1';
            $proveedores = Proveedor::where('estado_proveedor_id', '1')->get();
            $productos = Producto::where('estado_producto_id', '1')->get();
            return view('entradas.create', compact('proveedores', 'productos', 'nuevoGuia'));
        } catch (\Exception $e) {
            return redirect()->route('entradas.index')->with('error', 'Ocurrió un error al cargar la vista de creación: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            //mensajes personalizados
            $messages = [
                'guia_remision.required' => 'La guia de remision es obligatorio.',
                'guia_remision.unique' => 'La guia de remision ya esta en uso.',
                'guia_remision.min' => 'La guia de remision debe tener min 4 caracteres.',
                'proveedor_id.required' => 'Elegir el proveedor es obligatorio.',
                'productos.required' => 'Elegir el producto es obligatorio.',
                'tipo_entrada.required' => 'Elegir el tipo de entrada es obligatorio.',
                'procedencia.required' => 'La procendencia es obligatorio.',
            ];
            // Validar los datos del formulario
            $validatedData = $request->validate([
                'guia_remision' => [
                    'required',
                    'string',
                    'max:20',
                    'min:4',
                    Rule::unique('entrada_producto'), // Validación de unicidad
                ],
                'proveedor_id' => 'required|integer|exists:proveedor,id',
                'productos' => 'required|array',
                'productos.*.producto_id' => 'required|integer|exists:productos,id',
                'productos.*.cantidad' => 'required|integer|min:1',
                'productos.*.precio_unitario' => 'required|numeric|min:0.01',
                'tipo_entrada'=> 'required',
                'procedencia'=> 'required|max:100|min:4',
            ], $messages);

            $entrada = new EntradaProducto();
            $entrada->guia_remision = $validatedData['guia_remision'];
            $entrada->proveedor_id = $validatedData['proveedor_id'];
            $entrada->tipo_entrada = $validatedData['tipo_entrada'];
            $entrada->procedencia = $validatedData['procedencia'];
            $entrada->save();

            $productos = $validatedData['productos'];
            foreach ($productos as $productoData) {
                $itemEntrada = new ItemEntrada;
                $itemEntrada->entrada_producto_id = $entrada->id;
                $itemEntrada->productos_id = $productoData['producto_id'];
                $itemEntrada->cantidad = $productoData['cantidad'];
                $itemEntrada->p_unitario = $productoData['precio_unitario'];
                $itemEntrada->igv = 0.18;
                $itemEntrada->costo_total = $productoData['cantidad'] * $productoData['precio_unitario'] * (1 + $itemEntrada->igv);
                $itemEntrada->save();

                $producto = Producto::find($productoData['producto_id']);
                $stock_anterior = $producto->cantidad; //guardamos el stock anterior

                if ($producto->proveedor!=null) {
                    $producto->proveedor = $validatedData['proveedor_id'];
                }
                
                $producto->cantidad += $productoData['cantidad'];
                $producto->save();

                Movimiento::create([
                    'producto_id' => $producto->id,
                    'tipo' => 'entrada',
                    'item_entrada_id' => $itemEntrada->id, // Relacionamos con ItemEntrada
                    'item_salida_id' => 0,
                    'stock_anterior' => $stock_anterior,
                    'cantidad' => $productoData['cantidad'],
                ]);
            }
            return redirect()->route('entradas.index')->with('success', 'Entrada creada exitosamente!');
        } catch (\Exception $e) {
            return redirect()->route('entradas.index')->with('error', 'Ocurrió un error al crear el ingreso: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            // Obtener la entrada de producto por su ID
            $entrada = EntradaProducto::with(['proveedor'])->findOrFail($id);

            // Obtener los items de entrada con sus productos y movimientos
            $itemsEntrada = ItemEntrada::with(['producto', 'movimiento'])->where('entrada_producto_id', $id)->get();

            return view('entradas.show', compact('entrada', 'itemsEntrada'));
        } catch (\Exception $e) {
            return redirect()->route('entradas.index')->with('error', 'Ocurrió un error para ver.' . $e->getMessage());
        }
    }

    public function exportSingleToPdf($id)
    {
        try {
            // Buscar la entrada específica con sus relaciones
            $entradaProducto = EntradaProducto::with(['itemsEntrada.producto', 'itemsEntrada.movimiento', 'audit.userCreated', 'audit.userUpdated'])
                ->findOrFail($id);
    
            // Cargar la vista 'pdf_single' con los datos
            $pdf = PDF::loadView('entradas.pdf_single', compact('entradaProducto'));
    
            // Personalizar la configuración del documento PDF
            $pdf->setPaper('A4', 'portrait'); // Configurar orientación vertical y tamaño A4
    
            // Descargar el PDF con un nombre de archivo específico
            return $pdf->download('entrada_' . $entradaProducto->id . '.pdf');
        } catch (\Exception $e) {
            return redirect()->route('entradas.index')->with('error', 'Ocurrió un error al exportar los datos a PDF: ' . $e->getMessage());
        }
    }

    public function formatoPdf($id)
    {
        try {
            // Buscar la entrada específica con sus relaciones
            $entradaProducto = EntradaProducto::with(['itemsEntrada.producto', 'itemsEntrada.movimiento', 'audit.userCreated', 'audit.userUpdated'])
                ->findOrFail($id);
    
            // Cargar la vista 'pdf_single' con los datos
            $pdf = PDF::loadView('entradas.formato_pdf', compact('entradaProducto'));
    
            // Personalizar la configuración del documento PDF
            $pdf->setPaper('A4', 'portrait'); // Configurar orientación vertical y tamaño A4
    
            // Descargar el PDF con un nombre de archivo específico
            return $pdf->download('entrada_formato_' . $entradaProducto->id . '.pdf');
        } catch (\Exception $e) {
            return redirect()->route('entradas.index')->with('error', 'Ocurrió un error al exportar los datos a PDF: ' . $e->getMessage());
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
            return redirect()->route('entradas.index')->with('error', 'Ocurrió un error al exportar los datos: ' . $e->getMessage());
        }
    }

    public function exportToExcel()
    {
        try {
            // Obtener los items de entrada con sus productos y movimientos
            $itemsEntrada = ItemEntrada::with(['producto', 'movimiento', 'entradaproducto', 'entradaproducto.audit.usercreated', 'entradaproducto.audit.userupdated'])->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();


            $sheet->setCellValue('A1', 'ID Entrada');
            $sheet->setCellValue('B1', 'ID');
            $sheet->setCellValue('C1', 'Usuario');
            $sheet->setCellValue('D1', 'Guia Remision');
            $sheet->setCellValue('E1', 'Proveedor');
            $sheet->setCellValue('F1', 'Producto');
            $sheet->setCellValue('G1', 'Habia');
            $sheet->setCellValue('H1', 'Ingreso');
            $sheet->setCellValue('I1', 'Precio U.');
            $sheet->setCellValue('J1', 'IGV');
            $sheet->setCellValue('K1', 'Total');
            $sheet->setCellValue('L1', 'Fecha');

            $row = 2;
            foreach ($itemsEntrada as $itemEntrada) {
                $sheet->setCellValue('A' . $row, $itemEntrada->entradaproducto->id);
                $sheet->setCellValue('B' . $row, $itemEntrada->id);
                $sheet->setCellValue('C' . $row, $itemEntrada->entradaproducto->audit ? ($itemEntrada->entradaproducto->audit->usercreated ? $itemEntrada->entradaproducto->audit->usercreated->email : 'N/A') : 'N/A');
                $sheet->setCellValue('D' . $row, $itemEntrada->entradaproducto->guia_remision);
                $sheet->setCellValue('E' . $row, $itemEntrada->entradaproducto->proveedor->razon_social);
                $sheet->setCellValue('F' . $row, $itemEntrada->producto->nombre);
                $sheet->setCellValue('G' . $row, $itemEntrada->movimiento->stock_anterior);
                $sheet->setCellValue('H' . $row, $itemEntrada->cantidad);
                $sheet->setCellValue('I' . $row, $itemEntrada->p_unitario);
                $sheet->setCellValue('J' . $row, $itemEntrada->igv);
                $sheet->setCellValue('K' . $row, $itemEntrada->costo_total);
                $sheet->setCellValue('L' . $row, $itemEntrada->created_at);
                $row++;
            }

            foreach (range('A', 'L') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $writer = new Xlsx($spreadsheet);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="IngresoProductos.xlsx"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
        } catch (\Exception $e) {
            return redirect()->route('entradas.index')->with('error', 'Ocurrió un error al exportar los datos a Excel: ' . $e->getMessage());
        }
    }

    public function exportToPdf()
    {
        try {
            $itemsEntrada = ItemEntrada::with(['producto', 'movimiento', 'entradaproducto', 'entradaproducto.audit.usercreated', 'entradaproducto.audit.userupdated'])->get();

            // Cargar la vista 'personas.pdf' con los datos
            $pdf = PDF::loadView('entradas.pdf', compact('itemsEntrada'));

            // Personalizar la configuración del documento PDF
            $pdf->setPaper('A4', 'landscape'); // Configurar orientación horizontal y tamaño A4

            // Descargar el PDF con un nombre de archivo específico
            return $pdf->download('entradas.pdf');
        } catch (\Exception $e) {
            return redirect()->route('entradas.index')->with('error', 'Ocurrió un error al exportar los datos a PDF: ' . $e->getMessage());
        }
    }

    public function exportToWord()
    {
        try {
            $itemsEntrada = ItemEntrada::with(['producto', 'movimiento', 'entradaproducto', 'entradaproducto.audit.usercreated', 'entradaproducto.audit.userupdated'])->get();

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
            $table->addCell(1000)->addText('ID Entrada', $headerStyle);
            $table->addCell(1000)->addText('ID', $headerStyle);
            $table->addCell(2000)->addText('Usuario', $headerStyle);
            $table->addCell(2000)->addText('Guia Remision', $headerStyle);
            $table->addCell(2000)->addText('Proveedor', $headerStyle);
            $table->addCell(2000)->addText('Producto', $headerStyle);
            $table->addCell(2000)->addText('Habia', $headerStyle);
            $table->addCell(2000)->addText('Ingreso', $headerStyle);
            $table->addCell(2000)->addText('Precio U.', $headerStyle);
            $table->addCell(2000)->addText('IGV', $headerStyle);
            $table->addCell(2000)->addText('Total', $headerStyle);
            $table->addCell(2000)->addText('Fecha', $headerStyle);

            foreach ($itemsEntrada as $itemEntrada) {
                $table->addRow();
                $table->addCell(1000)->addText($itemEntrada->entradaproducto->id);
                $table->addCell(1000)->addText($itemEntrada->id);
                $table->addCell(3000)->addText($itemEntrada->entradaproducto->audit ? ($itemEntrada->entradaproducto->audit->usercreated ? $itemEntrada->entradaproducto->audit->usercreated->email : 'N/A') : 'N/A');
                $table->addCell(2000)->addText($itemEntrada->entradaproducto->guia_remision);
                $table->addCell(2000)->addText($itemEntrada->entradaproducto->proveedor->razon_social);
                $table->addCell(2000)->addText($itemEntrada->producto->nombre);
                $table->addCell(2000)->addText($itemEntrada->movimiento->stock_anterior);
                $table->addCell(2000)->addText($itemEntrada->cantidad);
                $table->addCell(2000)->addText($itemEntrada->p_unitario);
                $table->addCell(2000)->addText($itemEntrada->igv);
                $table->addCell(2000)->addText($itemEntrada->costo_total);
                $table->addCell(2000)->addText($itemEntrada->created_at);
            }

            // Crear el objeto de escritura para Word
            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');

            // Configurar cabeceras para la descarga del archivo
            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            header('Content-Disposition: attachment;filename="IngresoProductos.docx"');
            header('Cache-Control: max-age=0');

            // Guardar el documento en la salida
            $objWriter->save('php://output');
        } catch (\Exception $e) {
            return redirect()->route('entradas.index')->with('error', 'Ocurrió un error al exportar los datos a Word: ' . $e->getMessage());
        }
    }
}
