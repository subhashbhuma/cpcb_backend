<?php

namespace App\Repositories;

use App\Models\RecruitmentAnnouncement;

class RecruitmentAnnouncementRepository
{
    public function findAll()
    {
        return RecruitmentAnnouncement::orderBy('id', 'DESC')->get();
    }

    public function findById($id)
    {
        return RecruitmentAnnouncement::find($id);
    }

    public function create($data)
    {
        return RecruitmentAnnouncement::create($data);
    }

    public function update($data, $id)
    {
        $result = RecruitmentAnnouncement::find($id);
        if ($result) {
            return $result->update($data);
        }
        return false;
    }

    public function delete($id)
    {
        $result = RecruitmentAnnouncement::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }

    public function findForPublic($limit = 10)
    {
        return RecruitmentAnnouncement::where('is_published', 1)
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->get();
    }
}
