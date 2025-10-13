<?php

namespace App\Repositories;

use App\Models\News;

class NewsRepository
{
    public function news()
    {
        return News::with('user:id,nama')->select(['id', 'user_id', 'judul', 'thumbnail', 'status'])->latest();
    }

    public function paginated($search)
    {
        $query = News::query()
            ->select(['id', 'slug', 'judul', 'thumbnail', 'content', 'created_at'])->where('status', 'Published');

        $newsPaginated = $query->when($search, function ($query, $search) {
            $query->where('judul', 'like', "%{$search}%");
        })->latest()
            ->paginate(6)->withQueryString();

        return $newsPaginated;
    }
}
