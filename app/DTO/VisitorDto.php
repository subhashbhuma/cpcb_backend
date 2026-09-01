<?php

namespace App\DTO;

class VisitorDto
{
    public $ip_address;
    public $user_agent;
    public $page_url;
    public $session_id;

    public function __construct(
        $ip_address,
        $user_agent = null,
        $page_url = null,
        $session_id = null
    ) {
        $this->ip_address = $ip_address;
        $this->user_agent = $user_agent;
        $this->page_url = $page_url;
        $this->session_id = $session_id;
    }
}
