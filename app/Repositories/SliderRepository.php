<?php

namespace App\Repositories;

use App\Models\Slider;
use Illuminate\Support\Facades\Cache;

class SliderRepository
{
    const CACHE_KEY = 'public_sliders';

    public function findForPublic()
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return Slider::where('is_published', 1)->latest('created_at')->get();
        });
    }

    public function findAll()
    {
        return Slider::latest('created_at')->get();
    }

    public function findById($id)
    {
        return Slider::find($id);
    }

    public function create($data)
    {
        return Slider::create($data);
    }

    public function update($data, $id)
    {
        $result = Slider::find($id);
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
        $result = Slider::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
