<?php

namespace App\Repositories;

use App\Models\Page;

class PageRepository
{
    public function findAll()
    {
        return Page::with(['menu', 'files'])->orderBy('id', 'desc')->get();
    }
    public function findAllForPublic()
    {
        return Page::with(['menu', 'files'])->where('is_published', 1)->orderBy('id', 'desc')->get();
    }

    public function findByUrlForPublic($url)
    {
        return Page::with(['menu', 'files'])->where('slug', $url)->where('is_published', 1)->orderBy('id', 'desc')->get();
    }

    public function findByMenuUrlForPublic($menuUrl)
    {
        return Page::with(['menu', 'files'])
            ->whereHas('menu', function ($query) use ($menuUrl) {
                $urls = [rtrim($menuUrl, '/'), rtrim($menuUrl, '/') . '/'];
                $query->whereIn('url', $urls);
            })
            ->where('is_published', 1)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function findById($id)
    {
        return Page::find($id);
    }

    public function findBySlug($slug)
    {
        return Page::where([
            'slug' => $slug
        ])->first();
    }

    public function create($data)
    {
        return Page::create($data);
    }

    public function update($data, $id)
    {
        $result = Page::find($id);
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
        $result = Page::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
