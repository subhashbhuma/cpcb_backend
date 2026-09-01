<?php

namespace App\Http\Controllers\Secure;

use App\DTO\EventDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Services\EventService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class EventController extends Controller
{
    protected $eventService;

    public function __construct()
    {
        $this->eventService = new EventService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageTitle = 'Events';
        return view('secure.events.index', compact('pageTitle'));
    }

    /**
     * Fetch a listing of the resource.
     */
    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $events = $this->eventService->findAll();

            return DataTables::of($events)
                ->addColumn('type', function ($event) {
                    return ucfirst($event->file_or_link ?? 'N/A');
                })
                ->addColumn('preview', function ($event) {
                    if ($event->file_or_link === 'file') {
                        $files = '';
                        if ($event->file_name) {
                            $files .= "<a href='" . asset('storage/' . Config::get('file_paths')['ANNOUNCEMENT_FILE_EN_PATH'] . '/' . $event->file_name) . "' target='_BLANK'>View</a>";
                        }

                        if ($event->file_name_hi) {
                            $files .= "<a href='" . asset('storage/' . Config::get('file_paths')['ANNOUNCEMENT_FILE_HI_PATH'] . '/' . $event->file_name_hi) . "' target='_BLANK'>View</a>";
                        }

                        return $files;
                    }

                    if ($event->file_or_link === 'link' && $event->page_link) {
                        return "<a href='" . e($event->page_link) . "' target='_blank'>View Link</a>";
                    }

                    return '—';
                })
                ->addColumn('action', function ($event) {
                    $button = '';
                    if (auth()->user()->can('view event')) {
                        $button .= '<a href="' . route('events.show', $event->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit event')) {
                        $button .= '<a href="' . route('events.edit', $event->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete event')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-event" data-id="' . $event->id . '" title="Delete">
                        <i class="fa fa-trash"></i>
                    </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'preview'])
                ->make(true);
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Add Events';
        return view('secure.events.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request)
    {
        try {

            $eventDto = new EventDto(
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('brief_summary'),
                $request->input('brief_summary_hi'),
                $request->input('description'),
                $request->input('description_hi'),
                $request->input('venue'),
                $request->input('venue_hi'),
                $request->input('date'),
                $request->input('time'),
                $request->input('is_approved', 0),
                $request->input('is_published', 0),
                $request->input('remarks'),
                auth()->id(),
                auth()->id()
            );

            $event = $this->eventService->create($eventDto);

            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving event.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Event created successfully!'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Event addition failed: ' . $e->getMessage());
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
        $pageTitle = 'View Events';
        $event = $this->eventService->findById($id);
        return view('secure.events.show', compact('event', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Events';
        $event = $this->eventService->findById($id);
        return view('secure.events.edit', compact('event', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        try {
            $eventDto = new EventDto(
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('brief_summary'),
                $request->input('brief_summary_hi'),
                $request->input('description'),
                $request->input('description_hi'),
                $request->input('venue'),
                $request->input('venue_hi'),
                $request->input('date'),
                $request->input('time'),
                $event->is_approved,
                $event->is_published,
                $event->remarks,
                $event->created_by,
                auth()->user()->id
            );

            $event = $this->eventService->update($eventDto, $event->id);

            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating event.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Event updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Event updation failed: ' . $e->getMessage());
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
            $event = $this->eventService->delete($id);
            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting event.',
                ], 500);
            }

            return response()->json(['message' => 'Event moved to trash successfully!']);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Event deletion failed: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(Request $request, Event $event)
    {
        try {
            $eventDto = new EventDto(
                $event->title,
                $event->title_hi,
                $event->brief_summary,
                $event->brief_summary_hi,
                $event->description,
                $event->description_hi,
                $event->venue,
                $event->venue_hi,
                $event->date,
                $event->time,
                $request->input('is_approved'),
                0,
                $request->input('remarks'),
                $event->created_by,
                auth()->user()->id
            );

            $updated = $this->eventService->approve($eventDto, $event->id);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while approving event.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Event decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Event approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function publish(Request $request, Event $event)
    {
        try {
            $eventDto = new EventDto(
                $event->title,
                $event->title_hi,
                $event->brief_summary,
                $event->brief_summary_hi,
                $event->description,
                $event->description_hi,
                $event->venue,
                $event->venue_hi,
                $event->date,
                $event->time,
                $event->is_approved == 1 ? $event->is_approved : 1,
                $request->input('is_published'),
                $event->is_approved == 1 ? $event->remarks : 'Automatically approved while publishing the content',
                $event->created_by,
                auth()->user()->id
            );

            $updated = $this->eventService->publish($eventDto, $event->id);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while publishing event.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Event published successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Event publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function findAllforPublic()
    {
        $events = $this->eventService->findForPublic();
        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }
}
