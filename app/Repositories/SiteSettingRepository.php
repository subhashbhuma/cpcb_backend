<?php

namespace App\Repositories;

use App\Models\SiteSetting;

class SiteSettingRepository
{
    public function findFirst()
    {
        return SiteSetting::first();
    }

    public function findById($id)
    {
        return SiteSetting::find($id);
    }

    public function update($data, $id)
    {
        $result = SiteSetting::find($id);
        if ($result) {
            $result = $result->update($data);
            if (!$result) {
                return false;
            }
            return $result;
        }
        return false;
    }

}
