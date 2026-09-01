<?php

namespace App\Repositories;

use App\Models\ZonalOffice;

class ZonalOfficeRepository
{
    public function findForPublic($limit = 10)
    {
        return ZonalOffice::where('is_published', 1 )->limit($limit)->orderBy('created_at', 'desc')->get();
    }
    public function findAll(){
        return ZonalOffice::orderBy('id', 'DESC')->get();
    }
    public function findById($id){
        return ZonalOffice::find($id);
    }
    public function fetchByIdForPublic($id){
        return ZonalOffice::find($id);
    }

    public function create($data){
        return ZonalOffice::create($data);
    }

    public function update($data, $id){
        $result = ZonalOffice::find($id);
        if ($result){
            $result = $result->update($data);
            if(!$result){
                return false;
            }
            return $result;
        }
        return false;
    }
    public function delete($id){
        $result = ZonalOffice::find($id);
        if ($result){
            return $result->delete();
        }
        return false;
    }
}
