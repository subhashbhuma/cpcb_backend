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
use Illuminate\Support\Facades\DB;
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
        $requestedDate = $request->get('date');
        $requestedMonth = $request->get('month');
        $requestedYear = $request->get('year');
        $isArchive = $request->get('type') === 'archive' || $request->boolean('is_archive');

        $month = null;
        $year = null;

        if ($requestedMonth && $requestedYear) {
            $month = (int)$requestedMonth;
            $year = (int)$requestedYear;
        } elseif ($requestedDate) {
            try {
                $parsed = Carbon::parse($requestedDate);
                $reqM = $parsed->month;
                $reqY = $parsed->year;

                // Check if this requested month has data
                $hasData = AgraAirQuality::whereMonth('for_date', $reqM)
                    ->whereYear('for_date', $reqY)
                    ->exists();

                if ($hasData) {
                    $month = $reqM;
                    $year = $reqY;
                }
            } catch (\Exception $e) {
                // Fallback below
            }
        }

        // Fetch distinct available months with data
        $distinctMonths = DB::table('agra_air_qualities')
            ->whereNull('deleted_at')
            ->whereNotNull('for_date')
            ->where('for_date', '>', '1970-01-01')
            ->selectRaw("to_char(for_date, 'YYYY-MM') as ym, EXTRACT(YEAR FROM for_date)::integer as year, EXTRACT(MONTH FROM for_date)::integer as month, COUNT(*)::integer as record_count")
            ->groupBy('ym', 'year', 'month')
            ->orderBy('ym', 'desc')
            ->get();

        // If no month/year or requested month has no data, fallback
        if (!$month || !$year) {
            if ($isArchive && $distinctMonths->count() > 1) {
                // For archive by default, show one month previous to the latest available month
                $target = $distinctMonths->get(1);
                $month = (int)$target->month;
                $year = (int)$target->year;
            } elseif ($distinctMonths->isNotEmpty()) {
                $target = $distinctMonths->first();
                $month = (int)$target->month;
                $year = (int)$target->year;
            } else {
                $now = Carbon::now();
                $month = $now->month;
                $year = $now->year;
            }
        }

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();

        $zones = QualityZone::orderBy('id', "asc")->get();
        $records = AgraAirQuality::whereMonth('for_date', $month)
            ->whereYear('for_date', $year)
            ->orderBy('for_date', 'asc')
            ->get();

        // Get only the dates that actually have data
        $datesWithData = $records->pluck('for_date')
            ->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))
            ->unique()
            ->sort()
            ->values();

        $recordsByDateAndZone = $records->groupBy(function ($item) {
            return Carbon::parse($item->for_date)->format('Y-m-d');
        })->map(function ($dateRecords) {
            return $dateRecords->groupBy('quality_zone_id');
        });

        $report = [];
        foreach ($datesWithData as $dateStr) {
            $displayDate = Carbon::parse($dateStr)->format('d-m-Y');
            $row = ['date' => $displayDate];

            foreach ($zones as $zone) {
                $record = $recordsByDateAndZone->get($dateStr)?->get($zone->id)?->first();

                $row['zones'][$zone->title] = [
                    'zone_id' => $zone->id,
                    'zone_title' => $zone->title,
                    'zone_title_hi' => $zone->title_hi,
                    'title' => $record ? $record->title : null,
                    'title_hi' => $record ? $record->title_hi : null,
                    'file_path_en' => $record ? $record->file_path_en : null,
                    'file_path_hi' => $record ? $record->file_path_hi : null,
                    'display_text' => $record ? $displayDate : Carbon::parse($dateStr)->format('d.m.Y'),
                ];
            }

            $report[] = $row;
        }

        $availableMonths = $distinctMonths->map(function ($row) {
            $carbonDate = Carbon::createFromDate((int)$row->year, (int)$row->month, 1);
            return [
                'ym' => $row->ym,
                'year' => (int)$row->year,
                'month' => (int)$row->month,
                'month_name' => $carbonDate->format('F, Y'),
                'count' => (int)$row->record_count,
            ];
        });

        $availableYears = $availableMonths->pluck('year')->unique()->values();

        return response()->json([
            'month_name' => $startDate->format('F, Y'),
            'year' => $year,
            'month' => $month,
            'zones' => $zones->pluck('title'),
            'report' => $report,
            'lastUpdatedOn' => AgraAirQuality::getLastUpdatedOrCreatedAt(),
            'zone_lists' => PublicQualityZoneResource::collection($zones),
            'agra_air_quality_file' => AgraAirQuality::getAgraAirQualityFile(),
            'available_months' => $availableMonths,
            'available_years' => $availableYears,
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
