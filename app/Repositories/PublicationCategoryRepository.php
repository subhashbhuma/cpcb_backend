<?php

namespace App\Repositories;

use App\Models\PublicationCategory;

class PublicationCategoryRepository
{
    public function findForPublic($limit = 10)
    {
        return PublicationCategory::where('is_published', 1 )->limit($limit)->orderBy('created_at', 'desc')->get();
    }
    public function findAll(){
        return PublicationCategory::orderBy('id', 'DESC')->get();
    }
    public function findById($id){
        return PublicationCategory::find($id);
    }
    public function fetchByIdForPublic($id){
        return PublicationCategory::find($id);
    }

    public function create($data){
        return PublicationCategory::create($data);
    }

    public function update($data, $id){
        $result = PublicationCategory::find($id);
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
        $result = PublicationCategory::find($id);
        if ($result){
            return $result->delete();
        }
        return false;
    }
}