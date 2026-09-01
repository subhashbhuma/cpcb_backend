<?php

namespace App\Repositories;

use App\Models\Event;

class EventRepository
{
    public function findForPublic()
    {
        return Event::where('is_published', 1)->get();
    }

    public function findAll()
    {
        return Event::orderBy('id', 'DESC')->get();
    }

    public function findById($id)
    {
        return Event::find($id);
    }

    public function create($data)
    {
        return Event::create($data);
    }

    public function update($data, $id)
    {
        $result = Event::find($id);
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
        $result = Event::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
