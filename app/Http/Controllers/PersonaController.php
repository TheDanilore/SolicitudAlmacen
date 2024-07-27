<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\TipoDocumentoIdentidad;
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

class PersonaController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:lista-persona|ver-persona|crear-persona|editar-persona|borrar-persona', ['only' => ['index']]);
        $this->middleware('permission:crear-persona', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-persona', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-persona', ['only' => ['destroy']]);
        $this->middleware('permission:ver-persona', ['only' => ['show']]);
    }

    public function index()
    {
        try {
            $personas = Persona::all();
            $tipo = TipoDocumentoIdentidad::all();
            return view('personas.index', compact('personas', 'tipo'));
        } catch (\Exception $e) {
            return redirect()->route('personas.index')->with('error', 'Ocurrió un error al cargar los datos: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $personas = Persona::all();
            $tipo = TipoDocumentoIdentidad::all();
            return view('personas.create', compact('personas', 'tipo'));
        } catch (\Exception $e) {
            return redirect()->route('personas.index')->with('error', 'Ocurrió un error al cargar la vista de creación: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            //mensajes personalizados
            $messages = [
                'primer_nombre.required' => 'El primer nombre es obligatorio.',
                'primer_nombre.min' => 'El primer nombre debe tener min 2 caracteres.',
                'apellido_paterno.required' => 'El apellido paterno es obligatorio.',
                'apellido_paterno.min' => 'El apellido paterno debe tener min 5 caracteres.',
                'apellido_materno.required' => 'El apellido materno es obligatorio.',
                'apellido_materno.min' => 'El apellido materno debe tener min 5 caracteres.',
                'telefono.required' => 'El telefono es obligatorio.',
                'telefono.min' => 'El telefono debe tener tener min 9 caracteres.',
                'direccion.required' => 'El direccion es obligatorio.',
                'direccion.min' => 'El direccion debe tener min 10 caracteres.',
                'numero_documento.min' => 'El numero de documento debe tener min 8 caracteres.',
                'numero_documento.required' => 'El numero de documentoes obligatorio.',
                'numero_documento.unique' => 'El numero de documento ya esta en uso.',
            ];
            // Validar los datos del formulario
            $validatedData = $request->validate([
                'primer_nombre' => 'required|string|min:2|max:50',
                'segundo_nombre' => 'nullable|string|max:50',
                'apellido_paterno' => 'required|string|max:50|min:5',
                'apellido_materno' => 'required|string|max:70|min:5',
                'telefono' => 'required|string|min:9|max:12',
                'direccion' => 'required|string|max:255|min:10',
                'tipo_documento_identidad_id' => 'required|integer|exists:tipo_documento_identidad,id',
                'numero_documento' => [
                    'required',
                    'string',
                    'max:20',
                    'min:8',
                    Rule::unique('personas'), // Validación de unicidad
                ],
                'prefijo_telefono' => 'required|string|regex:/^\+\d{1,3}$/',
            ], $messages);

            // Validar longitud del teléfono si el prefijo es +51
            if ($validatedData['prefijo_telefono'] === '+51' && strlen($validatedData['telefono']) !== 9) {
                return back()->withErrors(['telefono' => 'El número de teléfono para el prefijo +51 debe tener 9 dígitos.'])->withInput();
            }


            // Crear una nueva persona
            $personas = Persona::create($validatedData);

            // Redirigir con un mensaje de éxito
            return redirect()->route('personas.index')->with('success', 'Persona creada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('personas.index')->with('error', 'Ocurrió un error al crear la persona: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $persona = Persona::with('audit.usercreated', 'audit.userupdated', 'tipodocumentoidentidad')->find($id);
            return response()->json($persona);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocurrió un error al mostrar la persona: ' . $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        try {
            $persona = Persona::findOrFail($id);
            $tipo = TipoDocumentoIdentidad::all();
            return view('personas.edit', compact('persona', 'tipo'));
        } catch (\Exception $e) {
            return redirect()->route('personas.index')->with('error', 'Ocurrió un error al cargar la vista de edición: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            //mensajes personalizados
            $messages = [
                'primer_nombre.required' => 'El primer nombre es obligatorio.',
                'primer_nombre.min' => 'El primer nombre debe tener min 2 caracteres.',
                'apellido_paterno.required' => 'El apellido paterno es obligatorio.',
                'apellido_paterno.min' => 'El apellido paterno debe tener min 5 caracteres.',
                'apellido_materno.required' => 'El apellido materno es obligatorio.',
                'apellido_materno.min' => 'El apellido materno debe tener min 5 caracteres.',
                'telefono.required' => 'El telefono es obligatorio.',
                'telefono.min' => 'El telefono debe tener tener min 9 caracteres.',
                'direccion.required' => 'El direccion es obligatorio.',
                'direccion.min' => 'El direccion debe tener min 10 caracteres.',
            ];
            // Validar los datos del formulario
            $validatedData = $request->validate([
                'primer_nombre' => 'required|string|min:2|max:50',
                'segundo_nombre' => 'nullable|string|max:50',
                'apellido_paterno' => 'required|string|max:50|min:5',
                'apellido_materno' => 'required|string|max:70|min:5',
                'telefono' => 'required|string|min:9|max:12',
                'direccion' => 'required|string|max:255|min:10',
                'tipo_documento_identidad_id' => 'required|integer|exists:tipo_documento_identidad,id',
                'numero_documento' => [
                    'required',
                    'string',
                    'max:20',
                    'min:8',
                ],
                'prefijo_telefono' => 'required|string|regex:/^\+\d{1,3}$/',
            ], $messages);


            // Validar longitud del teléfono si el prefijo es +51
            if ($validatedData['prefijo_telefono'] === '+51' && strlen($validatedData['telefono']) !== 9) {
                return back()->withErrors(['telefono' => 'El número de teléfono para el prefijo +51 debe tener 9 dígitos.'])->withInput();
            }

            $persona = Persona::findOrFail($id);
            $persona->update($validatedData);

            // Redirigir con un mensaje de éxito
            return redirect()->route('personas.index')->with('success', 'Persona actualizada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('personas.index')->with('error', 'Ocurrió un error al actualizar la persona: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            Persona::destroy($id);
            return redirect()->route('personas.index')->with('success', 'Persona eliminada exitosamente!');
        } catch (\Exception $e) {
            return redirect()->route('personas.index')->with('error', 'Ocurrio un error al eliminar la persona: ' . $e->getMessage());
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
            return redirect()->route('personas.index')->with('error', 'Ocurrió un error al exportar los datos: ' . $e->getMessage());
        }
    }

    public function exportToExcel()
    {
        try {
            $personas = Persona::with('tipoDocumentoIdentidad', 'audit.usercreated', 'audit.userupdated')->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A1', 'ID');
            $sheet->setCellValue('B1', 'Nombre Completo');
            $sheet->setCellValue('C1', 'Teléfono');
            $sheet->setCellValue('D1', 'Dirección');
            $sheet->setCellValue('E1', 'Tipo Documento');
            $sheet->setCellValue('F1', 'N° Documento');
            $sheet->setCellValue('G1', 'Usuario que Creó');
            $sheet->setCellValue('H1', 'Usuario que Actualizó');
            $sheet->setCellValue('I1', 'Fecha de Creación');
            $sheet->setCellValue('J1', 'Fecha de Actualización');

            $row = 2;
            foreach ($personas as $persona) {
                $sheet->setCellValue('A' . $row, $persona->id);
                $sheet->setCellValue('B' . $row, $persona->primer_nombre . ' ' . $persona->segundo_nombre . ' ' . $persona->apellido_paterno . ' ' . $persona->apellido_materno);
                $sheet->setCellValue('C' . $row, $persona->prefijo_telefono . $persona->telefono);
                $sheet->setCellValue('D' . $row, $persona->direccion);
                $sheet->setCellValue('E' . $row, $persona->tipoDocumentoIdentidad->abreviatura);
                $sheet->setCellValue('F' . $row, $persona->numero_documento);
                $sheet->setCellValue('G' . $row, $persona->audit ? ($persona->audit->usercreated ? $persona->audit->usercreated->email : 'N/A') : 'N/A');
                $sheet->setCellValue('H' . $row, $persona->audit ? ($persona->audit->userupdated ? $persona->audit->userupdated->email : 'N/A') : 'N/A');
                $sheet->setCellValue('I' . $row, $persona->created_at);
                $sheet->setCellValue('J' . $row, $persona->updated_at);
                $row++;
            }

            foreach (range('A', 'J') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $writer = new Xlsx($spreadsheet);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="personas.xlsx"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
        } catch (\Exception $e) {
            return redirect()->route('personas.index')->with('error', 'Ocurrió un error al exportar los datos a Excel: ' . $e->getMessage());
        }
    }

    public function exportToPdf()
    {
        try {
            $personas = Persona::with('tipoDocumentoIdentidad', 'audit.usercreated', 'audit.userupdated')->get();

            // Cargar la vista 'personas.pdf' con los datos
            $pdf = PDF::loadView('personas.pdf', compact('personas'));

            // Personalizar la configuración del documento PDF
            $pdf->setPaper('A4', 'landscape'); // Configurar orientación horizontal y tamaño A4

            // Descargar el PDF con un nombre de archivo específico
            return $pdf->download('personas.pdf');
        } catch (\Exception $e) {
            return redirect()->route('personas.index')->with('error', 'Ocurrió un error al exportar los datos a PDF: ' . $e->getMessage());
        }
    }


    public function exportToWord()
    {
        try {
            $personas = Persona::with('tipoDocumentoIdentidad', 'audit.usercreated', 'audit.userupdated')->get();

            $phpWord = new PhpWord();

            // Ajustar la sección actual para orientación horizontal
            $section = $phpWord->addSection([
                'orientation' => 'landscape', // Orientación horizontal
            ]);

            // Estilo para encabezados en negrita
            $headerStyle = ['bold' => true];

            $section->addText('Lista de Personas', ['bold' => true, 'size' => 16]);

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
            $table->addCell(3000)->addText('Nombre Completo', $headerStyle);
            $table->addCell(2000)->addText('Teléfono', $headerStyle);
            $table->addCell(2000)->addText('Tipo Documento', $headerStyle);
            $table->addCell(2000)->addText('N° Documento', $headerStyle);
            $table->addCell(3000)->addText('Usuario que Creó', $headerStyle);
            $table->addCell(3000)->addText('Usuario que Actualizó', $headerStyle);
            $table->addCell(2000)->addText('Fecha de Creación', $headerStyle);
            $table->addCell(2000)->addText('Fecha de Actualización', $headerStyle);

            foreach ($personas as $persona) {
                $table->addRow();
                $table->addCell(1000)->addText($persona->id);
                $table->addCell(3000)->addText($persona->primer_nombre . ' ' . $persona->segundo_nombre . ' ' . $persona->apellido_paterno . ' ' . $persona->apellido_materno);
                $table->addCell(2000)->addText($persona->prefijo_telefono . $persona->telefono);
                $table->addCell(2000)->addText($persona->tipoDocumentoIdentidad->abreviatura);
                $table->addCell(2000)->addText($persona->numero_documento);
                $table->addCell(3000)->addText($persona->audit ? ($persona->audit->usercreated ? $persona->audit->usercreated->email : 'N/A') : 'N/A');
                $table->addCell(3000)->addText($persona->audit ? ($persona->audit->userupdated ? $persona->audit->userupdated->email : 'N/A') : 'N/A');
                $table->addCell(2000)->addText($persona->created_at);
                $table->addCell(2000)->addText($persona->updated_at);
            }

            // Crear el objeto de escritura para Word
            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');

            // Configurar cabeceras para la descarga del archivo
            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            header('Content-Disposition: attachment;filename="personas.docx"');
            header('Cache-Control: max-age=0');

            // Guardar el documento en la salida
            $objWriter->save('php://output');
        } catch (\Exception $e) {
            return redirect()->route('personas.index')->with('error', 'Ocurrió un error al exportar los datos a Word: ' . $e->getMessage());
        }
    }
}
