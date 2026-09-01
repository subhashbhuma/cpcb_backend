<?php

namespace App\Repositories;

use App\Models\SmsLog;

class SmsLogRepository
{
    public function create(array $data)
    {
        return SmsLog::create($data);
    }

    public function findById($id)
    {
        return SmsLog::findOrFail($id);
    }
}
