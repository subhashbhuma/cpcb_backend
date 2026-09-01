<?php

namespace App\Http\Controllers\Secure;

use App\DTO\AgraAirQualityDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAgraAirQualityRequest;
use App\Http\Requests\UpdateAgraAirQualityRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Models\AgraAirQuality;
use App\Models\QualityZone;
use App\Services\AgraAirQualityService;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Http\Resources\PublicQualityZoneResource;

class AgraAirQualityController extends Controller
{
    use FileUploadTrait;

    protected $agraAirQualityService;

    public function __construct(AgraAirQualityService $agraAirQualityService)
    {
        $this->agraAirQualityService = $agraAirQualityService;
    }

    public function index(Request $request)
    {
        $pageTitle = 'Agra Air Quality';
        return view('secure.agra_air_quality.index', compact('pageTitle'));
    }

    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $records = $this->agraAirQualityService->getAllAgraAirQualitiesDataTable();
            return DataTables::of($records)
                ->addColumn('quality_zone', function ($data) {
                    return $data->qualityZone ? $data->qualityZone->title : 'N/A';
                })
                ->addColumn('file_name', function ($data) {
                    if ($data->file_name) {
                        return "<a href=\"" . generate_file_view_path_for_backend($data->file_en_url) . "\" target='_BLANK'>View Document</a>";
                    }
                    return '';
                })
                ->addColumn('file_name_hi', function ($data) {
                    if ($data->file_name_hi) {
                        return "<a href=\"" . generate_file_view_path_for_backend($data->file_hi_url) . "\" target='_BLANK'>View Document</a>";
                    }
                    return '';
                })
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
                ->addColumn('action', function ($data) {
                    $button = '';
                    if (auth()->user()->can('view agra air quality')) {
                        $button .= '<a href="' . route('agra-air-qualities.show', $data->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit agra air quality')) {
                        $button .= '<a href="' . route('agra-air-qualities.edit', $data->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete agra air quality')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-record" data-id="' . $data->id . '" title="Delete Record">
                            <i class="fa fa-trash"></i>
                        </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'file_name', 'file_name_hi', 'status', 'approval_status'])
                ->make(true);
        }
    }

    public function create()
    {
        $pageTitle = 'Add Agra Air Quality';
        $qualityZones = QualityZone::where('is_published', 1)->get();
        return view('secure.agra_air_quality.create', compact('pageTitle', 'qualityZones'));
    }

    public function store(StoreAgraAirQualityRequest $request)
    {
        try {
            $dto = new AgraAirQualityDto(
                $request->quality_zone_id,
                $request->title,
                $request->title_hi,
                $request->file('file_name'),
                $request->file('file_name_hi'),
                $request->for_date,
                0, // is_approved
                0, // is_published
                null, // remarks
                null, // publish_remark
                auth()->id(),
                auth()->id()
            );

            $result = $this->agraAirQualityService->createAgraAirQuality($dto);

            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving record.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Record created successfully!',
                'redirect_url' => route('agra-air-qualities.index')
            ], 201);
        } catch (\Exception $e) {
            Log::error('Agra Air Quality addition failed: ' . $e->getMessage());
            $msg = $e->getMessage();
            if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                return response()->json(['success' => false, 'message' => $msg]);
            }
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function show(string $id)
    {
        $pageTitle = 'View Agra Air Quality';
        $record = $this->agraAirQualityService->getAgraAirQualityById($id);
        return view('secure.agra_air_quality.show', compact('record', 'pageTitle'));
    }

    public function edit(string $id)
    {
        $pageTitle = 'Edit Agra Air Quality';
        $record = $this->agraAirQualityService->getAgraAirQualityById($id);
        $qualityZones = QualityZone::where('is_published', 1)->get();
        return view('secure.agra_air_quality.edit', compact('record', 'pageTitle', 'qualityZones'));
    }

    public function update(UpdateAgraAirQualityRequest $request, string $id)
    {
        try {
            $record = $this->agraAirQualityService->getAgraAirQualityById($id);
            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Record not found']);
            }

            $dto = new AgraAirQualityDto(
                $request->quality_zone_id,
                $request->title,
                $request->title_hi,
                $request->file('file_name'),
                $request->file('file_name_hi'),
                $request->for_date,
                0, // Reset is_approved
                0, // Reset is_published
                null, // Reset remarks
                null, // Reset publish_remark
                $record->created_by,
                auth()->id()
            );

            $result = $this->agraAirQualityService->updateAgraAirQuality($id, $dto);

            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating record.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Record updated successfully!',
                'redirect_url' => route('agra-air-qualities.index')
            ], 200);
        } catch (\Exception $e) {
            Log::error('Agra Air Quality update failed: ' . $e->getMessage());
            $msg = $e->getMessage();
            if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                return response()->json(['success' => false, 'message' => $msg]);
            }
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $result = $this->agraAirQualityService->deleteAgraAirQuality($id);

            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting record.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Record deleted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Agra Air Quality deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function approve(ApproveRequest $request, string $id)
    {
        try {
            $record = $this->agraAirQualityService->getAgraAirQualityById($id);
            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Record not found']);
            }

            $updated = $this->agraAirQualityService->approve(
                $id,
                $request->is_approved,
                $request->remarks ? strip_tags($request->remarks) : null,
                $record->publish_remark
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
            Log::error('Agra Air Quality approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function publish(PublishRequest $request, string $id)
    {
        try {
            $record = $this->agraAirQualityService->getAgraAirQualityById($id);
            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Record not found']);
            }

            $isPublished = (int) $request->input('is_published');
            $isApproved = $record->is_approved == 1 || $isPublished == 1 ? 1 : $record->is_approved;
            $remarks = $record->is_approved == 1
                ? $record->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $record->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->agraAirQualityService->publish(
                $id,
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
            Log::error('Agra Air Quality publish failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function fetchAllForPublic()
    {
        try {
            $records = $this->agraAirQualityService->getAllAgraAirQualities();
            return response()->json([
                'success' => true,
                'data' => $records
            ], 200);
        } catch (\Exception $e) {
            Log::error('Fetch public Agra Air Quality failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function fetchAllForPublicDataTable(Request $request)
    {
        if ($request->ajax()) {
            $records = $this->agraAirQualityService->getAllAgraAirQualities();
            return DataTables::of($records)
                ->addColumn('quality_zone', function ($data) {
                    return $data->qualityZone ? $data->qualityZone->title : 'N/A';
                })
                ->addColumn('file_name', function ($data) {
                    if ($data->file_name) {
                        return "<a href=\"" . $data->file_en_url . "\" target='_BLANK'>View Document</a>";
                    }
                    return '';
                })
                ->addColumn('file_name_hi', function ($data) {
                    if ($data->file_name_hi) {
                        return "<a href=\"" . $data->file_hi_url . "\" target='_BLANK'>View Document</a>";
                    }
                    return '';
                })
                ->rawColumns(['file_name', 'file_name_hi'])
                ->make(true);
        }
    }

    public function getMonthlyAirQuality(Request $request)
    {
        $date = null;
        if (!$request->get('date')) {
            $date = Carbon::now();
        } else {
            $date = Carbon::parse($request->get('date'));
        }
        $month = $date->month;
        $year = $date->year;

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $daysInMonth = $startDate->daysInMonth;

        $zones = QualityZone::orderBy('id', "asc")->get();
        $records = AgraAirQuality::whereMonth('for_date', $month)
            ->whereYear('for_date', $year)
            ->get()
            ->groupBy(['for_date', 'quality_zone_id']);

        $report = [];

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $currentDate = Carbon::createFromDate($year, $month, $i)->format('Y-m-d');
            $displayDate = Carbon::parse($currentDate)->format('d-m-Y');

            $row = ['date' => $displayDate];

            foreach ($zones as $zone) {
                $record = $records->get($currentDate)?->get($zone->id)?->first();

                $row['zones'][$zone->title] = [
                    'zone_id' => $zone->id,
                    'zone_title' => $zone->title,
                    'zone_title_hi' => $zone->title_hi,
                    'title' => $record ? $record->title : null,
                    'title_hi' => $record ? $record->title_hi : null,
                    'file_path_en' => $record ? $record->file_path_en : null,
                    'file_path_hi' => $record ? $record->file_path_hi : null,
                    'display_text' => $record ? $displayDate : Carbon::parse($currentDate)->format('d.m.Y'),
                ];
            }

            $report[] = $row;
        }

        return response()->json([
            'month_name' => $startDate->format('F, Y'),
            'zones' => $zones->pluck('title'),
            'report' => $report,
            'lastUpdatedOn' => AgraAirQuality::getLastUpdatedOrCreatedAt(),
            'zone_lists' => PublicQualityZoneResource::collection($zones),
            'agra_air_quality_file' => AgraAirQuality::getAgraAirQualityFile()
        ]);
    }

    public function searchAirQualityFile(Request $request)
    {
        $searchDate = Carbon::parse($request->get('date'))->format('Y-m-d');
        $zoneId = $request->get('zone_id');

        $record = AgraAirQuality::where('quality_zone_id', $zoneId)
            ->whereDate('for_date', $searchDate)
            ->first();

        if ($record) {
            return response()->json([
                'status' => true,
                'data' => [
                    'title' => $record->title,
                    'title_hi' => $record->title_hi,
                    'file_path_en' => $record->file_path_en,
                    'file_path_hi' => $record->file_path_hi,
                    'for_date' => Carbon::parse($record->for_date)->format('d-m-Y'),
                ]
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'No record found for the selected date and zone.',
            'data' => null,
        ]);
    }

    public function annualAverageFile()
    {
        $pageTitle = 'Agra Report File - Annual Average';
        $filePath = config('file_paths.AGRA_AIR_QUALITY_ANNUAL_AVERAGE_FILE_EN_PATH');
        $fileName = 'AAQM_data-Project_Office_Agra-2002-2023.pdf';

        $fileExists = \Storage::disk('public')->exists($filePath . '/' . $fileName);
        $fileUrl = $fileExists ? asset('storage/' . $filePath . '/' . $fileName) : null;

        return view('secure.agra_air_quality.annual_average_file', compact('pageTitle', 'fileExists', 'fileUrl', 'fileName'));
    }

    public function uploadAnnualAverageFile(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:pdf|max:10240',
            ]);

            $filePath = config('file_paths.AGRA_AIR_QUALITY_ANNUAL_AVERAGE_FILE_EN_PATH');
            $fileName = 'AAQM_data-Project_Office_Agra-2002-2023.pdf';

            if (\Storage::disk('public')->exists($filePath . '/' . $fileName)) {
                \Storage::disk('public')->delete($filePath . '/' . $fileName);
            }

            $file = $request->file('file');
            $file->storeAs($filePath, $fileName, 'public');

            activity('agra_air_quality_annual_average')
                ->causedBy(auth()->user())
                ->withProperties([
                    'file_name' => $fileName,
                    'file_path' => $filePath,
                    'file_size' => $file->getSize(),
                    'original_name' => $file->getClientOriginalName(),
                ])
                ->log('Annual average file uploaded/replaced');

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully!',
                'file_url' => asset('storage/' . $filePath . '/' . $fileName)
            ], 200);
        } catch (\Exception $e) {
            Log::error('Annual average file upload failed: ' . $e->getMessage());
            $msg = $e->getMessage();
            if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                return response()->json(['success' => false, 'message' => $msg]);
            }
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function deleteAnnualAverageFile()
    {
        try {
            $filePath = config('file_paths.AGRA_AIR_QUALITY_ANNUAL_AVERAGE_FILE_EN_PATH');
            $fileName = 'AAQM_data-Project_Office_Agra-2002-2023.pdf';

            if (\Storage::disk('public')->exists($filePath . '/' . $fileName)) {
                \Storage::disk('public')->delete($filePath . '/' . $fileName);

                activity('agra_air_quality_annual_average')
                    ->causedBy(auth()->user())
                    ->withProperties([
                        'file_name' => $fileName,
                        'file_path' => $filePath,
                    ])
                    ->log('Annual average file deleted');

                return response()->json([
                    'success' => true,
                    'message' => 'File deleted successfully!'
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'File not found.'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Annual average file deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }
}
