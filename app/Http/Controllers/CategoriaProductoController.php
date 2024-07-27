<?php

namespace App\Http\Controllers;

use App\Models\CategoriaProducto;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

//Exportar excel
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

//Exportar en pdf
use Barryvdh\DomPDF\Facade\Pdf;

//Exportar en Word
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class CategoriaProductoController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:lista-categoriaproducto|ver-categoriaproducto|crear-categoriaproducto|editar-categoriaproducto|borrar-categoriaproducto', ['only' => ['index']]);
        $this->middleware('permission:crear-categoriaproducto', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-categoriaproducto', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-categoriaproducto', ['only' => ['destroy']]);
        $this->middleware('permission:ver-categoriaproducto', ['only' => ['show']]);
    }

    public function index()
    {
        try {
            $categoriaProductos = CategoriaProducto::all();
            return view('categoriaproductos.index', compact('categoriaProductos'));
        } catch (\Exception $e) {
            return redirect()->route('categoriaproductos.index')->with('error', 'Ocurrió un error al cargar los datos: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            return view('categoriaproductos.create');
        } catch (\Exception $e) {
            return redirect()->route('categoriaproductos.index')->with('error', 'Ocurrió un error al cargar la vista de creación: ' . $e->getMessage());
        }
    }


    public function store(Request $request)
    {
        try {
            //mensajes personalizados
            $messages = [
                'descripcion.required' => 'El nombre es obligatorio.',
                'descripcion.unique' => 'El nombre ya esta en uso.',
                'descripcion.min' => 'El nombre debe tener min 5 caracteres.',
            ];
            //Validacion
            $validatedData = $request->validate([
                'descripcion' => [
                    'required',
                    'string',
                    'max:200',
                    'min:5',
                    Rule::unique('categoria_producto'), // Validación de unicidad
                ],
            ], $messages);

            $categoriaProducto = CategoriaProducto::create($validatedData);

            // Redirigir con un mensaje de éxito
            return redirect()->route('categoriaproductos.index')->with('success', 'Categoría de producto creada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('categoriaproductos.index')->with('error', 'Ocurrió un error al crear la categoría: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $categoriaProducto = CategoriaProducto::with('audit.usercreated', 'audit.userupdated')->find($id);
            return response()->json($categoriaProducto);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocurrió un error al mostrar la categoría: ' . $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        try {
            $categoriaProducto = CategoriaProducto::findOrFail($id);
            return view('categoriaproductos.editar', compact('categoriaProducto'));
        } catch (\Exception $e) {
            return redirect()->route('categoriaproductos.index')->with('error', 'Ocurrió un error al cargar la vista de edición: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $messages = [
                'descripcion.required' => 'El nombre es obligatorio.',
                'descripcion.min' => 'El nombre debe tener min 5 caracteres.',
            ];
            //Validacion
            $validatedData = $request->validate([
                'descripcion' => [
                    'required',
                    'string',
                    'max:200',
                    'min:5',
                ],
            ], $messages);

            $categoriaProducto = CategoriaProducto::findOrFail($id);
            $categoriaProducto->update($validatedData);


            return redirect()->route('categoriaproductos.index')->with('success', 'Categoría de producto actualizada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('categoriaproductos.index')->with('error', 'Ocurrió un error al actualizar la categoría: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $categoria = CategoriaProducto::findOrFail($id);
            $categoria->delete();
            return redirect()->route('categoriaproductos.index')->with('success', 'Categoría eliminada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('categoriaproductos.index')->with('error', 'Ocurrió un error al eliminar la categoría.' . $e->getMessage());
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
            return redirect()->route('categoriaproductos.index')->with('error', 'Ocurrió un error al exportar los datos: ' . $e->getMessage());
        }
    }

    public function exportToExcel()
    {
        try {
            $categorias = CategoriaProducto::with('audit.usercreated', 'audit.userupdated')->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A1', 'ID');
            $sheet->setCellValue('B1', 'Descripción');
            $sheet->setCellValue('C1', 'Usuario que Creó');
            $sheet->setCellValue('D1', 'Usuario que Actualizó');
            $sheet->setCellValue('E1', 'Fecha de Creación');
            $sheet->setCellValue('F1', 'Fecha de Actualización');

            $row = 2;
            foreach ($categorias as $categoria) {
                $sheet->setCellValue('A' . $row, $categoria->id);
                $sheet->setCellValue('B' . $row, $categoria->descripcion);
                $sheet->setCellValue('C' . $row, $categoria->audit ? ($categoria->audit->usercreated ? $categoria->audit->usercreated->email : 'N/A') : 'N/A');
                $sheet->setCellValue('D' . $row, $categoria->audit ? ($categoria->audit->userupdated ? $categoria->audit->userupdated->email : 'N/A') : 'N/A');
                $sheet->setCellValue('E' . $row, $categoria->created_at);
                $sheet->setCellValue('F' . $row, $categoria->updated_at);
                $row++;
            }

            foreach (range('A', 'F') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $writer = new Xlsx($spreadsheet);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="categorias_productos.xlsx"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
        } catch (\Exception $e) {
            return redirect()->route('categoriaproductos.index')->with('error', 'Ocurrió un error al exportar los datos a Excel: ' . $e->getMessage());
        }
    }

    public function exportToPdf()
    {
        try {
            $categoriaproductos = CategoriaProducto::with('audit.usercreated', 'audit.userupdated')->get();

            // Cargar la vista 'personas.pdf' con los datos
            $pdf = PDF::loadView('categoriaproductos.pdf', compact('categoriaproductos'));

            // Personalizar la configuración del documento PDF
            $pdf->setPaper('A4', 'landscape'); // Configurar orientación horizontal y tamaño A4

            // Descargar el PDF con un nombre de archivo específico
            return $pdf->download('categoriaproductos.pdf');
        } catch (\Exception $e) {
            return redirect()->route('categoriaproductos.index')->with('error', 'Ocurrió un error al exportar los datos a PDF: ' . $e->getMessage());
        }
    }

    public function exportToWord()
    {
        try {
            $categorias = CategoriaProducto::with('audit.usercreated', 'audit.userupdated')->get();

            $phpWord = new PhpWord();

            // Ajustar la sección actual para orientación horizontal
            $section = $phpWord->addSection([
                'orientation' => 'landscape', // Orientación horizontal
            ]);

            // Estilo para encabezados en negrita
            $headerStyle = ['bold' => true];

            $section->addText('Lista de Categorías', ['bold' => true, 'size' => 16]);

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
            $table->addCell(2000)->addText('Nombre', $headerStyle);
            $table->addCell(3000)->addText('Usuario que Creó', $headerStyle);
            $table->addCell(3000)->addText('Usuario que Actualizó', $headerStyle);
            $table->addCell(2000)->addText('Fecha de Creación', $headerStyle);
            $table->addCell(2000)->addText('Fecha de Actualización', $headerStyle);

            foreach ($categorias as $categoria) {
                $table->addRow();
                $table->addCell(1000)->addText($categoria->id);
                $table->addCell(2000)->addText($categoria->descripcion);
                $table->addCell(3000)->addText($categoria->audit ? ($categoria->audit->usercreated ? $categoria->audit->usercreated->email : 'N/A') : 'N/A');
                $table->addCell(3000)->addText($categoria->audit ? ($categoria->audit->userupdated ? $categoria->audit->userupdated->email : 'N/A') : 'N/A');
                $table->addCell(2000)->addText($categoria->created_at);
                $table->addCell(2000)->addText($categoria->updated_at);
            }

            // Crear el objeto de escritura para Word
            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');

            // Configurar cabeceras para la descarga del archivo
            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            header('Content-Disposition: attachment;filename="categorias.docx"');
            header('Cache-Control: max-age=0');

            // Guardar el documento en la salida
            $objWriter->save('php://output');
        } catch (\Exception $e) {
            return redirect()->route('categoriaproductos.index')->with('error', 'Ocurrió un error al exportar los datos a Word: ' . $e->getMessage());
        }
    }
}
