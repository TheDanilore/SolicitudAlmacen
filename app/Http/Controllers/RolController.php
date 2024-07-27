<?php

namespace App\Http\Controllers;

use App\Models\EstadoRol;
use App\Models\Rol;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

//Exportar excel
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

//Exportar en pdf
use Barryvdh\DomPDF\Facade\Pdf;

//Exportar en Word
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class RolController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:lista-rol|ver-rol|crear-rol|editar-rol|borrar-rol', ['only' => ['index']]);
        $this->middleware('permission:crear-rol', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-rol', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-rol', ['only' => ['destroy']]);
        $this->middleware('permission:ver-rol', ['only' => ['show']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $rolss = Rol::all();
            $roles = Role::all();
            return view('roles.index', compact('roles', 'rolss'));
        } catch (\Exception $e) {
            return redirect()->route('roles.index')->with('error', 'Ocurrió un error al cargar los datos: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $permission = Permission::get();
            return view('roles.crear', compact('permission'));
        } catch (\Exception $e) {
            return redirect()->route('roles.index')->with('error', 'Ocurrió un error al cargar la vista de creación: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        try {
            //mensajes personalizados
            $messages = [
                'name.required' => 'El nombre es obligatorio.',
                'permission.required' => 'Elegir un permiso al menos es obligatorio.',
                'name.unique' => 'El nombre ya esta en uso.',
            ];
            // Validar los datos del formulario
            $validatedData = $request->validate([
                'name' => 'required|unique:roles,name',
                'permission' => 'required|array',
            ], $messages);


            $role = Role::create([
                'name' => $request->input('name')
            ]);
            $role->syncPermissions($request->input('permission'));

            return redirect()->route('roles.index')->with('success', 'Rol creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('roles.index')->with('error', 'Ocurrió un error al crear el rol: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $rol = Rol::with('estadorol', 'audit.usercreated', 'audit.userupdated')->find($id);
            return response()->json($rol);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocurrió un error al mostrar el rol: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $role = Role::findOrFail($id);

            $permission = Permission::all();
            $rolePermissions = $role->permissions->pluck('name')->toArray();

            //$permission = Permission::get();

            /*$rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
*/
            //$rolePermissions = $role->permissions()->pluck('id')->toArray(); // Obtener IDs de permisos asociados al rol

            return view('roles.editar', compact('role', 'permission', 'rolePermissions'));
        } catch (\Exception $e) {
            return redirect()->route('roles.index')->with('error', 'Ocurrió un error al cargar la vista de edición: ' . $e->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            ///mensajes personalizados
            $messages = [
                'name.required' => 'El nombre es obligatorio.',
                'permission.required' => 'Elegir un permiso al menos es obligatorio.',
                'estado_rol_id.required' => 'Elegir estado es obligatorio.',
            ];
            // Validar los datos del formulario
            $validatedData = $request->validate([
                'name' => 'required',
                'permission' => 'required|array',
                'estado_rol_id' => 'required',
            ], $messages);



            $role = Role::findOrFail($id);
            $role->name = $request->input('name');
            $role->estado_rol_id = $request->input('estado_rol_id');
            $role->save();

            $role->syncPermissions($request->input('permission'));

            return redirect()->route('roles.index')->with('success', 'Rol actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('roles.index')->with('error', 'Ocurrió un error al actualizar el rol: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::table('roles')->where('id', $id)->delete();
            return redirect()->route('roles.index')->with('success', 'Rol eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('roles.index')->with('error', 'Ocurrio un error al eliminar el rol: ' . $e->getMessage());
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
            return redirect()->route('roles.index')->with('error', 'Ocurrió un error al exportar los datos: ' . $e->getMessage());
        }
    }

    public function exportToExcel()
    {
        try {
            $roles = Rol::with('estadoRol', 'audit.usercreated', 'audit.userupdated')->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A1', 'ID');
            $sheet->setCellValue('B1', 'Rol');
            $sheet->setCellValue('C1', 'Name');
            $sheet->setCellValue('D1', 'Estado');
            $sheet->setCellValue('E1', 'Usuario que Creó');
            $sheet->setCellValue('F1', 'Usuario que Actualizó');
            $sheet->setCellValue('G1', 'Fecha de Creación');
            $sheet->setCellValue('H1', 'Fecha de Actualización');

            $row = 2;
            foreach ($roles as $role) {
                $sheet->setCellValue('A' . $row, $role->id);
                $sheet->setCellValue('B' . $row, $role->name);
                $sheet->setCellValue('C' . $row, $role->guard_name);
                $sheet->setCellValue('D' . $row, $role->estadoRol->descripcion);
                $sheet->setCellValue('E' . $row, $role->audit ? ($role->audit->usercreated ? $role->audit->usercreated->email : 'N/A') : 'N/A');
                $sheet->setCellValue('F' . $row, $role->audit ? ($role->audit->userupdated ? $role->audit->userupdated->email : 'N/A') : 'N/A');
                $sheet->setCellValue('G' . $row, $role->created_at);
                $sheet->setCellValue('H' . $row, $role->updated_at);
                $row++;
            }

            foreach (range('A', 'H') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $writer = new Xlsx($spreadsheet);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="roles.xlsx"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
        } catch (\Exception $e) {
            return redirect()->route('roles.index')->with('error', 'Ocurrió un error al exportar los datos a Excel: ' . $e->getMessage());
        }
    }

    public function exportToPdf()
    {
        try {
            $roles = Rol::with('estadoRol', 'audit.usercreated', 'audit.userupdated')->get();
            $pdf = Pdf::loadView('roles.pdf', compact('roles'));

            // Personalizar la configuración del documento PDF
            $pdf->setPaper('A4', 'landscape'); // Configurar orientación horizontal y tamaño A4

            // Descargar el PDF con un nombre de archivo específico
            return $pdf->download('roles.pdf');
        } catch (\Exception $e) {
            return redirect()->route('roles.index')->with('error', 'Ocurrió un error al exportar los datos a PDF: ' . $e->getMessage());
        }
    }

    public function exportToWord()
    {
        try {
            $roles = Rol::with('estadoRol', 'audit.usercreated', 'audit.userupdated')->get();

            $phpWord = new PhpWord();
            // Ajustar la sección actual para orientación horizontal
            $section = $phpWord->addSection([
                'orientation' => 'landscape', // Orientación horizontal
            ]);

            // Estilo para encabezados en negrita
            $headerStyle = ['bold' => true];

            $section->addText('Lista de Roles', ['bold' => true, 'size' => 16]);

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
            $table->addCell(2000)->addText('Rol', $headerStyle);
            $table->addCell(2000)->addText('Name', $headerStyle);
            $table->addCell(2000)->addText('Estado', $headerStyle);
            $table->addCell(2000)->addText('Usuario que Creó', $headerStyle);
            $table->addCell(2000)->addText('Usuario que Actualizó', $headerStyle);
            $table->addCell(2000)->addText('Fecha de Creación', $headerStyle);
            $table->addCell(2000)->addText('Fecha de Actualización', $headerStyle);

            foreach ($roles as $rol) {
                $table->addRow();
                $table->addCell(800)->addText($rol->id);
                $table->addCell(2000)->addText($rol->name);
                $table->addCell(2000)->addText($rol->guard_name);
                $table->addCell(2000)->addText($rol->estadoRol->descripcion);
                $table->addCell(2000)->addText($rol->audit ? ($rol->audit->usercreated ? $rol->audit->usercreated->email : 'N/A') : 'N/A');
                $table->addCell(2000)->addText($rol->audit ? ($rol->audit->userupdated ? $rol->audit->userupdated->email : 'N/A') : 'N/A');
                $table->addCell(2000)->addText($rol->created_at);
                $table->addCell(2000)->addText($rol->updated_at);
            }

            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');

            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            header('Content-Disposition: attachment;filename="roles.docx"');
            header('Cache-Control: max-age=0');

            $objWriter->save('php://output');
        } catch (\Exception $e) {
            return redirect()->route('roles.index')->with('error', 'Ocurrió un error al exportar los datos a Word: ' . $e->getMessage());
        }
    }
}
