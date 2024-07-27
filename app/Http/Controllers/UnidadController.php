<?php

namespace App\Http\Controllers;

use App\Models\Unidad;
use App\Models\Dependencia;
use Illuminate\Http\Request;

//Exportar excel
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

//Exportar en pdf
use Barryvdh\DomPDF\Facade\Pdf;

//Exportar en Word
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class UnidadController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:lista-unidad|ver-unidad|crear-unidad|editar-unidad|borrar-unidad', ['only' => ['index']]);
        $this->middleware('permission:crear-unidad', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-unidad', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-unidad', ['only' => ['destroy']]);
        $this->middleware('permission:ver-unidad', ['only' => ['show']]);
    }

    public function index()
    {
        try {
            $unidades = Unidad::with('usuarios')->get();
            $dependencias = Dependencia::all();
            return view('unidades.index', compact('unidades', 'dependencias'));
        } catch (\Exception $e) {
            return redirect()->route('unidades.index')->with('error', 'Ocurrió un error al cargar los datos: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $dependencias = Dependencia::where('estado_id', '1')->get();;
            return view('unidades.create', compact('dependencias'));
        } catch (\Exception $e) {
            return redirect()->route('unidades.index')->with('error', 'Ocurrió un error al cargar la vista de creación: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            //mensajes personalizados
            $messages = [
                'dependencia_id.required' => 'Elegir la dependencia es obligatorio.',
                'descripcion.min' => 'El nombre debe contener min 5 caracteres.',
                'descripcion.required' => 'El nombre es obligatorio.',
            ];
            $validatedData = $request->validate([
                'dependencia_id' => 'required|integer|exists:dependencia,id',
                'descripcion' => 'required|string|max:100|min:5',
            ], $messages);


            Unidad::create($validatedData);

            return redirect()->route('unidades.index')->with('success', 'Unidad creada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('unidades.index')->with('error', 'Ocurrió un error al crear la Unidad: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $unidad = Unidad::with('dependencia', 'audit.usercreated', 'audit.userupdated')->find($id);
            return response()->json($unidad);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocurrió un error al mostrar la Unidad: ' . $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        try {
            $unidad = Unidad::findOrFail($id);
            $dependencias = Dependencia::where('estado_id', '1')->get();;
            return view('unidades.edit', compact('unidad', 'dependencias'));
        } catch (\Exception $e) {
            return redirect()->route('unidades.index')->with('error', 'Ocurrió un error al cargar la vista de edición: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            //mensajes personalizados
            $messages = [
                'dependencia_id.required' => 'Elegir la dependencia es obligatorio.',
                'descripcion.min' => 'El nombre debe contener min 5 caracteres.',
                'descripcion.required' => 'El nombre es obligatorio.',
            ];
            $validatedData = $request->validate([
                'dependencia_id' => 'required|integer|exists:dependencia,id',
                'descripcion' => 'required|string|max:100|min:5',
            ], $messages);


            Unidad::where('id', $id)->update($validatedData);

            //$unidad->update($validatedData);

            return redirect()->route('unidades.index')->with('success', 'Unidad actualizada exitosamente!');
        } catch (\Exception $e) {
            return redirect()->route('unidades.index')->with('error', 'Ocurrió un error al actualizar la Unidad: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $unidad = Unidad::findOrFail($id);
            $unidad->delete();
            return redirect()->route('unidades.index')->with('success', 'Unidad eliminada exitosamente!');
        } catch (\Exception $e) {
            return redirect()->route('unidades.index')->with('error', 'Ocurrio un error al eliminar la Unidad' . $e->getMessage());
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
            return redirect()->route('unidades.index')->with('error', 'Ocurrió un error al exportar los datos: ' . $e->getMessage());
        }
    }

    public function exportToExcel()
    {
        try {
            $unidades = Unidad::with(['dependencia', 'usuarios', 'audit.usercreated', 'audit.userupdated'])->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A1', 'ID');
            $sheet->setCellValue('B1', 'Dependencia');
            $sheet->setCellValue('C1', 'Descripción');
            $sheet->setCellValue('D1', 'Responsables');
            $sheet->setCellValue('E1', 'Usuario que Creó');
            $sheet->setCellValue('F1', 'Usuario que Actualizó');
            $sheet->setCellValue('G1', 'Fecha de Creación');
            $sheet->setCellValue('H1', 'Fecha de Actualización');

            $row = 2;
            foreach ($unidades as $unidad) {
                $sheet->setCellValue('A' . $row, $unidad->id);
                $sheet->setCellValue('B' . $row, $unidad->dependencia->nombre);
                $sheet->setCellValue('C' . $row, $unidad->descripcion);
                $sheet->setCellValue('D' . $row, $unidad->usuarios->pluck('name')->implode(', '));
                $sheet->setCellValue('E' . $row, $unidad->audit ? ($unidad->audit->usercreated ? $unidad->audit->usercreated->email : 'N/A') : 'N/A');
                $sheet->setCellValue('F' . $row, $unidad->audit ? ($unidad->audit->userupdated ? $unidad->audit->userupdated->email : 'N/A') : 'N/A');
                $sheet->setCellValue('G' . $row, $unidad->created_at);
                $sheet->setCellValue('H' . $row, $unidad->updated_at);
                $row++;
            }

            foreach (range('A', 'H') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $writer = new Xlsx($spreadsheet);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="unidades.xlsx"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
        } catch (\Exception $e) {
            return redirect()->route('unidades.index')->with('error', 'Ocurrió un error al exportar los datos a Excel: ' . $e->getMessage());
        }
    }

    public function exportToPdf()
    {
        try {
            $unidades = Unidad::with(['usuarios', 'dependencia', 'audit.usercreated', 'audit.userupdated'])->get();
            $pdf = Pdf::loadView('unidades.pdf', compact('unidades'));
            // Personalizar la configuración del documento PDF
            $pdf->setPaper('A4', 'landscape'); // Configurar orientación horizontal y tamaño A4

            // Descargar el PDF con un nombre de archivo específico
            return $pdf->download('unidades.pdf');
        } catch (\Exception $e) {
            return redirect()->route('unidades.index')->with('error', 'Ocurrió un error al exportar los datos a PDF: ' . $e->getMessage());
        }
    }

    public function exportToWord()
    {
        try {
            $unidades = Unidad::with(['usuarios', 'dependencia', 'audit.usercreated', 'audit.userupdated'])->get();

            $phpWord = new PhpWord();
            // Ajustar la sección actual para orientación horizontal
            $section = $phpWord->addSection([
                'orientation' => 'landscape', // Orientación horizontal
            ]);

            // Estilo para encabezados en negrita
            $headerStyle = ['bold' => true];

            $section->addText('Lista de Unidades', ['bold' => true, 'size' => 16]);

            // Configurar tabla con anchos relativos
            $table = $section->addTable([
                'borderSize' => 6,
                'borderColor' => '000000',
                'cellMargin' => 80,
                'width' => 100 * 50, // Ancho de la tabla en puntos
            ]);

            // Encabezados de tabla en negrita y centrados
            $table->addRow();
            $table->addCell(800)->addText('ID', $headerStyle);
            $table->addCell(2000)->addText('Dependencia', $headerStyle);
            $table->addCell(2000)->addText('Descripción', $headerStyle);
            $table->addCell(2000)->addText('Responsables', $headerStyle);
            $table->addCell(4000)->addText('Usuario que Creó', $headerStyle);
            $table->addCell(2000)->addText('Usuario que Actualizó', $headerStyle);
            $table->addCell(2000)->addText('Fecha de Creación', $headerStyle);
            $table->addCell(2000)->addText('Fecha de Actualización', $headerStyle);

            foreach ($unidades as $unidad) {
                $table->addRow();
                $table->addCell(800)->addText($unidad->id);
                $table->addCell(2000)->addText($unidad->dependencia->nombre);
                $table->addCell(2000)->addText($unidad->descripcion);
                $table->addCell(2000)->addText($unidad->usuarios->pluck('name')->implode(', '));
                $table->addCell(4000)->addText($unidad->audit ? ($unidad->audit->usercreated ? $unidad->audit->usercreated->email : 'N/A') : 'N/A');
                $table->addCell(2000)->addText($unidad->audit ? ($unidad->audit->userupdated ? $unidad->audit->userupdated->email : 'N/A') : 'N/A');
                $table->addCell(2000)->addText($unidad->created_at);
                $table->addCell(2000)->addText($unidad->updated_at);
            }

            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');

            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            header('Content-Disposition: attachment;filename="unidades.docx"');
            header('Cache-Control: max-age=0');

            $objWriter->save('php://output');
        } catch (\Exception $e) {
            return redirect()->route('unidades.index')->with('error', 'Ocurrió un error al exportar los datos a Word: ' . $e->getMessage());
        }
    }
}
