<?php

namespace App\Repositories;

use App\Models\QueryFormSubject;

class QueryFormSubjectRepository
{
    public function findForPublic($limit = 10)
    {
        return QueryFormSubject::with('division')->where('is_published', 1)->limit($limit)->orderBy('title', 'asc')->get();
    }

    public function findAll()
    {
        return QueryFormSubject::with('division')->orderBy('id', 'DESC')->get();
    }

    public function findById($id)
    {
        return QueryFormSubject::with('division')->find($id);
    }

    public function fetchByIdForPublic($id)
    {
        return QueryFormSubject::with('division')->find($id);
    }

    public function create($data)
    {
        return QueryFormSubject::create($data);
    }

    public function update($data, $id)
    {
        $result = QueryFormSubject::find($id);
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
        $result = QueryFormSubject::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
