<?php

namespace App\DTO;

class RegionalDirectoriesDto {
    public $zone;
    public $state;
    public $address;
    public $phone_numbers;
    public $email_ids;
    public $jurdiction;
    public $location_link;
    public $is_approved;
    public $is_published;
    public $remarks;
    public $created_by;
    public $updated_by;

    public function __construct(
        $zone = null,
        $state = null,
        $address = null,
        $phone_numbers = null,
        $email_ids = null,
        $jurdiction = null,
        $location_link = null,
        $is_approved = 0,
        $is_published = 0,
        $remarks = null,
        $created_by = null,
        $updated_by = null
    ) {
        $this->zone = $zone;
        $this->state = $state;
        $this->address = $address;
        $this->phone_numbers = $phone_numbers;
        $this->email_ids = $email_ids;
        $this->jurdiction = $jurdiction;
        $this->location_link = $location_link;
        $this->is_approved = $is_approved;
        $this->is_published = $is_published;
        $this->remarks = $remarks;
        $this->created_by = $created_by;
        $this->updated_by = $updated_by;
    }
}