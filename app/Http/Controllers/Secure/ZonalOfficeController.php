<?php

namespace App\Http\Controllers\Secure;

use App\DTO\ZonalOfficeDto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreZonalOfficeRequest;
use App\Http\Requests\UpdateZonalOfficeRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Models\ZonalOffice;
use App\Services\ZonalOfficeServices;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;


class ZonalOfficeController extends Controller
{
    protected $zonalOfficeServices;
    public $carbon;
    public function __construct()
    {
        $this->zonalOfficeServices = new ZonalOfficeServices();
        $this->carbon = new Carbon();
    }

    public function index(Request $request)
    {
        $pageTitle = "Zonal Office Setup";
        return view('secure.tenders.zonal_office.index', compact('pageTitle'));
    }

    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $zonal_offices = $this->zonalOfficeServices->findAll();
            return DataTables::of($zonal_offices)
                ->addColumn('action', function ($zonal_office) {
                    $button = '';
                    if (auth()->user()->can('view zonal office')) {
                        $button .= '<a href="' . route('zonal_office.show', $zonal_office->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit zonal office')) {
                        $button .= '<a href="' . route('zonal_office.edit', $zonal_office->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete zonal office')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-zonal-office" data-id="' . $zonal_office->id . '" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function create()
    {
        $pageTitle = 'Add Zonal Office';
        return view('secure.tenders.zonal_office.create', compact('pageTitle'));
    }

    public function store(StoreZonalOfficeRequest $request)
    {

        try {
            $zonalOfficeDto = new ZonalOfficeDto(
                $request->input('title'),
                $request->input('title_hi'),
                0, // is_approved
                0, // is_published
                null, // remarks
                null, // publish_remark
                auth()->user()->id,
                $this->carbon->now(),
                auth()->user()->id,
                $this->carbon->now()
            );

            $zonalOffice = $this->zonalOfficeServices->create($zonalOfficeDto);

            if (!$zonalOffice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving Zonal Office.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Zonal Office created successfully!'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Zonal Office addition failed: ' . $e->getMessage());
            $msg = $e->getMessage();
            if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                return response()->json([
                    'success' => false,
                    'message' => $msg
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'View Zonal Office';
        $zonal_office = $this->zonalOfficeServices->findById($id);
        return view('secure.tenders.zonal_office.show', compact('zonal_office', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Zonal Office';
        $zonal_office = $this->zonalOfficeServices->findById($id);
        return view('secure.tenders.zonal_office.edit', compact('zonal_office', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateZonalOfficeRequest $request, ZonalOffice $zonalOffice)
    {
        try {
            $zonalOfficeDto = new ZonalOfficeDto(

                $request->input('title'),
                $request->input('title_hi'),
                0, // is_approved
                0, // is_published
                null, // remarks
                null, // publish_remark
                $zonalOffice->created_by,
                $zonalOffice->created_at,
                auth()->user()->id,
                $this->carbon->now()
            );

            $updated = $this->zonalOfficeServices->update($zonalOfficeDto, $zonalOffice->id);
            if (!$updated) {
                return response()->json([
                    'data' => $zonalOfficeDto,
                    'success' => false,
                    'message' => 'Error while updating Zonal Office.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Zonal Office updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Zonal Office updation failed: ' . $e->getMessage());
            $msg = $e->getMessage();
            if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                return response()->json([
                    'success' => false,
                    'message' => $msg
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $zonalOffice = $this->zonalOfficeServices->delete($id);
            if (!$zonalOffice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting Zonal Office.',
                ], 500);
            }

            return response()->json(['message' => 'Zonal Office moved to trash successfully!']);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Zonal Office deletion failed: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, ZonalOffice $zonalOffice)
    {
        try {
            $updated = $this->zonalOfficeServices->approve(
                $zonalOffice->id,
                $request->is_approved,
                strip_tags($request->remarks) ?? null,
                $zonalOffice->publish_remark
            );

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while approving record.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Approval decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Zonal Office approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }


    public function publish(PublishRequest $request, ZonalOffice $zonalOffice)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $zonalOffice->is_approved == 1 || $isPublished == 1 ? 1 : $zonalOffice->is_approved;
            $remarks = $zonalOffice->is_approved == 1
                ? $zonalOffice->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $zonalOffice->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->zonalOfficeServices->publish(
                $zonalOffice->id,
                $isApproved,
                $remarks,
                $isPublished,
                $publishRemark
            );

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while publishing record.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Publish decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Zonal Office publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function fetchAllForPublic()
    {
        $zonal_office = $this->zonalOfficeServices->findForPublic();

        return response()->json([
            'success' => true,
            'data' => $zonal_office,
            'lastUpdatedOn' => ZonalOffice::getLastUpdatedOrCreatedAt(),
        ]);
    }

    public function fetchByIdForPublic($id)
    {
        $zonal_office = $this->zonalOfficeServices->fetchByIdForPublic($id);

        return response()->json([
            'success' => true,
            'data' => $zonal_office
        ]);
    }
}
