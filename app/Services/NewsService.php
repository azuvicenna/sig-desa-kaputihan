<?php

namespace App\Services;

use App\Repositories\NewsRepository;

class NewsService
{
    protected $newsRepository;

    public function __construct(NewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    public function getNews()
    {
        return $this->newsRepository->news();
    }

    public function paginatedResult($search)
    {
        return $this->newsRepository->paginated($search);
    }
}
