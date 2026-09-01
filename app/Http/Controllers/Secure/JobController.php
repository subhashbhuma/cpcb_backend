<?php

namespace App\Http\Controllers\Secure;

use App\DTO\JobDto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobsRequest;
use App\Http\Requests\UpdateJobsRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Http\Resources\PublicJobResource;

use App\Models\Job;
use App\Services\JobService;
use Carbon\Carbon;

use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class JobController extends Controller
{

    protected $jobService;
    public $carbon;

    public function __construct()
    {
        $this->jobService = new JobService();
        $this->carbon = new Carbon();
    }

    public function index(Request $request)
    {
        $pageTitle = 'Jobs';
        return view('secure.jobs.index', compact('pageTitle'));
    }

    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $query = Job::query();

            if ($request->has('status')) {
                if ($request->status == 'published') {
                    $query->where('is_published', 1);
                } elseif ($request->status == 'pending') {
                    $query->where('is_approved', 0);
                }
            }

            $jobs = $query->orderBy('id', 'DESC')->get();
            return DataTables::of($jobs)
                ->addColumn('vacancy_details', function ($row) {
                    return $row->title;
                })
                ->addColumn('timeline', function ($row) {
                    $start = $row->start_date ? date('d-m-Y', strtotime($row->start_date)) : 'N/A';
                    $end = $row->end_date ? date('d-m-Y', strtotime($row->end_date)) : 'N/A';
                    return '<div class=""><b>From:</b> ' . $start . '</div><div class=""><b>To:</b> ' . $end . '</div>';
                })
                ->addColumn('advt', function ($row) {
                    $links = [];
                    if ($row->advertisement_file_name) {
                        $links[] = '<a href="' . generate_file_view_path_for_backend($row->advertisement_file_url) . '" target="_blank" class="text-primary">View File</a>';
                    }
                    return count($links) > 0 ? implode(' ', $links) : '<span class="text-muted ">N/A</span>';
                })
                ->addColumn('direct_form', function ($row) {
                    $links = [];
                    if ($row->direct_application_form_name) {
                        $links[] = '<a href="' . generate_file_view_path_for_backend($row->direct_application_form_url) . '" target="_blank" class="badge bg-light-primary text-primary border border-primary">EN</a>';
                    }
                    if ($row->direct_application_form_hi_name) {
                        $links[] = '<a href="' . generate_file_view_path_for_backend($row->direct_application_form_url_hi) . '" target="_blank" class="badge bg-light-danger text-danger border border-danger">HI</a>';
                    }
                    return count($links) > 0 ? implode(' ', $links) : '<span class="text-muted ">N/A</span>';
                })
                ->addColumn('deputation_form', function ($row) {
                    $links = [];
                    if ($row->deputation_application_form_name) {
                        $links[] = '<a href="' . generate_file_view_path_for_backend($row->deputation_application_form_url) . '" target="_blank" class="badge bg-light-primary text-primary border border-primary">EN</a>';
                    }
                    if ($row->deputation_application_form_hi_name) {
                        $links[] = '<a href="' . generate_file_view_path_for_backend($row->deputation_application_form_url_hi) . '" target="_blank" class="badge bg-light-danger text-danger border border-danger">HI</a>';
                    }
                    return count($links) > 0 ? implode(' ', $links) : '<span class="text-muted ">N/A</span>';
                })
                ->addColumn('status', function ($row) {

                    $publication = $row->is_published == 1
                        ? '<span class="badge bg-success ms-1">Live</span>'
                        : '<span class="badge bg-warning ms-1">Draft</span>';

                    return $publication;
                })
                ->addColumn('action', function ($jobs) {
                    $button = '';
                    if (auth()->user()->can('view job')) {
                        $button .= '<a href="' . route('jobs.show', $jobs->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit job')) {
                        $button .= '<a href="' . route('jobs.edit', $jobs->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete job')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-jobs" data-id="' . $jobs->id . '" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>';
                    }
                    return $button;
                })
                ->rawColumns(['vacancy_details', 'timeline', 'advt', 'direct_form', 'deputation_form', 'status', 'action'])
                ->make(true);
        }
    }

    public function create()
    {
        $pageTitle = 'Add Jobs';
        return view('secure.jobs.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJobsRequest $request)
    {
        try {
            $createdAt = Carbon::now();
            $tenderDto = new JobDto(
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('start_date') ? Carbon::parse($request->input('start_date'))->format('Y-m-d') : null,
                $request->input('end_date') ? Carbon::parse($request->input('end_date'))->format('Y-m-d') : null,
                $request->file('advertisement_file_name'),
                $request->file('advertisement_file_hi_name'),
                $request->file('direct_application_form_name'),
                $request->file('direct_application_form_hi_name'),
                $request->file('deputation_application_form_name'),
                $request->file('deputation_application_form_hi_name'),
                $request->input('job_type'),
                $request->input('direct_application'),
                $request->input('deputation_application'),
                $request->input('direct_application_url'),
                $request->input('deputation_application_url'),
                $request->input('online_form_url'),
                $request->input('walk_in_interview_date') ? Carbon::parse($request->input('walk_in_interview_date'))->format('Y-m-d') : null,
                $request->input('posts', []),
                0,
                0,
                auth()->user()->id,
                $createdAt,
                auth()->user()->id,
                $createdAt
            );

            $jobs = $this->jobService->create($tenderDto);

            if (!$jobs) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving jobs.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Jobs created successfully!'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Jobs addition failed: ' . $e->getMessage());
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
        $pageTitle = 'View Job Details';
        $job = $this->jobService->findById($id);
        return view('secure.jobs.show', compact('job', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Job';
        $jobs = Job::with('jobPosts')->find($id);
        return view('secure.jobs.edit', compact('jobs', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJobsRequest $request, Job $job)
    {
        try {
            $jobDto = new JobDto(

                $request->input('title'),
                $request->input('title_hi'),
                $request->input('start_date') ? Carbon::parse($request->input('start_date'))->format('Y-m-d') : null,
                $request->input('end_date') ? Carbon::parse($request->input('end_date'))->format('Y-m-d') : null,
                $request->hasFile('advertisement_file_name') ? $request->file('advertisement_file_name') : null,
                $request->hasFile('advertisement_file_hi_name') ? $request->file('advertisement_file_hi_name') : null,
                $request->hasFile('direct_application_form_name') ? $request->file('direct_application_form_name') : null,
                $request->hasFile('direct_application_form_hi_name') ? $request->file('direct_application_form_hi_name') : null,
                $request->hasFile('deputation_application_form_name') ? $request->file('deputation_application_form_name') : null,
                $request->hasFile('deputation_application_form_hi_name') ? $request->file('deputation_application_form_hi_name') : null,
                $request->input('job_type'),
                $request->input('direct_application'),
                $request->input('deputation_application'),
                $request->input('direct_application_url'),
                $request->input('deputation_application_url'),
                $request->input('online_form_url'),
                $request->input('walk_in_interview_date') ? Carbon::parse($request->input('walk_in_interview_date'))->format('Y-m-d') : null,
                $request->input('posts', []),
                $job->is_approved,
                $job->is_published,
                $job->created_by,
                $job->created_at,
                auth()->user()->id,
                $this->carbon->now()
            );

            $updated = $this->jobService->update($jobDto, $job->id);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating jobs.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Jobs updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Job updation failed: ' . $e->getMessage());
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
            $jobs = $this->jobService->delete($id);
            if (!$jobs) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting jobs.',
                ], 500);
            }

            return response()->json(['message' => 'Jobs moved to trash successfully!']);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Jobs deletion failed: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, Job $job)
    {
        try {
            $updated = $this->jobService->approve(
                $job->id,
                $request->is_approved,
                strip_tags($request->remarks) ?? null,
                $job->publish_remark
            );

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while approving jobs.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Approval decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Job approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }


    public function publish(PublishRequest $request, Job $job)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $job->is_approved == 1 || $isPublished == 1 ? 1 : $job->is_approved;
            $remarks = $job->is_approved == 1
                ? $job->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $job->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->jobService->publish($job->id, $isApproved, $remarks, $isPublished, $publishRemark);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while publishing jobs.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Publish decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Job publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }



    public function fetchAllForPublic()
    {
        $jobs = $this->jobService->findForPublic();
        return response()->json([
            'success' => true,
            'data' => $jobs
        ]);
    }



    // public function fetchAllForPublicDataTable(Request $request, $type = 'latest')
    // {
    //     try {
    //         $baseQuery = Job::where('is_published', 1);

    //         $threeMonthsAgo = Carbon::now()->subDays(365)->startOfDay();
    //         $query = clone $baseQuery;

    //         if ($type === 'latest') {
    //             $query->where('end_date', '>=', $threeMonthsAgo);
    //         } elseif ($type === 'archive') {
    //             $query->where('end_date', '<=', $threeMonthsAgo);
    //         }

    //         /* SEARCH */
    //         if ($request->input('search')) {
    //             $search = $request->input('search');
    //             $search = str_replace(['%', '_'], ['\\%', '\\_'], $search);
    //             $query->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"])
    //                 ->orWhereRaw('title_hi LIKE ?', ["%{$search}%"]);
    //         }

    //         /* FILTER BY YEAR */
    //         if ($request->filled('year')) {
    //             $query->whereYear('start_date', $request->year);
    //         }

    //         /* JOB TYPE FILTER */
    //         if ($request->filled('job_type')) {
    //             $query->where('job_type', $request->job_type);
    //         }

    //         /* SORTING */
    //         $allowedSorts = ['start_date', 'end_date', 'title', 'created_at'];

    //         $sortField = in_array($request->input('sort'), $allowedSorts)
    //             ? $request->input('sort')
    //             : 'end_date';

    //         $sortOrder = $request->input('order') === 'asc' ? 'asc' : 'desc';

    //         $query->reorder()
    //             ->orderByRaw("$sortField IS NULL")
    //             ->orderBy($sortField, $sortOrder);

    //         /* PAGINATION */
    //         $perPage = (int) $request->input('per_page', 10);
    //         $results = $query->with([
    //             'jobPosts' => function ($q) {
    //                 $q->where('is_published', 1)->with([
    //                     'recruitmentAnnouncements' => function ($sq) {
    //                         $sq->where('is_published', 1);
    //                     }
    //                 ]);
    //             }
    //         ])->paginate($perPage);

    //         $totalCount = $results->total();

    //         return response()->json([
    //             'status' => true,
    //             'meta' => [
    //                 'total_records' => $totalCount,
    //                 'filtered_count' => $totalCount,
    //                 'current_page' => $results->currentPage(),
    //                 'per_page' => $results->perPage(),
    //                 'last_page' => $results->lastPage(),
    //                 'lastUpdatedOn' => Job::getLastUpdatedOrCreatedAt(),
    //             ],
    //             'data' => PublicJobResource::collection($results),
    //         ], 200);

    //     } catch (\Exception $e) {
    //         Log::error("DataTable Fetch Error: " . $e->getMessage());
    //         return response()->json(['status' => false, 'message' => 'Internal Server Error'], 500);
    //     }
    // }

    public function fetchAllForPublicDataTable(Request $request, $type = 'latest')
    {
        try {
            $threeMonthsAgo = \Carbon\Carbon::now()->subDays(365)->startOfDay();

            $query = Job::query()
                ->where('is_published', 1)->orderBy('start_date', 'desc');

            /* TYPE FILTER (LATEST / ARCHIVE) */
            if ($type === 'latest') {
                $query->whereDate('end_date', '>=', $threeMonthsAgo);
            } elseif ($type === 'archive') {
                $query->whereDate('end_date', '<', $threeMonthsAgo);
            }

            /* SEARCH (FIXED - GROUPED) */
            if ($request->filled('search')) {
                $search = strtolower(trim($request->input('search')));
                $search = str_replace(['%', '_'], ['\\%', '\\_'], $search);

                $query->where(function ($q) use ($search) {
                    $q->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"])
                        ->orWhereRaw('LOWER(title_hi) LIKE ?', ["%{$search}%"]);
                });
            }

            /* FILTER BY YEAR */
            if ($request->filled('year')) {
                $query->whereYear('start_date', $request->year);
            }

            /* JOB TYPE FILTER */
            if ($request->filled('job_type')) {
                $query->where('job_type', $request->job_type);
            }

            /* SORTING (SAFE) */
            $allowedSorts = ['start_date', 'end_date', 'title', 'created_at'];

            $sortField = in_array($request->input('sort'), $allowedSorts)
                ? $request->input('sort')
                : 'end_date';

            $sortOrder = $request->input('order') === 'asc' ? 'asc' : 'desc';

            $query->orderByRaw("$sortField IS NULL") // push NULL last
                ->orderBy($sortField, $sortOrder);

            /* PAGINATION */
            $perPage = (int) $request->input('per_page', 10);

            $results = $query
                ->with([
                    'jobPosts' => function ($q) {
                        $q->where('is_published', 1)
                            ->with([
                                'recruitmentAnnouncements' => function ($sq) {
                                    $sq->where('is_published', 1);
                                }
                            ]);
                    }
                ])
                ->paginate($perPage);

            return response()->json([
                'status' => true,
                'meta' => [
                    'total_records' => $results->total(),
                    'filtered_count' => $results->total(),
                    'current_page' => $results->currentPage(),
                    'per_page' => $results->perPage(),
                    'last_page' => $results->lastPage(),
                    'lastUpdatedOn' => Job::getLastUpdatedOrCreatedAt(),
                ],
                'data' => PublicJobResource::collection($results),
            ], 200);

        } catch (\Exception $e) {
            \Log::error("DataTable Fetch Error: " . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }
}
