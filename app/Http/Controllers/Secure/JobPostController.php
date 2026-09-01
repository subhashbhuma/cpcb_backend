<?php

namespace App\Http\Controllers\Secure;
use Mews\Purifier\Facades\Purifier;
use App\DTO\JobPostDto;
use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobPost;
use App\Services\JobPostService;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class JobPostController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = new JobPostService();
    }

    public function index()
    {
        $pageTitle = 'Job Posts';
        return view('secure.job_posts.index', compact('pageTitle'));
    }

    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $query = JobPost::with('job');

            if ($request->has('status')) {
                if ($request->status == 'published') {
                    $query->where('is_published', 1);
                } elseif ($request->status == 'pending') {
                    $query->where('is_approved', 0);
                }
            }

            $data = $query->orderBy('id', 'DESC')->get();

            return DataTables::of($data)
                ->addColumn('job_title', function ($row) {
                    return $row->job->title ?? 'N/A';
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (auth()->user()->can('view job post')) {
                        $btn .= '<a href="' . route('job-posts.show', $row->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }
                    if (auth()->user()->can('edit job post')) {
                        $btn .= '<a href="' . route('job-posts.edit', $row->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete job post')) {
                        $btn .= '<button class="btn btn-sm btn-danger delete-job-post" data-id="' . $row->id . '" title="Delete"><i class="fa fa-trash"></i></button>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function create()
    {
        $pageTitle = 'Add Job Post';
        $jobs = Job::orderBy('title', 'ASC')->get();
        return view('secure.job_posts.create', compact('pageTitle', 'jobs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'title' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'title_hi' => 'nullable|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
        ]);

        try {
            $dto = new JobPostDto(
                $request->job_id,
                $request->title,
                $request->title_hi,
                Purifier::clean($request->input('remarks')) ?? null,
                0,
                0,
                auth()->id(),
                Carbon::now(),
                auth()->id(),
                Carbon::now()
            );

            $result = $this->service->create($dto);

            if (!$result) {
                return response()->json(['success' => false, 'message' => 'Error while saving job post.'], 500);
            }

            return response()->json(['success' => true, 'message' => 'Job Post created successfully!'], 201);
        } catch (\Exception $e) {
            Log::error('Job Post addition failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Job Post';
        $jobPost = $this->service->findById($id);
        $jobs = Job::orderBy('title', 'ASC')->get();
        return view('secure.job_posts.edit', compact('pageTitle', 'jobPost', 'jobs'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'title' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'title_hi' => 'nullable|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
        ]);

        try {
            $jobPost = $this->service->findById($id);
            $dto = new JobPostDto(
                $request->job_id,
                $request->title,
                $request->title_hi,
                $jobPost->remarks,
                $jobPost->is_approved,
                $jobPost->is_published,
                $jobPost->created_by,
                $jobPost->created_at,
                auth()->id(),
                Carbon::now()
            );

            $result = $this->service->update($dto, $id);

            if (!$result) {
                return response()->json(['success' => false, 'message' => 'Error while updating job post.'], 500);
            }

            return response()->json(['success' => true, 'message' => 'Job Post updated successfully!'], 200);
        } catch (\Exception $e) {
            Log::error('Job Post update failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $pageTitle = 'View Job Post';
        $jobPost = JobPost::with('job')->find($id);
        return view('secure.job_posts.show', compact('jobPost', 'pageTitle'));
    }

    public function destroy($id)
    {
        try {
            $this->service->delete($id);
            return response()->json(['success' => true, 'message' => 'Job Post deleted successfully!']);
        } catch (\Exception $e) {
            Log::error('Job Post deletion failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, JobPost $jobPost)
    {
        try {
            $updated = $this->service->approve(
                $jobPost->id,
                $request->is_approved,
                strip_tags($request->remarks) ?? null,
                $jobPost->publish_remark
            );

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while approving job post.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Approval decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Job Post approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    public function publish(PublishRequest $request, JobPost $jobPost)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $jobPost->is_approved == 1 || $isPublished == 1 ? 1 : $jobPost->is_approved;
            $remarks = $jobPost->is_approved == 1
                ? $jobPost->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $jobPost->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->service->publish($jobPost->id, $isApproved, $remarks, $isPublished, $publishRemark);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while publishing job post.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Publish decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Job Post publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }
}
