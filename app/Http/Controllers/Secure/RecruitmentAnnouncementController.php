<?php

namespace App\Http\Controllers\Secure;

use App\DTO\RecruitmentAnnouncementDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRecruitmentAnnouncementRequest;
use App\Http\Requests\UpdateRecruitmentAnnouncementRequest;
use App\Models\Job;
use App\Models\JobPost;
use App\Models\RecruitmentAnnouncement;
use App\Services\RecruitmentAnnouncementService;
use App\Services\JobPostService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mews\Purifier\Facades\Purifier;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RecruitmentAnnouncementsExport;
use App\Exports\RecruitmentAnnouncementsFormatExport;
use App\Imports\RecruitmentAnnouncementsImport;

class RecruitmentAnnouncementController extends Controller
{
    protected $service;
    protected $jobPostService;

    public function __construct()
    {
        $this->service = new RecruitmentAnnouncementService();
        $this->jobPostService = new JobPostService();
    }

    public function index()
    {
        $pageTitle = 'Recruitment Announcements';
        $jobs = Job::orderBy('title', 'ASC')->get();
        return view('secure.recruitment_announcements.index', compact('pageTitle', 'jobs'));
    }

    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $query = RecruitmentAnnouncement::where('type', 'notification')
                ->orWhere('type', null)
                ->with('jobPost.job');

            if ($request->has('status') && !empty($request->status)) {
                if ($request->status == 'published') {
                    $query->where('is_published', 1);
                } elseif ($request->status == 'pending') {
                    $query->where('is_approved', 0);
                }
            }

            if ($request->has('job_id') && !empty($request->job_id)) {
                $query->whereHas('jobPost', function ($q) use ($request) {
                    $q->where('job_id', $request->job_id);
                });
            }

            if ($request->has('type') && !empty($request->type)) {
                $query->where('type', $request->type);
            }

            $data = $query->orderBy('id', 'DESC')->get();

            return DataTables::of($data)
                ->addColumn('job_title', function ($row) {
                    return $row->jobPost->job->title ?? 'N/A';
                })
                ->addColumn('post_title', function ($row) {
                    return $row->jobPost->title ?? 'N/A';
                })
                ->addColumn('file', function ($row) {
                    if ($row->file_name) {
                        return '<a href="' . generate_file_view_path_for_backend($row->file_url) . '" target="_blank">View File</a>';
                    }
                    return '-';
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (auth()->user()->can('view recruitment announcement')) {
                        $btn .= '<a href="' . route('recruitment-announcements.show', $row->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }
                    if (auth()->user()->can('edit recruitment announcement')) {
                        $btn .= '<a href="' . route('recruitment-announcements.edit', $row->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }



                    if (auth()->user()->can('delete recruitment announcement')) {
                        $btn .= '<button class="btn btn-sm btn-danger delete-recruitment-announcement" data-id="' . $row->id . '" title="Delete"><i class="fa fa-trash"></i></button>';
                    }
                    return $btn;
                })
                ->rawColumns(['action', 'file'])
                ->make(true);
        }
    }

    public function create()
    {
        $pageTitle = 'Add Recruitment Announcement';
        $jobs = Job::orderBy('title', 'ASC')->get();
        return view('secure.recruitment_announcements.create', compact('pageTitle', 'jobs'));
    }

    public function store(StoreRecruitmentAnnouncementRequest $request)
    {
        try {
            $dto = new RecruitmentAnnouncementDto(
                $request->job_post_id,
                $request->type,
                strip_tags($request->input('title')),
                strip_tags($request->input('title_hi')),
                $request->start_date,
                $request->end_date,
                $request->file('file_name'),
                $request->file('file_name_hi'),
                Purifier::clean($request->input('remarks')),
                0,
                0,
                auth()->id(),
                Carbon::now(),
                auth()->id(),
                Carbon::now()
            );

            $result = $this->service->create($dto);

            if (!$result) {
                return response()->json(['success' => false, 'message' => 'Error while saving announcement.'], 500);
            }

            return response()->json(['success' => true, 'message' => 'Announcement created successfully!'], 201);
        } catch (\Exception $e) {
            Log::error('Recruitment Announcement addition failed: ' . $e->getMessage());
            $msg = $e->getMessage();
            if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                return response()->json([
                    'success' => false,
                    'message' => $msg
                ]);
            }
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Recruitment Announcement';
        $announcement = $this->service->findById($id);
        $jobs = Job::orderBy('title', 'ASC')->get();
        $jobPosts = $this->jobPostService->findByJobId($announcement->jobPost->job_id);

        return view('secure.recruitment_announcements.edit', compact('pageTitle', 'announcement', 'jobs', 'jobPosts'));
    }

    public function update(UpdateRecruitmentAnnouncementRequest $request, $id)
    {
        try {
            $announcement = $this->service->findById($id);
            $dto = new RecruitmentAnnouncementDto(
                $request->job_post_id,
                $request->type,
                strip_tags($request->input('title')),
                strip_tags($request->input('title_hi')),
                $request->start_date,
                $request->end_date,
                $request->file('file_name'),
                $request->file('file_name_hi'),
                $request->remarks,
                $announcement->is_approved,
                $announcement->is_published,
                $announcement->created_by,
                $announcement->created_at,
                auth()->id(),
                Carbon::now()
            );

            $result = $this->service->update($dto, $id);

            if (!$result) {
                return response()->json(['success' => false, 'message' => 'Error while updating announcement.'], 500);
            }

            return response()->json(['success' => true, 'message' => 'Announcement updated successfully!'], 200);
        } catch (\Exception $e) {
            Log::error('Recruitment Announcement update failed: ' . $e->getMessage());
            $msg = $e->getMessage();
            if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                return response()->json([
                    'success' => false,
                    'message' => $msg
                ]);
            }
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $pageTitle = 'View Recruitment Announcement';
        $announcement = RecruitmentAnnouncement::with('jobPost.job')->find($id);
        return view('secure.recruitment_announcements.show', compact('announcement', 'pageTitle'));
    }

    public function destroy($id)
    {
        try {
            $this->service->delete($id);
            return response()->json(['success' => true, 'message' => 'Announcement deleted successfully!']);
        } catch (\Exception $e) {
            Log::error('Recruitment Announcement deletion failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, $id)
    {
        try {
            $announcement = RecruitmentAnnouncement::findOrFail($id);
            $updated = $this->service->approve(
                $announcement->id,
                $request->is_approved,
                strip_tags($request->remarks) ?? null,
                $announcement->publish_remark
            );

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while approving announcement.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Approval decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Recruitment Announcement approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    public function publish(PublishRequest $request, $id)
    {
        try {
            $announcement = RecruitmentAnnouncement::findOrFail($id);
            $isPublished = (int) $request->input('is_published');
            $isApproved = $announcement->is_approved == 1 || $isPublished == 1 ? 1 : $announcement->is_approved;
            $remarks = $announcement->is_approved == 1
                ? $announcement->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $announcement->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->service->publish($announcement->id, $isApproved, $remarks, $isPublished, $publishRemark);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while publishing announcement.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Publish decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Recruitment Announcement publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    public function getJobPostsByJob(Request $request)
    {
        $jobId = $request->job_id;
        $posts = $this->jobPostService->findByJobId($jobId);
        return response()->json(['success' => true, 'data' => $posts]);
    }

    /**
     * Export recruitment announcements to Excel.
     */
    public function export()
    {
        try {
            $fileName = 'recruitment_announcements_' . date('Y-m-d_His') . '.xlsx';
            return Excel::download(new RecruitmentAnnouncementsExport(), $fileName);
        } catch (\Exception $e) {
            Log::error('Recruitment Announcements export failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to export records.');
        }
    }

    /**
     * Import recruitment announcements metadata from Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            // Truncate the table before import
            RecruitmentAnnouncement::truncate();

            $import = new RecruitmentAnnouncementsImport();
            Excel::import($import, $request->file('import_file'));

            $updatedCount = $import->getUpdatedCount();
            $insertedCount = $import->getInsertedCount();
            $skippedCount = $import->getSkippedCount();

            return response()->json([
                'success' => true,
                'message' => "Import completed! {$updatedCount} updated, {$insertedCount} inserted, {$skippedCount} skipped.",
            ]);
        } catch (\Exception $e) {
            Log::error('Recruitment Announcements import failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error during import: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download an empty format for Excel Import.
     */
    public function downloadFormat()
    {
        try {
            $fileName = 'recruitment_announcements_format.xlsx';
            return Excel::download(new RecruitmentAnnouncementsFormatExport(), $fileName);
        } catch (\Exception $e) {
            Log::error('Recruitment Announcements format download failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to download format.');
        }
    }
}
