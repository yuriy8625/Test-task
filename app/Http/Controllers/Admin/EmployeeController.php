<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Models\Employee;
use App\Models\Position;
use App\Services\EmployeeService;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\Datatables\Datatables;

class EmployeeController extends Controller
{
    protected $employeeService;

    public  function __construct(EmployeeService $employeeService)
    {
        set_time_limit(8000000);
        $this->employeeService = $employeeService;
    }

    /**
     * @return Factory|View
     * @throws Exception
     */
    public function index()
    {
        if (request()->ajax()) {
            $data = Datatables::of(Employee::query()->with(['position']))
                ->addColumn('action', function ($employees) {
                    return '<a href="' . route('admin.employees.form', ['employee' => $employees->id]) . '" style="color: grey" title="edit"><i class="fa fa-edit"></i></a>
                            <a class="deleteEmployee" data-id="'. $employees->id .'" data-name="'. $employees->name .'" title="delete"><i style="color: grey" class="fa fa-trash"></i></a>';
                })
                ->editColumn('position', function($employees)
                {
                    return $employees->position->name;
                })
                ->rawColumns(['action'])
                ->addIndexColumn();

            return $data->make(true);
        }

        return view('admin.employee.employees');
    }

    /**
     * @param Employee|null $employee
     * @return Factory|View
     */
    public function form(Employee $employee = null)
    {

        $positions = Position::all();
        $parents = Employee::where('level', '!=', 6)->where('is_parent', '=', false)->get();

        if ($employee && $employee->parent) {
            $parents->push($employee->parent);
        }

        return view('admin.employee.form', [
            'employee' => $employee ?? ['name' => ''],
            'positions' => $positions,
            'parents' => $parents,
        ]);
    }

    /**
     * @param EmployeeRequest $employeeRequest
     * @param null $employee
     * @return JsonResponse
     */
    public function edit(EmployeeRequest $employeeRequest, $employee = null)
    {
        $user = auth()->user();
        $model = new Employee();

        if((integer) $employee) {
            $model = Employee::findOrFail($employee);
        }

        $model->fill($employeeRequest->all(Employee::API_FIELDS));

        if(!(integer) $employee){
            $model->admin_created_id = $user->id;
        }
        $model->admin_updated_id = $user->id;

        $model->save();

        if($employeeRequest->file('file', null)){
            $this->employeeService->savePhoto($model,$employeeRequest->file('file'));
        }

        return response()->json($model, 200);

    }

    /**
     * @param Employee $employee
     * @return JsonResponse
     * @throws Exception
     */
    public function delete(Employee $employee)
    {
        $employee->delete();

        return response()->json($employee, 200);
    }
}
