<?php

namespace App\Http\Controllers;

use App\Models\EntradaProducto;
use App\Models\ItemEntrada;
use App\Models\ItemSalida;
use App\Models\Movimiento;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\SalidaProducto;
use Illuminate\Http\Request;

//Exportar excel
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

//Exportar en pdf
use Barryvdh\DomPDF\Facade\Pdf;

//Exportar en Word
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;


class MovimientoController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:lista-movimiento|ver-movimientoentrada|ver-movimientosalida', ['only' => ['index']]);
        $this->middleware('permission:ver-movimientoentrada', ['only' => ['showEntrada']]);
        $this->middleware('permission:ver-movimientosalida', ['only' => ['showSalida']]);
    }

    public function index()
    {
        try {
            $entradas = EntradaProducto::all();
            $proveedores = Proveedor::all();
            $salidas = SalidaProducto::all();
            $movimientos = Movimiento::all();
            return view('movimientos.index', compact('movimientos', 'entradas', 'proveedores', 'salidas'));
        } catch (\Exception $e) {
            return redirect()->route('movimientos.index')->with('error', 'Ocurrió un error al cargar los datos: ' . $e->getMessage());
        }
    }

    public function kardexPdf($id)
    {
        try {
            
            $movimientos = Movimiento::with('audit.usercreated', 'audit.userupdated', 'producto')->where('producto_id',$id)->get();

            $producto = Producto::with('audit.usercreated', 'audit.userupdated', 'proveedor')->find($id);

            // Cargar la vista 'personas.pdf' con los datos
            $pdf = PDF::loadView('movimientos.formato_kardex', compact('movimientos','producto'));

            // Personalizar la configuración del documento PDF
            $pdf->setPaper('A4', 'landscape'); // Configurar orientación horizontal y tamaño A4

            // Descargar el PDF con un nombre de archivo específico
            return $pdf->download('formato_kardex.pdf');
        } catch (\Exception $e) {
            return redirect()->route('movimientos.index')->with('error', 'Ocurrió un error al exportar los datos a PDF: ' . $e->getMessage());
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
            return redirect()->route('movimientos.index')->with('error', 'Ocurrió un error al exportar los datos: ' . $e->getMessage());
        }
    }

    public function exportToExcel()
    {
        try {
            $movimientos = Movimiento::with('audit.usercreated', 'audit.userupdated', 'producto')->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A1', 'ID');
            $sheet->setCellValue('B1', 'Usuario');
            $sheet->setCellValue('C1', 'Producto');
            $sheet->setCellValue('D1', 'Habia');
            $sheet->setCellValue('E1', 'Tipo');
            $sheet->setCellValue('F1', 'Cantidad');
            $sheet->setCellValue('G1', 'Fecha');

            $row = 2;
            foreach ($movimientos as $movimiento) {
                $sheet->setCellValue('A' . $row, $movimiento->id);
                $sheet->setCellValue('B' . $row, $movimiento->audit ? ($movimiento->audit->usercreated ? $movimiento->audit->usercreated->email : 'N/A') : 'N/A');
                $sheet->setCellValue('C' . $row, $movimiento->producto->nombre);
                $sheet->setCellValue('D' . $row, $movimiento->stock_anterior);
                $sheet->setCellValue('E' . $row, $movimiento->tipo);
                $sheet->setCellValue('F' . $row, $movimiento->cantidad);
                $sheet->setCellValue('G' . $row, $movimiento->created_at);
                $row++;
            }

            foreach (range('A', 'G') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $writer = new Xlsx($spreadsheet);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="movimientos.xlsx"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
        } catch (\Exception $e) {
            return redirect()->route('movimientos.index')->with('error', 'Ocurrió un error al exportar los datos a Excel: ' . $e->getMessage());
        }
    }

    public function exportToPdf()
    {
        try {
            $movimientos = Movimiento::with('audit.usercreated', 'audit.userupdated', 'producto')->get();

            // Cargar la vista 'personas.pdf' con los datos
            $pdf = PDF::loadView('movimientos.pdf', compact('movimientos'));

            // Personalizar la configuración del documento PDF
            $pdf->setPaper('A4', 'landscape'); // Configurar orientación horizontal y tamaño A4

            // Descargar el PDF con un nombre de archivo específico
            return $pdf->download('movimientos.pdf');
        } catch (\Exception $e) {
            return redirect()->route('movimientos.index')->with('error', 'Ocurrió un error al exportar los datos a PDF: ' . $e->getMessage());
        }
    }

    public function exportToWord()
    {
        try {
            $movimientos = Movimiento::with('audit.usercreated', 'audit.userupdated', 'producto')->get();

            $phpWord = new PhpWord();

            // Ajustar la sección actual para orientación horizontal
            $section = $phpWord->addSection([
                'orientation' => 'landscape', // Orientación horizontal
            ]);

            // Estilo para encabezados en negrita
            $headerStyle = ['bold' => true];

            $section->addText('Todos los Movimientos', ['bold' => true, 'size' => 16]);

            // Configurar tabla con anchos relativos
            $table = $section->addTable([
                'borderSize' => 6,
                'borderColor' => '000000',
                'cellMargin' => 80,
                'width' => 100 * 50, // Ancho de la tabla en puntos
            ]);

            // Encabezados de tabla en negrita y centrados
            $table->addRow();
            $table->addCell(1000)->addText('ID', $headerStyle);
            $table->addCell(2000)->addText('Usuario', $headerStyle);
            $table->addCell(2000)->addText('Producto', $headerStyle);
            $table->addCell(2000)->addText('Habia', $headerStyle);
            $table->addCell(2000)->addText('Tipo', $headerStyle);
            $table->addCell(2000)->addText('Cantidad', $headerStyle);
            $table->addCell(2000)->addText('Fecha', $headerStyle);

            foreach ($movimientos as $movimiento) {
                $table->addRow();
                $table->addCell(1000)->addText($movimiento->id);
                $table->addCell(3000)->addText($movimiento->audit ? ($movimiento->audit->usercreated ? $movimiento->audit->usercreated->email : 'N/A') : 'N/A');
                $table->addCell(2000)->addText($movimiento->producto->nombre);
                $table->addCell(2000)->addText($movimiento->stock_anterior);
                $table->addCell(2000)->addText($movimiento->tipo);
                $table->addCell(2000)->addText($movimiento->cantidad);
                $table->addCell(2000)->addText($movimiento->created_at);
            }

            // Crear el objeto de escritura para Word
            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');

            // Configurar cabeceras para la descarga del archivo
            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            header('Content-Disposition: attachment;filename="movimientos.docx"');
            header('Cache-Control: max-age=0');

            // Guardar el documento en la salida
            $objWriter->save('php://output');
        } catch (\Exception $e) {
            return redirect()->route('movimientos.index')->with('error', 'Ocurrió un error al exportar los datos a Word: ' . $e->getMessage());
        }
    }
}
