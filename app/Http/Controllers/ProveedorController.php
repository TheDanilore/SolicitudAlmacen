<?php

namespace App\Http\Controllers;

use App\Models\EstadoProveedor;
use Illuminate\Http\Request;
use App\Models\Proveedor;
use Illuminate\Validation\Rule;

//Exportar excel
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

//Exportar en pdf
use Barryvdh\DomPDF\Facade\Pdf;

//Exportar en Word
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class ProveedorController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:lista-proveedor|ver-proveedor|crear-proveedor|editar-proveedor|borrar-proveedor', ['only' => ['index']]);
        $this->middleware('permission:crear-proveedor', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-proveedor', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-proveedor', ['only' => ['destroy']]);
        $this->middleware('permission:ver-proveedor', ['only' => ['show']]);
    }

    public function index()
    {
        try {
            $proveedores = Proveedor::all();
            $estados = EstadoProveedor::all();
            return view('proveedor.index', compact('proveedores', 'estados'));
        } catch (\Exception $e) {
            return redirect()->route('proveedor.index')->with('error', 'Ocurrió un error al cargar los datos: ' . $e->getMessage());
        }
    }
    public function create()
    {
        try {
            $estados = EstadoProveedor::all();
            return view('proveedor.create', compact('estados'));
        } catch (\Exception $e) {
            return redirect()->route('proveedor.index')->with('error', 'Ocurrió un error al cargar la vista de creación: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            //mensajes personalizados
            $messages = [
                'ruc.required' => 'El ruc es obligatorio.',
                'ruc.unique' => 'El ruc ya esta en uso.',
                'ruc.min' => 'El ruc debe contener 11 digitos.',
                'ruc.max' => 'El ruc debe contener 11 digitos.',
                'razon_social.required' => 'La razon social es obligatorio.',
                'razon_social.unique' => 'La razon social ya esta en uso.',
                'razon_social.min' => 'La razon social debe tener 11 caracteres al menos.',
                'direccion.required' => 'La direccion es obligatorio.',
                'direccion.min' => 'La direccion debe tener 10 caracteres al menos.',
                'telefono.required' => 'El telefono es obligatorio.',
                'telefono.min' => 'El telefono debe tener 9 caracteres al menos.',
            ];
            // Validar los datos del formulario
            $validatedData = $request->validate([
                'ruc' => [
                    'required',
                    'numeric',
                    'digits:11',
                    Rule::unique('proveedor'), // Validación de unicidad
                ],
                'razon_social' => [
                    'required',
                    'string',
                    'max:255',
                    'min:11',
                    Rule::unique('proveedor'), // Validación de unicidad
                ],
                'direccion' => 'required|string|max:255|min:10',
                'telefono' => 'required|string|min:9|max:12',
                'prefijo_telefono' => 'required|string|regex:/^\+\d{1,3}$/',
            ], $messages);

            // Validar longitud del teléfono si el prefijo es +51
            if ($validatedData['prefijo_telefono'] === '+51' && strlen($validatedData['telefono']) !== 9) {
                return back()->withErrors(['telefono' => 'El número de teléfono para el prefijo +51 debe tener 9 dígitos.'])->withInput();
            }


            // Crear una nueva persona
            $proveedores = Proveedor::create($validatedData);

            // Redirigir con un mensaje de éxito
            return redirect()->route('proveedor.index')->with('success', 'Proveedor creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('proveedor.index')->with('error', 'Ocurrió un error al crear el proveedor: ' . $e->getMessage());
        }
    }


    public function show($id)
    {
        try {
            $proveedor = Proveedor::with('estado', 'audit.usercreated', 'audit.userupdated')->find($id);
            return response()->json($proveedor);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocurrió un error al mostrar el proveedor: ' . $e->getMessage()], 500);
        }
    }


    public function edit($id)
    {
        try {
            $proveedor = Proveedor::findOrFail($id);
            $estadosProveedor = EstadoProveedor::all();
            return view('proveedor.editar', compact('proveedor', 'estadosProveedor'));
        } catch (\Exception $e) {
            return redirect()->route('proveedor.index')->with('error', 'Ocurrió un error al cargar la vista de edición: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            //mensajes personalizados
            $messages = [
                'ruc.required' => 'El ruc es obligatorio.',
                'ruc.min' => 'El ruc debe contener 11 digitos.',
                'ruc.max' => 'El ruc debe contener 11 digitos.',
                'razon_social.required' => 'La razon social es obligatorio.',
                'razon_social.min' => 'La razon social debe tener 11 caracteres al menos.',
                'direccion.required' => 'La direccion es obligatorio.',
                'direccion.min' => 'La direccion debe tener 10 caracteres al menos.',
                'telefono.required' => 'El telefono es obligatorio.',
                'telefono.min' => 'El telefono debe tener 9 caracteres al menos.',
            ];
            // Validar los datos del formulario
            $validatedData = $request->validate([
                'ruc' => [
                    'required',
                    'numeric',
                    'digits:11',
                ],
                'razon_social' => [
                    'required',
                    'string',
                    'max:255',
                    'min:11',
                ],
                'direccion' => 'required|string|max:255|min:10',
                'telefono' => 'required|string|min:9|max:12',
                'prefijo_telefono' => 'required|string|regex:/^\+\d{1,3}$/',
                'estado_proveedor_id' => 'required|exists:estado_proveedor,id',
            ], $messages);


            $proveedor = Proveedor::findOrFail($id);
            $proveedor->update($validatedData);

            // Redirigir con un mensaje de éxito
            return redirect()->route('proveedor.index')->with('success', 'Proveedor actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('proveedor.index')->with('error', 'Ocurrió un error al actualizar el proveedor: ' . $e->getMessage());
        }
    }


    public function destroy($id)
    {
        try {
            $proveedor = Proveedor::findOrFail($id);
            $proveedor->delete();
            return redirect()->route('proveedor.index')->with('success', 'Proveedor eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('proveedor.index')->with('error', 'Ocurrio un error al eliminar el proveedor: ' . $e->getMessage());
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
            return redirect()->route('proveedor.index')->with('error', 'Ocurrió un error al exportar los datos: ' . $e->getMessage());
        }
    }

    public function exportToExcel()
    {
        try {
            $proveedores = Proveedor::with('estado', 'audit.usercreated', 'audit.userupdated')->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A1', 'ID');
            $sheet->setCellValue('B1', 'RUC');
            $sheet->setCellValue('C1', 'Razón Social');
            $sheet->setCellValue('D1', 'Dirección');
            $sheet->setCellValue('E1', 'Teléfono');
            $sheet->setCellValue('F1', 'Estado');
            $sheet->setCellValue('G1', 'Usuario que Creó');
            $sheet->setCellValue('H1', 'Usuario que Actualizó');
            $sheet->setCellValue('I1', 'Fecha de Creación');
            $sheet->setCellValue('J1', 'Fecha de Actualización');

            $row = 2;
            foreach ($proveedores as $proveedor) {
                $sheet->setCellValue('A' . $row, $proveedor->id);
                $sheet->setCellValue('B' . $row, $proveedor->ruc);
                $sheet->setCellValue('C' . $row, $proveedor->razon_social);
                $sheet->setCellValue('D' . $row, $proveedor->direccion);
                $sheet->setCellValue('E' . $row, $proveedor->prefijo_telefono . $proveedor->telefono);
                $sheet->setCellValue('F' . $row, $proveedor->estado->descripcion);
                $sheet->setCellValue('G' . $row, $proveedor->audit ? ($proveedor->audit->usercreated ? $proveedor->audit->usercreated->email : 'N/A') : 'N/A');
                $sheet->setCellValue('H' . $row, $proveedor->audit ? ($proveedor->audit->userupdated ? $proveedor->audit->userupdated->email : 'N/A') : 'N/A');
                $sheet->setCellValue('I' . $row, $proveedor->created_at);
                $sheet->setCellValue('J' . $row, $proveedor->updated_at);
                $row++;
            }

            foreach (range('A', 'J') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $writer = new Xlsx($spreadsheet);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="proveedores.xlsx"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
        } catch (\Exception $e) {
            return redirect()->route('proveedor.index')->with('error', 'Ocurrió un error al exportar los datos a Excel: ' . $e->getMessage());
        }
    }


    public function exportToPdf()
    {
        try {
            $proveedores = Proveedor::with('estado', 'audit.usercreated', 'audit.userupdated')->get();
            $pdf = Pdf::loadView('proveedor.pdf', compact('proveedores'));

            // Personalizar la configuración del documento PDF
            $pdf->setPaper('A4', 'landscape'); // Configurar orientación horizontal y tamaño A4

            // Descargar el PDF con un nombre de archivo específico
            return $pdf->download('proveedor.pdf');
        } catch (\Exception $e) {
            return redirect()->route('proveedor.index')->with('error', 'Ocurrió un error al exportar los datos a PDF: ' . $e->getMessage());
        }
    }

    public function exportToWord()
    {
        try {
            $proveedores = Proveedor::with('estado', 'audit.usercreated', 'audit.userupdated')->get();

            $phpWord = new PhpWord();
            // Ajustar la sección actual para orientación horizontal
            $section = $phpWord->addSection([
                'orientation' => 'landscape', // Orientación horizontal
            ]);

            // Estilo para encabezados en negrita
            $headerStyle = ['bold' => true];

            $section->addText('Lista de Proveedores', ['bold' => true, 'size' => 16]);

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
            $table->addCell(2000)->addText('RUC', $headerStyle);
            $table->addCell(2000)->addText('Razón Social', $headerStyle);
            $table->addCell(2000)->addText('Dirección', $headerStyle);
            $table->addCell(2000)->addText('Teléfono', $headerStyle);
            $table->addCell(2000)->addText('Estado', $headerStyle);
            $table->addCell(2000)->addText('Usuario que Creó', $headerStyle);
            $table->addCell(2000)->addText('Usuario que Actualizó', $headerStyle);
            $table->addCell(2000)->addText('Fecha de Creación', $headerStyle);
            $table->addCell(2000)->addText('Fecha de Actualización', $headerStyle);

            foreach ($proveedores as $proveedor) {
                $table->addRow();
                $table->addCell(800)->addText($proveedor->id);
                $table->addCell(2000)->addText($proveedor->ruc);
                $table->addCell(2000)->addText($proveedor->razon_social);
                $table->addCell(2000)->addText($proveedor->direccion);
                $table->addCell(2000)->addText($proveedor->prefijo_telefono . $proveedor->telefono);
                $table->addCell(2000)->addText($proveedor->estado->descripcion);
                $table->addCell(2000)->addText($proveedor->audit ? ($proveedor->audit->usercreated ? $proveedor->audit->usercreated->email : 'N/A') : 'N/A');
                $table->addCell(2000)->addText($proveedor->audit ? ($proveedor->audit->userupdated ? $proveedor->audit->userupdated->email : 'N/A') : 'N/A');
                $table->addCell(2000)->addText($proveedor->created_at);
                $table->addCell(2000)->addText($proveedor->updated_at);
            }

            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');

            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            header('Content-Disposition: attachment;filename="proveedores.docx"');
            header('Cache-Control: max-age=0');

            $objWriter->save('php://output');
        } catch (\Exception $e) {
            return redirect()->route('proveedor.index')->with('error', 'Ocurrió un error al exportar los datos a Word: ' . $e->getMessage());
        }
    }
}
