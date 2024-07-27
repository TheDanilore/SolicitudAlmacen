<?php

namespace App\Http\Controllers;

use App\Models\Ubicacion;
use Illuminate\Http\Request;

//Exportar excel
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

//Exportar en pdf
use Barryvdh\DomPDF\Facade\Pdf;

//Exportar en Word
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class UbicacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $ubicaciones = Ubicacion::all();
            return view('ubicacion.index', compact('ubicaciones'));
        } catch (\Exception $e) {
            return redirect()->route('ubicacion.index')->with('error', 'Ocurrió un error al cargar los datos: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $ubicaciones = Ubicacion::all();
            return view('ubicacion.create', compact('ubicaciones'));
        } catch (\Exception $e) {
            return redirect()->route('ubicacion.index')->with('error', 'Ocurrió un error al cargar la vista de creación: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            ///mensajes personalizados
            $messages = [
                'code.required' => 'El código es obligatorio.',
                'code.min' => 'El código debe contener min 9 caracteres.',
                'descripcion.min' => 'El nombre debe contener min 5 caracteres.',
                'descripcion.required' => 'El nombre es obligatorio.',
            ];
            $validatedData = $request->validate([
                'code' => 'required|string|max:100|min:8',
                'descripcion' => 'required|string|max:100|min:5',
            ], $messages);


            Ubicacion::create($validatedData);

            return redirect()->route('ubicacion.index')->with('success', 'Ubicacion creada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('ubicacion.index')->with('error', 'Ocurrió un error al crear la Ubicación: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $ubicacion = Ubicacion::with('audit.usercreated', 'audit.userupdated')->find($id);
            return response()->json($ubicacion);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocurrió un error al mostrar la Ubicación: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $ubicacion = Ubicacion::findOrFail($id);
            return view('ubicacion.edit', compact('ubicacion'));
        } catch (\Exception $e) {
            return redirect()->route('ubicacion.index')->with('error', 'Ocurrió un error al cargar la vista de edición: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        try {
            //mensajes personalizados
            $messages = [
                'code.required' => 'El código es obligatorio.',
                'code.min' => 'El código debe contener min 9 caracteres.',
                'descripcion.min' => 'El nombre debe contener min 5 caracteres.',
                'descripcion.required' => 'El nombre es obligatorio.',
            ];
            $validatedData = $request->validate([
                'code' => 'required|string|max:100|min:8',
                'descripcion' => 'required|string|max:100|min:5',
            ], $messages);


            Ubicacion::where('id', $id)->update($validatedData);

            //$unidad->update($validatedData);

            return redirect()->route('ubicacion.index')->with('success', 'ubicacion actualizada exitosamente!');
        } catch (\Exception $e) {
            return redirect()->route('ubicacion.index')->with('error', 'Ocurrió un error al actualizar la ubicación: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $ubicacion = Ubicacion::findOrFail($id);
            $ubicacion->delete();

            return redirect()->route('ubicacion.index')->with('success', 'Ubicación eliminada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('ubicacion.index')->with('error', 'Ocurrió un error al eliminar la ubicación: ' . $e->getMessage());
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
            return redirect()->route('ubicacion.index')->with('error', 'Ocurrió un error al exportar los datos: ' . $e->getMessage());
        }
    }

    public function exportToExcel()
    {
        try {
            $ubicaciones = Ubicacion::with(['audit.usercreated', 'audit.userupdated'])->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A1', 'ID');
            $sheet->setCellValue('B1', 'Código');
            $sheet->setCellValue('C1', 'Descripción');
            $sheet->setCellValue('D1', 'Usuario que Creó');
            $sheet->setCellValue('E1', 'Usuario que Actualizó');
            $sheet->setCellValue('F1', 'Fecha de Creación');
            $sheet->setCellValue('G1', 'Fecha de Actualización');

            $row = 2;
            foreach ($ubicaciones as $ubicacion) {
                $sheet->setCellValue('A' . $row, $ubicacion->id);
                $sheet->setCellValue('B' . $row, $ubicacion->code);
                $sheet->setCellValue('C' . $row, $ubicacion->descripcion);
                $sheet->setCellValue('D' . $row, $ubicacion->audit ? ($ubicacion->audit->usercreated ? $ubicacion->audit->usercreated->email : 'N/A') : 'N/A');
                $sheet->setCellValue('E' . $row, $ubicacion->audit ? ($ubicacion->audit->userupdated ? $ubicacion->audit->userupdated->email : 'N/A') : 'N/A');
                $sheet->setCellValue('F' . $row, $ubicacion->created_at);
                $sheet->setCellValue('G' . $row, $ubicacion->updated_at);
                $row++;
            }

            foreach (range('A', 'G') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $writer = new Xlsx($spreadsheet);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="ubicaciones.xlsx"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
        } catch (\Exception $e) {
            return redirect()->route('ubicacion.index')->with('error', 'Ocurrió un error al exportar los datos a Excel: ' . $e->getMessage());
        }
    }

    public function exportToPdf()
    {
        try {
            $ubicaciones = Ubicacion::with(['audit.usercreated', 'audit.userupdated'])->get();
            $pdf = Pdf::loadView('ubicacion.pdf', compact('ubicaciones'));

            // Personalizar la configuración del documento PDF
            $pdf->setPaper('A4', 'landscape'); // Configurar orientación horizontal y tamaño A4

            // Descargar el PDF con un nombre de archivo específico
            return $pdf->download('ubicacion.pdf');
        } catch (\Exception $e) {
            return redirect()->route('ubicacion.index')->with('error', 'Ocurrió un error al exportar los datos a PDF: ' . $e->getMessage());
        }
    }

    public function exportToWord()
    {
        try {
            $ubicaciones = Ubicacion::with(['audit.usercreated', 'audit.userupdated'])->get();

            $phpWord = new PhpWord();
            // Ajustar la sección actual para orientación horizontal
            $section = $phpWord->addSection([
                'orientation' => 'landscape', // Orientación horizontal
            ]);

            // Estilo para encabezados en negrita
            $headerStyle = ['bold' => true];

            $section->addText('Lista de Ubicaciones', ['bold' => true, 'size' => 16]);

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
            $table->addCell(2000)->addText('Código', $headerStyle);
            $table->addCell(2000)->addText('Descripción', $headerStyle);
            $table->addCell(2000)->addText('Usuario que Creó', $headerStyle);
            $table->addCell(2000)->addText('Usuario que Actualizó', $headerStyle);
            $table->addCell(2000)->addText('Fecha de Creación', $headerStyle);
            $table->addCell(2000)->addText('Fecha de Actualización', $headerStyle);

            foreach ($ubicaciones as $ubicacion) {
                $table->addRow();
                $table->addCell(800)->addText($ubicacion->id);
                $table->addCell(2000)->addText($ubicacion->code);
                $table->addCell(2000)->addText($ubicacion->descripcion);
                $table->addCell(2000)->addText($ubicacion->audit ? ($ubicacion->audit->usercreated ? $ubicacion->audit->usercreated->email : 'N/A') : 'N/A');
                $table->addCell(2000)->addText($ubicacion->audit ? ($ubicacion->audit->userupdated ? $ubicacion->audit->userupdated->email : 'N/A') : 'N/A');
                $table->addCell(2000)->addText($ubicacion->created_at);
                $table->addCell(2000)->addText($ubicacion->updated_at);
            }

            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');

            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            header('Content-Disposition: attachment;filename="ubicaciones.docx"');
            header('Cache-Control: max-age=0');

            $objWriter->save('php://output');
        } catch (\Exception $e) {
            return redirect()->route('ubicacion.index')->with('error', 'Ocurrió un error al exportar los datos a Word: ' . $e->getMessage());
        }
    }
}
