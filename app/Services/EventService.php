<?php

namespace App\Services;

use App\DTO\EventDto;
use App\Repositories\EventRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class EventService
{
    use FileUploadTrait;
    private $eventRepository;

    public function __construct()
    {
        $this->eventRepository = new EventRepository();
    }

    public function findForPublic()
    {
        return $this->eventRepository->findForPublic();
    }

    public function findAll()
    {
        return $this->eventRepository->findAll();
    }

    public function findById($id)
    {
        return $this->eventRepository->findById($id);
    }

    public function create(EventDto $eventDto)
    {
        return $this->eventRepository->create([
            'title' => $eventDto->title,
            'title_hi' => $eventDto->title_hi,
            'brief_summary' => $eventDto->brief_summary,
            'brief_summary_hi' => $eventDto->brief_summary_hi,
            'description' => $eventDto->description,
            'description_hi' => $eventDto->description_hi,
            'venue' => $eventDto->venue,
            'venue_hi' => $eventDto->venue_hi,
            'date' => $eventDto->date,
            'time' => $eventDto->time,
            'is_approved' => $eventDto->is_approved,
            'is_published' => $eventDto->is_published,
            'remarks' => $eventDto->remarks,
            'created_by' => $eventDto->created_by,
            'updated_by' => $eventDto->updated_by,
        ]);
    }

    public function update(EventDto $eventDto, $id)
    {

        $data = [
            'title' => $eventDto->title,
            'title_hi' => $eventDto->title_hi,
            'brief_summary' => $eventDto->brief_summary,
            'brief_summary_hi' => $eventDto->brief_summary_hi,
            'description' => $eventDto->description,
            'description_hi' => $eventDto->description_hi,
            'venue' => $eventDto->venue,
            'venue_hi' => $eventDto->venue_hi,
            'date' => $eventDto->date,
            'time' => $eventDto->time,
            'is_approved' => $eventDto->is_approved,
            'is_published' => $eventDto->is_published,
            'remarks' => $eventDto->remarks,
            'created_by' => $eventDto->created_by,
            'updated_by' => $eventDto->updated_by,
        ];

        return $this->eventRepository->update($data, $id);
    }


    public function delete($id)
    {
        return $this->eventRepository->delete($id);
    }

    public function approve(EventDto $eventDto, $id)
    {
        $updateData = [
            'is_approved' => $eventDto->is_approved,
            'remarks' => $eventDto->remarks,
            'updated_by' => $eventDto->updated_by,
        ];

        $result = $this->eventRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }

    public function publish(EventDto $eventDto, $id)
    {
        $updateData = [
            'is_approved' => $eventDto->is_approved,
            'is_published' => $eventDto->is_published,
            'remarks' => $eventDto->remarks,
            'updated_by' => $eventDto->updated_by,
        ];

        $result = $this->eventRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }
}
