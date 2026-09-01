<?php

namespace App\DTO;

class EventDto
{
    public $title;
    public $title_hi;
    public $brief_summary;
    public $brief_summary_hi;
    public $description;
    public $description_hi;
    public $venue;
    public $venue_hi;
    public $date;
    public $time;
    public $is_approved;
    public $is_published;
    public $remarks;
    public $created_by;
    public $updated_by;

    public function __construct(
        $title,
        $title_hi,
        $brief_summary = null,
        $brief_summary_hi = null,
        $description = null,
        $description_hi = null,
        $venue = null,
        $venue_hi = null,
        $date,
        $time,
        $is_approved = 0,
        $is_published = 0,
        $remarks = null,
        $created_by,
        $updated_by = null
    ) {
        $this->title = $title;
        $this->title_hi = $title_hi;
        $this->brief_summary = $brief_summary;
        $this->brief_summary_hi = $brief_summary_hi;
        $this->description = $description;
        $this->description_hi = $description_hi;
        $this->venue = $venue;
        $this->venue_hi = $venue_hi;
        $this->date = $date;
        $this->time = $time;
        $this->is_approved = $is_approved;
        $this->is_published = $is_published;
        $this->remarks = $remarks;
        $this->created_by = $created_by;
        $this->updated_by = $updated_by;
    }
}
