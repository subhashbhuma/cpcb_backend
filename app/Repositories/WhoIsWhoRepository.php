<?php

namespace App\Repositories;

use App\Models\WhoIsWho;

class WhoIsWhoRepository
{
    public function findAllForHomepage()
    {
        return WhoIsWho::where([
            'show_on_homepage' => 1,
            'is_approved' => 1,
            'is_published' => 1,
        ])->with('division')->orderBy('order', 'ASC')->get();
    }

    public function findByDesignation($designation)
    {
        return WhoIsWho::where([
            'designation' => $designation,
            'is_approved' => 1,
            'is_published' => 1,
        ])->with('division')->orderBy('order', 'ASC')->get();
    }


    public function whoIsWhoHomePage()
    {
        return WhoIsWho::where([
            'is_approved' => 1,
            'is_published' => 1,
            'show_on_homepage' => 1,
        ])
            ->whereIn('order', [1, 2, 3, 4, 5])
            // ->whereIn('designation', ['Chairman', 'Member Secretary','Hon’ble Minister of State of EF&CC','Hon’ble Minister of EF&CC','Hon’ble Minister of EF&CC','Hon’ble Prime Minister'])
            ->with('division')
            ->orderBy('order', 'ASC')->get();
    }

    public function findAllForWhoIsWho()
    {
        return WhoIsWho::where([
            'hide_on_who_is_who' => 0,
            'is_approved' => 1,
            'is_published' => 1,
        ])->with('division')->orderBy('order', 'ASC')->get();
    }

    public function findAll()
    {
        return WhoIsWho::with('division')->orderBy('order', 'ASC')->get();
    }

    public function findById($id)
    {
        return WhoIsWho::with('division')->find($id);
    }

    public function create($data)
    {
        return WhoIsWho::create($data);
    }

    public function update($data, $id)
    {
        $result = WhoIsWho::find($id);
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
        $result = WhoIsWho::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
