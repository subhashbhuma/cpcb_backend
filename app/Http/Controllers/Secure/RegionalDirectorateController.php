<?php

namespace App\Http\Controllers\Secure;

use Mews\Purifier\Facades\Purifier;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRegionalDirectorateRequest;
use App\Http\Requests\UpdateRegionalDirectorateRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Services\RegionalDirectorateService;
use App\DTO\RegionalDirectorateDto;
use App\Models\RegionalDirectorate;
use App\Http\Resources\PublicRegionalDirectorateResource;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RegionalDirectorateController extends Controller
{
    protected $regionalDirectorateService;

    public function __construct(RegionalDirectorateService $regionalDirectorateService)
    {
        $this->regionalDirectorateService = $regionalDirectorateService;
    }

    public function index()
    {
        $pageTitle = 'Regional Directorates Setup';
        return view('secure.regional_directorates.index', compact('pageTitle'));
    }

    public function fetchForDatatable(Request $request)
    {
        $data = $this->regionalDirectorateService->findAll();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $editUrl = route('regional_directorates.edit', $row->id);
                $deleteUrl = route('regional_directorates.destroy', $row->id);
                $viewUrl = route('regional_directorates.show', $row->id);
                $btn = '';

                if (auth()->user()->can('view regional directorate')) {
                    $btn .= '<a href="' . $viewUrl . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                }

                if (auth()->user()->can('edit regional directorate')) {
                    $btn .= '<a href="' . $editUrl . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                }

                if (auth()->user()->can('delete regional directorate')) {
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
        $pageTitle = 'Add Regional Directorate';
        $backLink = route('regional_directorates.index');
        return view('secure.regional_directorates.create', compact('pageTitle', 'backLink'));
    }

    public function store(StoreRegionalDirectorateRequest $request)
    {
        try {
            $dto = new RegionalDirectorateDto(
                strip_tags(html_entity_decode($request->input('regional_directorate'))),
                strip_tags(html_entity_decode($request->input('regional_directorate_hi'))),
                strip_tags(html_entity_decode($request->input('title'))),
                strip_tags(html_entity_decode($request->input('title_hi'))),
                strip_tags(html_entity_decode($request->input('designation'))),
                strip_tags(html_entity_decode($request->input('designation_hi'))),
                strip_tags(html_entity_decode($request->input('email'))) ?? null,
                Purifier::clean(html_entity_decode($request->input('description'))) ?? null,
                Purifier::clean(html_entity_decode($request->input('description_hi'))) ?? null,
                0,
                0,
                null,
                null,
                auth()->id(),
                null,
                $request->input('order', 0),
                $request->file('image')
            );

            $this->regionalDirectorateService->create(
                $dto,
                $request->input('personnels', []),
                $request->input('profile_activities', []),
                $request->input('states', [])
            );

            return response()->json([
                'success' => true,
                'message' => 'Regional Directorate created successfully.',
                'redirect_url' => route('regional_directorates.index'),
            ], 200);
        } catch (\Exception $e) {
            Log::error('Regional Directorate creation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error while creating Regional Directorate.',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Regional Directorate';
        $backLink = route('regional_directorates.index');
        $regionalDirectorate = $this->regionalDirectorateService->findById($id);

        return view('secure.regional_directorates.edit', compact('pageTitle', 'backLink', 'regionalDirectorate'));
    }

    public function show($id)
    {
        $pageTitle = 'View Regional Directorate';
        $backLink = route('regional_directorates.index');
        $regionalDirectorate = $this->regionalDirectorateService->findById($id);

        return view('secure.regional_directorates.show', compact('pageTitle', 'backLink', 'regionalDirectorate'));
    }

    public function update(UpdateRegionalDirectorateRequest $request, $id)
    {
        try {
            $regionalDirectorate = $this->regionalDirectorateService->findById($id);

            $dto = new RegionalDirectorateDto(
                strip_tags(html_entity_decode($request->input('regional_directorate'))),
                strip_tags(html_entity_decode($request->input('regional_directorate_hi'))),
                strip_tags(html_entity_decode($request->input('title'))),
                strip_tags(html_entity_decode($request->input('title_hi'))),
                strip_tags(html_entity_decode($request->input('designation'))),
                strip_tags(html_entity_decode($request->input('designation_hi'))),
                strip_tags(html_entity_decode($request->input('email'))) ?? null,
                Purifier::clean(html_entity_decode($request->input('description'))) ?? null,
                Purifier::clean(html_entity_decode($request->input('description_hi'))) ?? null,
                0,
                0,
                null,
                null,
                $regionalDirectorate->created_by,
                auth()->id(),
                $request->input('order', 0),
                $request->file('image')
            );

            $this->regionalDirectorateService->update(
                $dto,
                $id,
                $request->input('personnels', []),
                $request->input('profile_activities', []),
                $request->input('states', [])
            );

            return response()->json([
                'success' => true,
                'message' => 'Regional Directorate updated successfully.',
                'redirect_url' => route('regional_directorates.index'),
            ], 200);
        } catch (\Exception $e) {
            Log::error('Regional Directorate update failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error while updating Regional Directorate.',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        $this->regionalDirectorateService->delete($id);
        return response()->json(['success' => true, 'message' => 'Regional Directorate deleted successfully.']);
    }

    public function approve(ApproveRequest $request, $id)
    {
        try {
            $regionalDirectorate = $this->regionalDirectorateService->findById($id);

            $dto = new RegionalDirectorateDto(
                $regionalDirectorate->regional_directorate,
                $regionalDirectorate->regional_directorate_hi,
                $regionalDirectorate->title,
                $regionalDirectorate->title_hi,
                $regionalDirectorate->designation,
                $regionalDirectorate->designation_hi,
                $regionalDirectorate->email,
                $regionalDirectorate->description,
                $regionalDirectorate->description_hi,
                $request->input('is_approved'),
                0,
                strip_tags($request->input('remarks')) ?? null,
                $regionalDirectorate->publish_remark,
                $regionalDirectorate->created_by,
                auth()->id(),
                $regionalDirectorate->order
            );

            $this->regionalDirectorateService->approve($dto, $id);

            return response()->json([
                'success' => true,
                'message' => 'Approval decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Regional Directorate approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error while approving regional directorate.',
            ], 500);
        }
    }

    public function publish(PublishRequest $request, $id)
    {
        try {
            $regionalDirectorate = $this->regionalDirectorateService->findById($id);
            $isPublished = (int) $request->input('is_published');
            $isApproved = $regionalDirectorate->is_approved == 1 || $isPublished == 1 ? 1 : $regionalDirectorate->is_approved;
            $remarks = $regionalDirectorate->is_approved == 1
                ? $regionalDirectorate->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $regionalDirectorate->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $dto = new RegionalDirectorateDto(
                $regionalDirectorate->regional_directorate,
                $regionalDirectorate->regional_directorate_hi,
                $regionalDirectorate->title,
                $regionalDirectorate->title_hi,
                $regionalDirectorate->designation,
                $regionalDirectorate->designation_hi,
                $regionalDirectorate->email,
                $regionalDirectorate->description,
                $regionalDirectorate->description_hi,
                $isApproved,
                $isPublished,
                $remarks,
                $publishRemark,
                $regionalDirectorate->created_by,
                auth()->id(),
                $regionalDirectorate->order
            );

            $this->regionalDirectorateService->publish($dto, $id);

            return response()->json([
                'success' => true,
                'message' => 'Publish decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Regional Directorate publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error while publishing regional directorate.',
            ], 500);
        }
    }

    public function findAllForPublic()
    {
        return response()->json([
            'success' => true,
            'data' => PublicRegionalDirectorateResource::collection($this->regionalDirectorateService->findForPublic()),
            'lastUpdatedOn' => RegionalDirectorate::getLastUpdatedOrCreatedAt(),
        ]);
    }
}
