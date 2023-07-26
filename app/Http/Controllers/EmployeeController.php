<?php

/* Declaring the namespace of the file. */

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
/* Importing the Request class from the Illuminate\Http namespace. */
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

/* Extending the Controller class. */

class EmployeeController extends Controller
{
    public function index()
    {
        //select, consulta multitabla de los empleados, ocupa id y nombre,
        $employees = Employee::select(
            'employees.id',
            'employees.name',
            'email',
            'phone',
            /* Renaming the department column to department. */
            'department_id',
            'departments.name as department'
        )
            // la info se une con la tabla deptos mediante el id de la tabla deptos
            // cuando sea igual a la columna department_id de la tabla empleados
            ->join('departments', 'departments.id', '=', 'employees.department_id')
            // paginacion de 10
            ->paginate(10);
        $departments = Department::all();
        // la info de ambas tablas se retorna hacia la vista a traves de Inertia
        // en un prop van las variables empleados y deptos
        return Inertia::render('Employees/Index', [
            'employees' => $employees,
            'departments' => $departments
        ]);
    }
    public function store(Request $request)
    {
        //validar la info de los campos con sus respectivas especificaciones
        $request->validate([
            'name' => 'required|max:150',
            'email' => 'required|max:80',
            'phone' => 'required|max:15',
            'department_id' => 'required|numeric'
        ]);
        // se crea un nuevo empleado con la info del request
        $employee = new Employee($request->input());
        $employee->save();
        return redirect('employees');
    }
    public function update(Request $request, Employee $employee)
    {
        //
        $request->validate([
            'name' => 'required|max:150',
            'email' => 'required|max:80',
            'phone' => 'required|max:15',
            'department_id' => 'required|numeric'
        ]);
        $employee->update($request->input());
        return redirect('employees');
    }
    public function destroy(Employee $employee)
    /* A blade directive that is used to open a section. */
    {
        $employee->delete();
        return redirect('employees');
    }
    public function EmployeeByDepartment()
    {
        // funcion para la grafica
        // en el select se calcula el numero de empleados y se renombra count, incluye nombre de los deptos
        // deptos
        $data = Employee::select(DB::raw('count(employees.id) as count, departments.name'))
            ->join('departments', 'departments.id', '=', 'employees.department_id')
            // se agrupan por nombre de depto
            ->groupBy('departments.name')->get();
        // la info de la consulta se envia a la vista Graphic con la info en un prop
        return Inertia::render('Employees/Graphic', ['data' => $data]);
    }
    public function reports()
    {
        $employees = Employee::select(
            'employees.id',
            'employees.name',
            'email',
            'phone',
            'department_id',
            'departments.name as department'
        )
            ->join('departments', 'departments.id', '=', 'employees.department_id')
            ->get();
        $departments = Department::all();
        return Inertia::render('Employees/Reports', [
            'employees' => $employees,
            'departments' => $departments
        ]);
    }
    /* Closing the class. */
}
