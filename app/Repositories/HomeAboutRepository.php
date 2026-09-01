<?php

namespace App\Repositories;

use App\Models\HomeAbout;

class HomeAboutRepository
{
    public function findFirst()
    {
        return HomeAbout::orderBy('id', 'DESC')->first();
    }

    public function findForPublic()
    {
        return HomeAbout::where('is_published', 1)->get();
    }

    public function findAll()
    {
        return HomeAbout::get();
    }

    public function findById($id)
    {
        return HomeAbout::find($id);
    }

    public function create($data)
    {
        return HomeAbout::create($data);
    }

    public function update($data, $id)
    {
        $result = HomeAbout::find($id);
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
        $result = HomeAbout::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
