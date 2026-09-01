<?php

namespace App\Services;

use App\Repositories\AuditLogRepository;

class AuditLogService
{
    private $auditLogRepository;

    public function __construct()
    {
        $this->auditLogRepository = new AuditLogRepository();
    }

    public function findAll()
    {
        return $this->auditLogRepository->findAll();
    }

    public function findById($id)
    {
        return $this->auditLogRepository->findById($id);
    }
}
