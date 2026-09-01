<?php

namespace App\Repositories;

use App\Models\TenderCategory;

class TenderCategoryRepository
{
    public function findForPublic($limit = 10)
    {
        return TenderCategory::where('is_published', 1 )->limit($limit)->orderBy('created_at', 'desc')->get();
    }
    public function findAll(){
        return TenderCategory::orderBy('id', 'DESC')->get();
    }
    public function findById($id){
        return TenderCategory::find($id);
    }
    public function fetchByIdForPublic($id){
        return TenderCategory::find($id);
    }

    public function create($data){
        return TenderCategory::create($data);
    }

    public function update($data, $id){
        $result = TenderCategory::find($id);
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
        $result = TenderCategory::find($id);
        if ($result){
            return $result->delete();
        }
        return false;
    }
}
