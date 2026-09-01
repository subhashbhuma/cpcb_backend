<?php

namespace App\Repositories;

use App\Models\InformationCenter;

class InformationCenterRepository
{
    public function findAll($type)
    {
        if($type!=""){
          return InformationCenter::whereNotIn('title', ['Annual Reports', 'Court Directives'])
            ->get();
        }else{
          return InformationCenter::latest()->get();
        }
    }

    public function findById($id)
    {
        return InformationCenter::findOrFail($id);
    }

    public function findPublished()
    {
        return InformationCenter::where('is_published', 1)->latest()->get();
    }

    public function findForPublic()
    {
        return InformationCenter::where('is_published', 1)
            ->where('is_approved', 1)
            ->orderBy('created_at','ASC')
            ->get();
    }

    public function create(array $data): InformationCenter
    {
        return InformationCenter::create($data);
    }

    public function update($id, array $data): InformationCenter
    {
        $center = $this->findById($id);
        $center->update($data);
        return $center;
    }

    public function delete($id): bool
    {
        return InformationCenter::destroy($id) > 0;
    }

    public function paginate($limit = 15)
    {
        return InformationCenter::latest()->paginate($limit);
    }
}
