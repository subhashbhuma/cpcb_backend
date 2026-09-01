<?php

namespace App\Repositories;

use App\Models\EmailLog;

class EmailLogRepository
{
    public function create(array $data)
    {
        return EmailLog::create($data);
    }

    public function findById($id)
    {
        return EmailLog::findOrFail($id);
    }
}
