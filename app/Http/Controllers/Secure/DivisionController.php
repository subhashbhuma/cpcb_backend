<?php

namespace App\Http\Controllers\Secure;

use App\DTO\DivisionDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDivisionRequest;
use App\Http\Requests\UpdateDivisionRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Models\Division;
use App\Services\DivisionService;
use App\Imports\DivisionsImport;
use App\Exports\DivisionFormatExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class DivisionController extends Controller
{
    protected DivisionService $divisionService;

    public function __construct(DivisionService $divisionService)
    {
        $this->divisionService = $divisionService;
    }

    public function index()
    {
        $pageTitle = 'Division Setup';
        return view('secure.division.index', compact('pageTitle'));
    }

    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $divisions = $this->divisionService->findAll();

            return DataTables::of($divisions)
                ->addColumn('status', function ($row) {
                    if ($row->is_published == 1) {
                        return '<span class="badge bg-success">' . $row->is_published_desc . '</span>';
                    } else {
                        return '<span class="badge bg-warning">' . $row->is_published_desc . '</span>';
                    }
                })
                ->addColumn('approval_status', function ($row) {
                    if ($row->is_approved == 1) {
                        return '<span class="badge bg-success">' . $row->is_approved_desc . '</span>';
                    } elseif ($row->is_approved == 2) {
                        return '<span class="badge bg-danger">' . $row->is_approved_desc . '</span>';
                    } else {
                        return '<span class="badge bg-warning">' . $row->is_approved_desc . '</span>';
                    }
                })
                ->addColumn('action', function ($division) {
                    $buttons = '';

                    if (auth()->user()->can('view division')) {
                        $buttons .= '<a href="' . route('division.show', $division->id) . '"
                                        class="btn btn-sm btn-primary" title="View">
                                        <i class="fa fa-eye"></i>
                                    </a> ';
                    }

                    if (auth()->user()->can('edit division')) {
                        $buttons .= '<a href="' . route('division.edit', $division->id) . '"
                                        class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a> ';
                    }

                    if (auth()->user()->can('delete division')) {
                        $buttons .= '<button class="btn btn-sm btn-danger delete-division"
                                        data-id="' . $division->id . '" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>';
                    }

                    return $buttons;
                })
                ->rawColumns(['action', 'status', 'approval_status'])
                ->make(true);
        }
    }

    public function create()
    {
        $pageTitle = 'Create Division';
        return view('secure.division.create', compact('pageTitle'));
    }

    public function store(StoreDivisionRequest $request)
    {
        try {
            $dto = new DivisionDto(
                $request->title,
                $request->title_hi,
                0, // is_approved
                0, // is_published
                $request->has('is_new') ? 1 : 0,
                null, // remarks
                null, // publish_remark
                auth()->id(),
                auth()->id()
            );

            $result = $this->divisionService->create($dto);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Division created successfully!',
                    'redirect_url' => route('division.index')
                ], 201);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error while saving division.'
            ], 500);
        } catch (\Exception $e) {
            Log::error('Division creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.'
            ], 500);
        }
    }

    public function show(string $id)
    {
        $pageTitle = 'View Division';
        $record = $this->divisionService->findById($id);
        return view('secure.division.show', compact('record', 'pageTitle'));
    }

    public function edit(string $id)
    {
        $pageTitle = 'Edit Division';
        $record = $this->divisionService->findById($id);
        return view('secure.division.edit', compact('pageTitle', 'record'));
    }

    public function update(UpdateDivisionRequest $request, string $id)
    {
        try {
            $record = $this->divisionService->findById($id);
            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Division not found']);
            }

            $dto = new DivisionDto(
                $request->title,
                $request->title_hi,
                0, // Reset is_approved
                0, // Reset is_published
                $request->has('is_new') ? 1 : 0,
                null, // Reset remarks
                null, // Reset publish_remark
                $record->created_by,
                auth()->id()
            );

            $updated = $this->divisionService->update($dto, $id);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Division updated successfully!',
                    'redirect_url' => route('division.index')
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error while saving division.'
            ], 500);
        } catch (\Exception $e) {
            Log::error('Division update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.'
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $deleted = $this->divisionService->delete($id);

            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting division.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Division deleted successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Division deletion failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, string $id)
    {
        try {
            $record = $this->divisionService->findById($id);
            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Division not found']);
            }

            $updated = $this->divisionService->approve(
                $id,
                $request->is_approved,
                $request->remarks ? strip_tags($request->remarks) : null,
                $record->publish_remark
            );

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while approving division.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Approval decision submitted successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    public function publish(PublishRequest $request, string $id)
    {
        try {
            $record = $this->divisionService->findById($id);
            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Division not found']);
            }

            $isPublished = (int) $request->input('is_published');
            $isApproved = $record->is_approved == 1 || $isPublished == 1 ? 1 : $record->is_approved;
            $remarks = $record->is_approved == 1
                ? $record->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $record->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->divisionService->publish(
                $id,
                $isApproved,
                $remarks,
                $isPublished,
                $publishRemark
            );

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while publishing division.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Publish decision submitted successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    public function findAllforPublic()
    {
        return response()->json([
            'success' => true,
            'data' => $this->divisionService->findForPublic()
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            // TRUNCATE the data before importing
            Division::truncate();

            // Import the new data
            Excel::import(new DivisionsImport, $request->file('import_file'));

            return response()->json([
                'success' => true,
                'message' => 'Data imported and previous data truncated successfully!',
                'redirect_url' => route('division.index')
            ]);
        } catch (\Exception $e) {
            Log::error('Division Import failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error during import: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadFormat()
    {
        return Excel::download(new DivisionFormatExport, 'division_import_format.xlsx');
    }
}
