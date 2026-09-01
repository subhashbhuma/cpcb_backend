<?php

namespace App\Http\Controllers\Secure;

use App\DTO\UserDto;
use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Designation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EmployeesImport;

class EmployeeController extends Controller
{
    protected $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    /**
     * Display employee listing page
     */
    public function index(Request $request)
    {
        $pageTitle = 'Employee Setup';
        return view('secure.employee.index', compact('pageTitle'));
    }

    /**
     * Fetch employees for DataTable (AJAX)
     */
    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $employees = User::role('EMPLOYEE')->get();

            return DataTables::of($employees)
                ->addColumn('designation', function ($employee) {
                    return $employee->designation ? $employee->designation->title : 'N/A';
                })
                ->addColumn('action', function ($employee) {
                    $button = '';
                    if (auth()->user()->can('edit employee')) {
                        $button .= '<a href="' . route('employee.edit', $employee->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete employee')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-employee" data-id="' . $employee->id . '" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>';
                    }
                    return $button;
                })
                ->editColumn('pan_no', function ($employee) {
                    return substr($employee->pan_no, 0, 5) . '****' . substr($employee->pan_no, -1);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    /**
     * Show create employee form
     */
    public function create()
    {
        $pageTitle = 'Add Employee';
        $designations = Designation::where('is_published', 1)->where('is_approved', 1)->get();
        return view('secure.employee.create', compact('pageTitle', 'designations'));
    }

    /**
     * Store new employee
     */
    public function store(StoreEmployeeRequest $request)
    {
        try {
            $password = $request->input('pan_no');
            $email = $request->input('email') ?? $request->input('emp_code'); // Use emp_code as email if email is not provided to ensure uniqueness
            $userDto = new UserDto(
                $request->input('name'),
                $email,
                $request->input('mobile_number'),
                $password,
                ['EMPLOYEE'], // Auto-assign Employee role
                auth()->user()->id,
                auth()->user()->id
            );

            $user = $this->userService->create($userDto);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving employee.',
                    'key' => CustomHelper::setEncryptionKey()
                ], 500);
            }


            // Update employee-specific fields
            $user->update([
                'designation_id' => $request->input('designation_id'),
                'emp_code' => $request->input('emp_code'),
                'level' => $request->input('level'),
                'cell' => $request->input('cell'),
                'posted_at' => $request->input('posted_at'),
                'pan_no' => $request->input('pan_no'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Employee created successfully!',
                'redirect_url' => route('employee.index'),
                'key' => CustomHelper::setEncryptionKey()
            ], 201);
        } catch (\Exception $e) {
            Log::error('Employee creation failed: ' . $e->getMessage());
            $msg = $e->getMessage();
            if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                return response()->json([
                    'success' => false,
                    'message' => $msg,
                    'key' => CustomHelper::setEncryptionKey()
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again later.',
                'key' => CustomHelper::setEncryptionKey()
            ], 500);
        }
    }

    /**
     * Show edit employee form
     */
    public function edit(User $employee)
    {
        $designations = Designation::where('is_published', 1)->where('is_approved', 1)->get();
        $pageTitle = 'Edit Employee';
        return view('secure.employee.edit', compact('employee', 'designations', 'pageTitle'));
    }

    /**
     * Update employee
     */
    public function update(UpdateEmployeeRequest $request, User $employee)
    {
        try {
            $password = $request->input('pan_no');
            $userDto = new UserDto(
                $request->input('name'),
                $request->input('email'),
                $request->input('mobile_number'),
                $password,
                ['EMPLOYEE'], // Ensure Employee role is assigned
                $employee->created_by,
                auth()->user()->id
            );

            $empl_pan = $employee->pan_no;
            $updatedUser = $this->userService->update($userDto, $employee->id);

            if (!$updatedUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating employee.',
                    'key' => CustomHelper::setEncryptionKey()
                ], 500);
            }

            // Update employee-specific fields
            $updatedUser->update([
                'designation_id' => $request->input('designation_id'),
                'emp_code' => $request->input('emp_code'),
                'level' => $request->input('level'),
                'cell' => $request->input('cell'),
                'posted_at' => $request->input('posted_at'),
                'pan_no' => $request->input('pan_no') != '' ? $request->input('pan_no') : $empl_pan,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Employee updated successfully!',
                'redirect_url' => route('employee.index'),
                'key' => CustomHelper::setEncryptionKey()
            ], 200);
        } catch (\Exception $e) {
            Log::error('Employee update failed: ' . $e->getMessage());
            $msg = $e->getMessage();
            if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                return response()->json([
                    'success' => false,
                    'message' => $msg,
                    'key' => CustomHelper::setEncryptionKey()
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again later.',
                'key' => CustomHelper::setEncryptionKey()
            ], 500);
        }
    }

    /**
     * Delete employee
     */
    public function destroy(User $employee)
    {
        try {
            $this->userService->delete($employee->id);

            return response()->json(['message' => 'Employee deleted successfully!']);
        } catch (\Exception $e) {
            Log::error('Employee delete failed: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    /**
     * Import employees from Excel
     */
    public function bulkImport(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls,csv',
            ]);

            Excel::import(new EmployeesImport, $request->file('file'));

            return response()->json([
                'success' => true,
                'message' => 'Employees imported successfully!',
                'redirect_url' => route('employee.index')
            ], 200);
        } catch (\Exception $e) {
            Log::error('Employee bulk import failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error during import: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download employee Excel format template
     */
    public function downloadFormat()
    {
        $path = storage_path('app/public/employee_excel_format/employee-data.xlsx');

        if (!file_exists($path)) {
            Log::error('Employee template not found at: ' . $path);
            abort(404, 'Template not found.');
        }

        return response()->download($path, 'employee-data.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ]);
    }
}
