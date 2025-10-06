<?php

namespace App\Repositories;

use App\Models\News;

class NewsRepository
{
    public function news()
    {
        return News::with('user:id,nama')->select(['id', 'user_id', 'judul', 'thumbnail', 'status'])->latest();
    }
}
