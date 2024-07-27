<?php

namespace App\Http\Controllers;
//
use App\Models\Dependencia;
use App\Models\EstadoDependencia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

//Exportar excel
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

////Exportar en pdf
use Barryvdh\DomPDF\Facade\Pdf;

//Exportar en Word
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;


class DependenciaController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:lista-dependencia|ver-dependencia|crear-dependencia|editar-dependencia|borrar-dependencia', ['only' => ['index']]);
        $this->middleware('permission:crear-dependencia', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-dependencia', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-dependencia', ['only' => ['destroy']]);
        $this->middleware('permission:ver-dependencia', ['only' => ['show']]);
    }

    public function index()
    {
        try {
            $dependencias = Dependencia::all();
            $estadosDependencia = EstadoDependencia::all();
            return view('dependencias.index', compact('dependencias', 'estadosDependencia'));
        } catch (\Exception $e) {
            return redirect()->route('categoriaproductos.index')->with('error', 'Ocurrió un error al cargar los datos: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $estadosDependencia = EstadoDependencia::all();

            return view('dependencias.create', compact('estadosDependencia'));
        } catch (\Exception $e) {
            return redirect()->route('dependencias.index')->with('error', 'Ocurrió un error al cargar la vista de creación: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            //mensajes personalizados
            $messages = [
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.unique' => 'El nombre ya esta en uso.',
                'nombre.min' => 'El nombre debe tener min 4 caracteres.',
            ];
            //Validacion
            $validatedData = $request->validate([
                'nombre' => [
                    'required',
                    'string',
                    'max:100',
                    'min:4',
                    Rule::unique('dependencia'), // Validación de unicidad
                ],
            ], $messages);

            $dependencia = Dependencia::create($validatedData);

            return redirect()->route('dependencias.index')->with('success', 'Dependencia creada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('dependencias.index')->with('error', 'Ocurrió un error al crear la dependencia: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $dependencia = Dependencia::with('estado', 'audit.usercreated', 'audit.userupdated')->find($id);
            return response()->json($dependencia);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocurrió un error al mostrar la dependencia: ' . $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        try {
            $dependencia = Dependencia::findOrFail($id);
            $estadosDependencia = EstadoDependencia::all();
            return view('dependencias.editar', compact('dependencia', 'estadosDependencia'));
        } catch (\Exception $e) {
            return redirect()->route('dependencias.index')->with('error', 'Ocurrió un error al cargar la vista de edición: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            //mensajes personalizados
            $messages = [
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.min' => 'El nombre debe tener min 4 caracteres.',
            ];
            //Validacion
            $validatedData = $request->validate([
                'nombre' => [
                    'required',
                    'string',
                    'max:100',
                    'min:4',
                ],
                'estado_id' => 'required|integer',
            ], $messages);

            $dependencia = Dependencia::findOrFail($id);
            $dependencia->update($validatedData);

            return redirect()->route('dependencias.index')->with('success', 'Dependencia actualizada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('dependencias.index')->with('error', 'Ocurrió un error al actualizar la dependencia: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {

        try {
            $dependencia = Dependencia::findOrFail($id);
            $dependencia->delete();

            return redirect()->route('dependencias.index')->with('success', 'Dependencia eliminada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('dependencias.index')->with('error', 'Ocurrió un error al eliminar la depedencia.' . $e->getMessage());
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
            return redirect()->route('dependencias.index')->with('error', 'Ocurrió un error al exportar los datos: ' . $e->getMessage());
        }
    }

    public function exportToExcel()
    {
        try {
            $dependencias = Dependencia::with('estado', 'audit.usercreated', 'audit.userupdated')->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A1', 'ID');
            $sheet->setCellValue('B1', 'Nombre');
            $sheet->setCellValue('C1', 'Estado');
            $sheet->setCellValue('D1', 'Usuario que Creó');
            $sheet->setCellValue('E1', 'Usuario que Actualizó');
            $sheet->setCellValue('F1', 'Fecha de Creación');
            $sheet->setCellValue('G1', 'Fecha de Actualización');

            $row = 2;
            foreach ($dependencias as $dependencia) {
                $sheet->setCellValue('A' . $row, $dependencia->id);
                $sheet->setCellValue('B' . $row, $dependencia->nombre);
                $sheet->setCellValue('C' . $row, $dependencia->estado->descripcion);
                $sheet->setCellValue('D' . $row, $dependencia->audit ? ($dependencia->audit->usercreated ? $dependencia->audit->usercreated->email : 'N/A') : 'N/A');
                $sheet->setCellValue('E' . $row, $dependencia->audit ? ($dependencia->audit->userupdated ? $dependencia->audit->userupdated->email : 'N/A') : 'N/A');
                $sheet->setCellValue('F' . $row, $dependencia->created_at);
                $sheet->setCellValue('G' . $row, $dependencia->updated_at);
                $row++;
            }

            foreach (range('A', 'G') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $writer = new Xlsx($spreadsheet);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="dependencias.xlsx"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
        } catch (\Exception $e) {
            return redirect()->route('dependencias.index')->with('error', 'Ocurrió un error al exportar los datos a Excel: ' . $e->getMessage());
        }
    }

    public function exportToPdf()
    {
        try {
            $dependencias = Dependencia::with('estado', 'audit.usercreated', 'audit.userupdated')->get();

            // Cargar la vista 'personas.pdf' con los datos
            $pdf = PDF::loadView('dependencias.pdf', compact('dependencias'));

            // Personalizar la configuración del documento PDF
            $pdf->setPaper('A4', 'landscape'); // Configurar orientación horizontal y tamaño A4

            // Descargar el PDF con un nombre de archivo específico
            return $pdf->download('dependencias.pdf');
        } catch (\Exception $e) {
            return redirect()->route('dependencias.index')->with('error', 'Ocurrió un error al exportar los datos a PDF: ' . $e->getMessage());
        }
    }

    public function exportToWord()
    {
        try {
            $dependencias = Dependencia::with('estado', 'audit.usercreated', 'audit.userupdated')->get();

            $phpWord = new PhpWord();

            // Ajustar la sección actual para orientación horizontal
            $section = $phpWord->addSection([
                'orientation' => 'landscape', // Orientación horizontal
            ]);

            // Estilo para encabezados en negrita
            $headerStyle = ['bold' => true];

            $section->addText('Lista de Dependencias', ['bold' => true, 'size' => 16]);

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
            $table->addCell(2000)->addText('Estado', $headerStyle);
            $table->addCell(3000)->addText('Usuario que Creó', $headerStyle);
            $table->addCell(3000)->addText('Usuario que Actualizó', $headerStyle);
            $table->addCell(2000)->addText('Fecha de Creación', $headerStyle);
            $table->addCell(2000)->addText('Fecha de Actualización', $headerStyle);

            foreach ($dependencias as $dependencia) {
                $table->addRow();
                $table->addCell(1000)->addText($dependencia->id);
                $table->addCell(2000)->addText($dependencia->nombre);
                $table->addCell(2000)->addText($dependencia->estado->descripcion);
                $table->addCell(3000)->addText($dependencia->audit ? ($dependencia->audit->usercreated ? $dependencia->audit->usercreated->email : 'N/A') : 'N/A');
                $table->addCell(3000)->addText($dependencia->audit ? ($dependencia->audit->userupdated ? $dependencia->audit->userupdated->email : 'N/A') : 'N/A');
                $table->addCell(2000)->addText($dependencia->created_at);
                $table->addCell(2000)->addText($dependencia->updated_at);
            }

            // Crear el objeto de escritura para Word
            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');

            // Configurar cabeceras para la descarga del archivo
            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            header('Content-Disposition: attachment;filename="dependencias.docx"');
            header('Cache-Control: max-age=0');

            // Guardar el documento en la salida
            $objWriter->save('php://output');
        } catch (\Exception $e) {
            return redirect()->route('dependencias.index')->with('error', 'Ocurrió un error al exportar los datos a Word: ' . $e->getMessage());
        }
    }
}
