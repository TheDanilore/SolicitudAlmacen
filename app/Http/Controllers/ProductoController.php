<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\CategoriaProducto;
use App\Models\UnidadMedida;
use App\Models\EstadoProducto;
use App\Models\Movimiento;
use App\Models\Ubicacion;
use Illuminate\Validation\Rule;
use App\Exports\ProductosExport;
use Maatwebsite\Excel\Facades\Excel;

//Imagen
use Illuminate\Support\Str;

//Exportar excel
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

//Exportar en pdf
use Barryvdh\DomPDF\Facade\Pdf;

//Exportar en Word
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class ProductoController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:lista-producto|ver-producto|crear-producto|editar-producto|borrar-producto', ['only' => ['index']]);
        $this->middleware('permission:crear-producto', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-producto', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-producto', ['only' => ['destroy']]);
        $this->middleware('permission:ver-producto', ['only' => ['show']]);
    }

    public function index()
    {
        try {
            $productos = Producto::all();
            $categoria_productos = CategoriaProducto::all();
            $unidades = UnidadMedida::all();
            $estados = EstadoProducto::all();
            $ubicaciones = Ubicacion::all();
            return view('productos.index', compact('productos', 'categoria_productos', 'unidades', 'estados', 'ubicaciones'));
        } catch (\Exception $e) {
            return redirect()->route('productos.index')->with('error', 'Ocurrió un error al cargar los datos: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $categoria_productos = CategoriaProducto::all();
            $unidades = UnidadMedida::all();
            $estados = EstadoProducto::all();
            $ubicaciones = Ubicacion::all();
            return view('productos.create', compact('categoria_productos', 'unidades', 'estados', 'ubicaciones'));
        } catch (\Exception $e) {
            return redirect()->route('productos.index')->with('error', 'Ocurrió un error al cargar la vista de creación: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            // Mensajes personalizados
            $messages = [
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.unique' => 'El nombre ya está en uso.',
                'nombre.min' => 'El nombre debe tener al menos 4 caracteres.',
                'descripcion.required' => 'La descripción es obligatoria.',
                'descripcion.min' => 'La descripción debe tener al menos 5 caracteres.',
                'categoria_productos_id.required' => 'Elige una categoría.',
                'unidad_medida_id.required' => 'Elige una unidad de medida.',
                'cantidad.required' => 'La cantidad no puede ser negativa.',
                'precio_actual.required' => 'El precio es obligatorio',
                'ubicacion_id.required' => 'La ubicación es obligatoria.',
                'imagen.image' => 'La imagen debe ser un archivo de imagen válido.',
                'imagen.max' => 'La imagen no puede exceder los 2MB.',
            ];

            // Validar los datos del formulario
            $validatedData = $request->validate([
                'nombre' => [
                    'required',
                    'string',
                    'max:100',
                    'min:4',
                    Rule::unique('productos'), // Validación de unicidad
                ],
                'descripcion' => 'required|string|max:255|min:5',
                'categoria_productos_id' => 'required|integer|exists:categoria_producto,id',
                'unidad_medida_id' => 'required|integer|exists:unidad_medida,id',
                'cantidad' => 'required|integer|min:0',
                'precio_actual' => 'required|numeric|min:0.1',
                'ubicacion_id' => 'nullable|exists:ubicacion,id',
                'imagen' => 'nullable', 'image', 'max:2048',
            ], $messages);

            // Manejo de la imagen
            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                $filename = time() . '_' . Str::random(10) . '.' . $imagen->getClientOriginalExtension();

                // Guardamos la imagen en storage/app/public/productos
                if ($imagen->storeAs('productos', $filename, 'public')) {
                    $validatedData['imagen'] = 'storage/productos/' . $filename;
                } else {
                    return redirect()->back()->withErrors(['imagen' => 'No se pudo guardar la imagen. Por favor, inténtelo de nuevo.']);
                }
            }

            // Crear el nuevo producto
            $producto = Producto::create($validatedData);

            // Redirigir a la página de índice con un mensaje de éxito
            return redirect()->route('productos.index')->with('success', 'Producto creado con éxito.');
        } catch (\Exception $e) {
            return redirect()->route('productos.index')->with('error', 'Ocurrió un error al crear el producto: ' . $e->getMessage());
        }
    }



    public function show($id)
    {
        try {
            $producto = Producto::with('estado', 'categoria', 'audit.usercreated', 'audit.userupdated', 'unidadmedida', 'ubicacion')->find($id);
            return response()->json($producto);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocurrió un error al mostrar el producto: ' . $e->getMessage()], 500);
        }
    }


    public function edit($id)
    {
        try {
            $producto = Producto::findOrFail($id);
            $categoria_productos = CategoriaProducto::all();
            $unidades = UnidadMedida::all();
            $estados = EstadoProducto::all();
            $ubicaciones = Ubicacion::all();
            return view('productos.editar', compact('producto', 'categoria_productos', 'unidades', 'estados', 'ubicaciones'));
        } catch (\Exception $e) {
            return redirect()->route('productos.index')->with('error', 'Ocurrió un error al cargar la vista de edición: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Mensajes personalizados
            $messages = [
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.min' => 'El nombre debe tener min 4 caracteres.',
                'descripcion.required' => 'La descripcion es obligatorio.',
                'descripcion.min' => 'La descripcion debe tener minimo 5 caracteres.',
                'categoria_productos_id.required' => 'Elije una categoria.',
                'unidad_medida_id.required' => 'Elije una unidad de medida.',
                'cantidad.required' => 'La cantidad no puede ser negativa.',
                'precio_actual.required' => 'El precio es obligatorio.',
                'ubicacion_id.required' => 'La ubicacion es obligatoria.',
                'imagen.image' => 'La imagen debe ser un archivo de imagen válido.',
                'imagen.max' => 'La imagen no puede exceder los 2MB.',
            ];

            // Validar los datos del formulario
            $validatedData = $request->validate([
                'nombre' => [
                    'required',
                    'string',
                    'max:100',
                    'min:4',
                ],
                'descripcion' => 'required|string|max:255|min:5',
                'categoria_productos_id' => 'required|integer|exists:categoria_producto,id',
                'unidad_medida_id' => 'required|integer|exists:unidad_medida,id',
                'cantidad' => 'required|integer|min:0',
                'precio_actual' => 'required|numeric|min:0.1',
                'ubicacion_id' => 'nullable|exists:ubicacion,id',
                'estado_producto_id' => 'nullable|exists:estado_proveedor,id',
                'imagen' => 'nullable', 'image', 'max:2048',
            ], $messages);

            // Manejo de la imagen
            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                $filename = time() . '_' . Str::random(10) . '.' . $imagen->getClientOriginalExtension();

                // Guardamos la imagen en storage/app/public/productos
                if ($imagen->storeAs('productos', $filename, 'public')) {
                    $validatedData['imagen'] = 'storage/productos/' . $filename;
                } else {
                    return redirect()->back()->withErrors(['imagen' => 'No se pudo guardar la imagen. Por favor, inténtelo de nuevo.']);
                }
            } else {
                // Si no hay una nueva imagen, eliminamos el campo de la imagen de los datos validados para no actualizarlo
                unset($validatedData['imagen']);
            }

            $producto = Producto::findOrFail($id);
            $stock_anterior = $producto->cantidad; // Guardamos el stock anterior
            $producto->update($validatedData);

            // Validamos si agrega cantidad mayor o menor
            if ($stock_anterior != $producto->cantidad) {
                Movimiento::create([
                    'producto_id' => $producto->id,
                    'tipo' => 'ajuste',
                    'item_entrada_id' => 0, // Relacionamos con ItemEntrada
                    'item_salida_id' => 0,
                    'stock_anterior' => $stock_anterior,
                    'cantidad' => $validatedData['cantidad'],
                ]);
            }

            // Redirigir con un mensaje de éxito
            return redirect()->route('productos.index')->with('success', 'Producto actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('productos.index')->with('error', 'Ocurrió un error al actualizar el producto: ' . $e->getMessage());
        }
    }


    public function destroy($id)
    {
        try {
            $producto = Producto::findOrFail($id);
            $producto->delete();

            return redirect()->route('productos.index')->with('success', 'Productos eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('productos.index')->with('error', 'Ocurrio un error al eliminar el producto: ' . $e->getMessage());
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
            return redirect()->route('productos.index')->with('error', 'Ocurrió un error al exportar los datos: ' . $e->getMessage());
        }
    }

    public function exportToExcel()
    {
        try {
            $productos = Producto::with(['categoria', 'unidadMedida', 'audit.usercreated', 'audit.userupdated', 'estado', 'ubicacion'])->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Definir las cabeceras
            $sheet->setCellValue('A1', 'ID');
            $sheet->setCellValue('B1', 'Nombre');
            $sheet->setCellValue('C1', 'Descripción');
            $sheet->setCellValue('D1', 'Categoría');
            $sheet->setCellValue('E1', 'Unidad de Medida');
            $sheet->setCellValue('F1', 'Stock');
            $sheet->setCellValue('G1', 'Precio Unitario');
            $sheet->setCellValue('H1', 'Ubicación');
            $sheet->setCellValue('I1', 'Imagen');
            $sheet->setCellValue('J1', 'Estado');
            $sheet->setCellValue('K1', 'Usuario que Creó');
            $sheet->setCellValue('L1', 'Usuario que Actualizó');
            $sheet->setCellValue('M1', 'Fecha de Creación');
            $sheet->setCellValue('N1', 'Fecha de Actualización');

            // Agregar datos
            $row = 2;
            foreach ($productos as $producto) {
                $sheet->setCellValue('A' . $row, $producto->id);
                $sheet->setCellValue('B' . $row, $producto->nombre);
                $sheet->setCellValue('C' . $row, $producto->descripcion);
                $sheet->setCellValue('D' . $row, $producto->categoria->descripcion);
                $sheet->setCellValue('E' . $row, $producto->unidadMedida->abreviacion);
                $sheet->setCellValue('F' . $row, $producto->cantidad);
                $sheet->setCellValue('G' . $row, $producto->precio_actual);
                $sheet->setCellValue('H' . $row, $producto->ubicacion->descripcion);

                // Insertar imagen
                if (!empty($producto->imagen)) {
                    $imagePath = public_path($producto->imagen);
                    if (file_exists($imagePath)) {
                        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                        $drawing->setPath($imagePath);
                        $drawing->setCoordinates('I' . $row);
                        $drawing->setWidth(50); // Ajusta el tamaño según sea necesario
                        $drawing->setHeight(50);
                        $drawing->setWorksheet($sheet);
                    }
                }

                $sheet->setCellValue('J' . $row, $producto->estado->descripcion);
                $sheet->setCellValue('K' . $row, $producto->audit ? ($producto->audit->usercreated ? $producto->audit->usercreated->email : 'N/A') : 'N/A');
                $sheet->setCellValue('L' . $row, $producto->audit ? ($producto->audit->userupdated ? $producto->audit->userupdated->email : 'N/A') : 'N/A');
                $sheet->setCellValue('M' . $row, $producto->created_at);
                $sheet->setCellValue('N' . $row, $producto->updated_at);
                $row++;
            }

            // Autoajustar el ancho de las columnas
            foreach (range('A', 'N') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Crear el escritor y generar el archivo Excel
            $writer = new Xlsx($spreadsheet);

            // Configurar la respuesta HTTP para descargar el archivo
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="productos.xlsx"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
        } catch (\Exception $e) {
            return redirect()->route('productos.index')->with('error', 'Ocurrió un error al exportar los datos a Excel: ' . $e->getMessage());
        }
    }

    public function exportToPdf()
    {
        try {
            $productos = Producto::with(['categoria', 'unidadMedida', 'audit.usercreated', 'audit.userupdated', 'estado', 'ubicacion'])->get();
            // Cargar la vista 'personas.pdf' con los datos
            $pdf = Pdf::loadView('productos.pdf', compact('productos'));

            // Personalizar la configuración del documento PDF
            $pdf->setPaper('A4', 'landscape'); // Configurar orientación horizontal y tamaño A4

            // Descargar el PDF con un nombre de archivo específico
            return $pdf->download('productos.pdf');
        } catch (\Exception $e) {
            return redirect()->route('productos.index')->with('error', 'Ocurrió un error al exportar los datos a PDF: ' . $e->getMessage());
        }
    }

    public function exportToWord()
    {
        try {
            $productos = Producto::with(['categoria', 'unidadMedida', 'audit.usercreated', 'audit.userupdated', 'estado', 'ubicacion'])->get();

            $phpWord = new PhpWord();

            // Ajustar la sección actual para orientación horizontal
            $section = $phpWord->addSection([
                'orientation' => 'landscape', // Orientación horizontal
            ]);

            // Estilo para encabezados en negrita
            $headerStyle = ['bold' => true];

            $section->addText('Lista de Productos', ['bold' => true, 'size' => 16]);

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
            $table->addCell(2000)->addText('Descripción', $headerStyle);
            $table->addCell(2000)->addText('Categoría', $headerStyle);
            $table->addCell(2000)->addText('Unidad de Medida', $headerStyle);
            $table->addCell(2000)->addText('Stock', $headerStyle);
            $table->addCell(2000)->addText('Precio Unitario', $headerStyle);
            $table->addCell(2000)->addText('Ubicación', $headerStyle);
            $table->addCell(2000)->addText('Imagen', $headerStyle);
            $table->addCell(2000)->addText('Estado', $headerStyle);
            $table->addCell(3000)->addText('Usuario que Creó', $headerStyle);
            $table->addCell(3000)->addText('Usuario que Actualizó', $headerStyle);
            $table->addCell(2000)->addText('Fecha de Creación', $headerStyle);
            $table->addCell(2000)->addText('Fecha de Actualización', $headerStyle);

            foreach ($productos as $producto) {
                $table->addRow();
                $table->addCell(1000)->addText($producto->id);
                $table->addCell(2000)->addText($producto->nombre);
                $table->addCell(2000)->addText($producto->descripcion);
                $table->addCell(2000)->addText($producto->categoria->descripcion);
                $table->addCell(2000)->addText($producto->unidadMedida->abreviacion);
                $table->addCell(2000)->addText($producto->cantidad);
                $table->addCell(2000)->addText($producto->precio_actual);
                $table->addCell(2000)->addText($producto->ubicacion->descripcion);

                // Insertar imagen
                if (!empty($producto->imagen)) {
                    $imagePath = public_path($producto->imagen);
                    if (file_exists($imagePath)) {
                        $imageContent = file_get_contents($imagePath);
                        $table->addCell(2000)->addImage($imageContent, ['width' => 50, 'height' => 50]);
                    } else {
                        $table->addCell(2000)->addText('N/A');
                    }
                } else {
                    $table->addCell(2000)->addText('N/A');
                }

                $table->addCell(2000)->addText($producto->estado->descripcion);
                $table->addCell(3000)->addText($producto->audit ? ($producto->audit->usercreated ? $producto->audit->usercreated->email : 'N/A') : 'N/A');
                $table->addCell(3000)->addText($producto->audit ? ($producto->audit->userupdated ? $producto->audit->userupdated->email : 'N/A') : 'N/A');
                $table->addCell(2000)->addText($producto->created_at);
                $table->addCell(2000)->addText($producto->updated_at);
            }

            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');

            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            header('Content-Disposition: attachment;filename="productos.docx"');
            header('Cache-Control: max-age=0');

            $objWriter->save('php://output');
        } catch (\Exception $e) {
            return redirect()->route('productos.index')->with('error', 'Ocurrió un error al exportar los datos a Word: ' . $e->getMessage());
        }
    }
}
