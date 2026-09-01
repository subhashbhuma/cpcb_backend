<?php

namespace App\Repositories;

use App\Models\Faq;

class FaqRepository
{
    public function findForPublic($limit = 7)
    {
        return Faq::where('is_published', 1)->limit($limit)->orderBy('id', 'desc')->get();
    }

    public function findAllForPublic($search)
    {
        $query=Faq::where('is_published', 1);
        if($search){
            $query->where('question', 'like', '%' . $search . '%');
        }
        return $query->orderBy('id', 'asc')->get();
    }

    public function findAll()
    {
        return Faq::get();
    }

    public function findById($id)
    {
        return Faq::find($id);
    }

    public function create($data)
    {
        return Faq::create($data);
    }

    public function update($data, $id)
    {
        $result = Faq::find($id);
        if ($result) {
            $result = $result->update($data);
            if (!$result) {
                return false;
            }
            return $result;
        }
        return false;
    }

    public function delete($id)
    {
        $result = Faq::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
