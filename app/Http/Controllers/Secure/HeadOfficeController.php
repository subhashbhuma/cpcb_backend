<?php

namespace App\Http\Controllers\Secure;

use Mews\Purifier\Facades\Purifier;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHeadOfficeRequest;
use App\Http\Requests\UpdateHeadOfficeRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Services\HeadOfficeService;
use App\Services\DivisionService;
use App\DTO\HeadOfficeDto;
use App\Models\HeadOffice;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Resources\PublicHeadOfficeResource;

class HeadOfficeController extends Controller
{
    protected $headOfficeService;
    protected $divisionService;

    public function __construct(HeadOfficeService $headOfficeService, DivisionService $divisionService)
    {
        $this->headOfficeService = $headOfficeService;
        $this->divisionService = $divisionService;
    }

    public function index()
    {
        $pageTitle = 'Head Office Setup';
        $addLink = route('head_offices.create');
        return view('secure.head_offices.index', compact('pageTitle', 'addLink'));
    }

    public function fetchForDatatable(Request $request)
    {
        $data = $this->headOfficeService->findAll();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $editUrl = route('head_offices.edit', $row->id);
                $deleteUrl = route('head_offices.destroy', $row->id);
                $viewUrl = route('head_offices.show', $row->id);
                $btn = '';

                if (auth()->user()->can('view head office')) {
                    $btn .= '<a href="' . $viewUrl . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                }

                if (auth()->user()->can('edit head office')) {
                    $btn .= '<a href="' . $editUrl . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                }

                if (auth()->user()->can('delete head office')) {
                    $btn .= '<button type="button" class="btn btn-sm btn-danger delete-btn" data-url="' . $deleteUrl . '" title="Delete"><i class="fa fa-trash"></i></button>';
                }

                return $btn;
            })
            ->addColumn('is_approved', function ($row) {
                if ($row->is_approved == 1) {
                    return '<span class="badge bg-success">Approved</span>';
                } elseif ($row->is_approved == 2) {
                    return '<span class="badge bg-danger">Rejected</span>';
                } else {
                    return '<span class="badge bg-warning">Pending</span>';
                }
            })
            ->addColumn('is_published', function ($row) {
                return $row->is_published
                    ? '<span class="badge bg-success">Published</span>'
                    : '<span class="badge bg-secondary">Draft</span>';
            })
            ->rawColumns(['action', 'is_approved', 'is_published'])
            ->make(true);
    }

    public function create()
    {
        $pageTitle = 'Add Head Office';
        $backLink = route('head_offices.index');
        $divisions = $this->divisionService->findPublished();
        return view('secure.head_offices.create', compact('pageTitle', 'backLink', 'divisions'));
    }

    public function store(StoreHeadOfficeRequest $request)
    {
        try {
            $dto = new HeadOfficeDto(
                $request->input('division_id'),
                strip_tags(html_entity_decode($request->input('title'))),
                strip_tags(html_entity_decode($request->input('title_hi'))),
                strip_tags(html_entity_decode($request->input('email'))) ?? null,
                strip_tags(html_entity_decode($request->input('ext_number'))) ?? null,
                $request->input('description'),
                $request->input('description_hi'),
                $request->input('order') ?? 0,
                0,
                0,
                null,
                null,
                auth()->user()->id,
                auth()->user()->id,
                $request->file('image')
            );

            $this->headOfficeService->create(
                $dto,
                $request->input('personnels', []),
                $request->input('profile_activities', [])
            );

            return response()->json([
                'success' => true,
                'message' => 'Head Office created successfully.',
                'redirect_url' => route('head_offices.index'),
            ], 200);
        } catch (\Exception $e) {
            Log::error('Head Office creation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error while creating Head Office.',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Head Office';
        $backLink = route('head_offices.index');
        $headOffice = $this->headOfficeService->findById($id);
        $divisions = $this->divisionService->findPublished();

        return view('secure.head_offices.edit', compact('pageTitle', 'backLink', 'headOffice', 'divisions'));
    }

    public function show($id)
    {
        $pageTitle = 'View Head Office';
        $backLink = route('head_offices.index');
        $headOffice = $this->headOfficeService->findById($id);

        return view('secure.head_offices.show', compact('pageTitle', 'backLink', 'headOffice'));
    }

    public function update(UpdateHeadOfficeRequest $request, $id)
    {
        try {
            $headOffice = $this->headOfficeService->findById($id);

            $dto = new HeadOfficeDto(
                $request->input('division_id'),
                strip_tags(html_entity_decode($request->input('title'))),
                strip_tags(html_entity_decode($request->input('title_hi'))),
                strip_tags(html_entity_decode($request->input('email'))) ?? null,
                strip_tags(html_entity_decode($request->input('ext_number'))) ?? null,
                $request->input('description'),
                $request->input('description_hi'),
                $request->input('order') ?? 0,
                0,
                0,
                null,
                null,
                $headOffice->created_by,
                auth()->user()->id,
                $request->file('image')
            );

            $this->headOfficeService->update(
                $dto,
                $id,
                $request->input('personnels', []),
                $request->input('profile_activities', [])
            );

            return response()->json([
                'success' => true,
                'message' => 'Head Office updated successfully.',
                'redirect_url' => route('head_offices.index'),
            ], 200);
        } catch (\Exception $e) {
            Log::error('Head Office updation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error while updating Head Office.',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        $this->headOfficeService->delete($id);
        return response()->json(['success' => true, 'message' => 'Head Office deleted successfully.']);
    }

    public function approve(ApproveRequest $request, $id)
    {
        try {
            $headOffice = $this->headOfficeService->findById($id);

            $dto = new HeadOfficeDto(
                $headOffice->division_id,
                $headOffice->title,
                $headOffice->title_hi,
                $headOffice->email,
                $headOffice->ext_number,
                $headOffice->description,
                $headOffice->description_hi,
                $headOffice->order,
                $request->input('is_approved'),
                0,
                strip_tags($request->input('remarks')) ?? null,
                $headOffice->publish_remark,
                $headOffice->created_by,
                auth()->user()->id
            );

            $this->headOfficeService->approve($dto, $id);

            return response()->json([
                'success' => true,
                'message' => 'Approval decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Head Office approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error while approving head office.',
            ], 500);
        }
    }

    public function publish(PublishRequest $request, $id)
    {
        try {
            $headOffice = $this->headOfficeService->findById($id);
            $isPublished = (int) $request->input('is_published');
            $isApproved = $headOffice->is_approved == 1 || $isPublished == 1 ? 1 : $headOffice->is_approved;
            $remarks = $headOffice->is_approved == 1
                ? $headOffice->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $headOffice->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $dto = new HeadOfficeDto(
                $headOffice->division_id,
                $headOffice->title,
                $headOffice->title_hi,
                $headOffice->email,
                $headOffice->ext_number,
                $headOffice->description,
                $headOffice->description_hi,
                $headOffice->order,
                $isApproved,
                $isPublished,
                $remarks,
                $publishRemark,
                $headOffice->created_by,
                auth()->user()->id
            );

            $this->headOfficeService->publish($dto, $id);

            return response()->json([
                'success' => true,
                'message' => 'Publish decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Head Office publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error while publishing head office.',
            ], 500);
        }
    }
    public function findAllForPublic()
    {
        return response()->json([
            'success' => true,
            'data' => PublicHeadOfficeResource::collection($this->headOfficeService->findForPublic()),
            'lastUpdatedOn' => HeadOffice::getLastUpdatedOrCreatedAt(),
        ]);
    }


}
