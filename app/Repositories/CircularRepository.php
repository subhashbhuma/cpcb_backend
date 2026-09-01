<?php

namespace App\Repositories;

use App\Models\Circular;
use Carbon\Carbon;
use Auth;

class CircularRepository
{
    public function findForPublicWithPagination($perPage = 10)
    {
        return Circular::orderBy('created_at', 'DESC')->paginate($perPage);
    }

    public function findByCategoryWithPagination($categoryId, $perPage = 10)
    {
        return Circular::where('category', $categoryId)->orderBy('created_at', 'DESC')->paginate($perPage);
    }

    public function fetchForDatatable($archiveStatus = null, $category = null)
    {
        $threeMonthsAgo = Carbon::now()->subDays(90)->startOfDay();
        $query = Circular::with(['circularCategory', 'division'])->where('is_published', 1)->orderBy('published_date', 'desc');

        if (Auth::user()->hasRole("EMPLOYEE")):
            if ($archiveStatus === 'archive') {
                $query->where('published_date', '<=', $threeMonthsAgo);
            } else {
                $query->where('published_date', '>=', $threeMonthsAgo);
            }
        endif;

        if ($category != null) {
            $query->where('category', $category);
        }
        return $query;
    }


    public function findForPublic($limit = null)
    {
        if ($limit == null) {
            return Circular::with('circularCategory')->where('is_published', 1)->orderBy('published_date', 'desc')->get();
        }
        return Circular::with('circularCategory')->where('is_published', 1)->limit($limit)->orderBy('published_date', 'desc')->get();
    }

    public function findAll()
    {
        return Circular::with(['circularCategory', 'division'])->orderBy('published_date', 'desc')->get();
    }

    public function findById($id)
    {
        return Circular::with('circularCategory')->find($id);
    }

    public function create($data)
    {
        return Circular::create($data);
    }

    public function update($data, $id)
    {

        $result = Circular::find($id);
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
        $result = Circular::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
