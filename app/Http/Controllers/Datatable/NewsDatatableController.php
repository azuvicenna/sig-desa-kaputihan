<?php

namespace App\Http\Controllers\Datatable;

use App\Http\Controllers\Controller;
use App\Services\NewsService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class NewsDatatableController extends Controller
{
    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function getNews()
    {
        $news = $this->newsService->getNews();

        return DataTables::of($news)
            ->addIndexColumn()
            ->addColumn('thumbnail', function ($row) {
                $placeholder = asset('placeholder/blog.svg');
                $imageUrl = $row->thumbnail
                    ? asset('storage/foto-berita/' . $row->thumbnail)
                    : $placeholder;

                return '<div style="width: 100px; aspect-ratio: 16 / 9; overflow: hidden;">
                            <img src="' . $imageUrl . '" alt="Foto Thumbnail" 
                                style="width: 100%; height: 100%; object-fit: cover;">
                        </div>';
            })
            ->addColumn('penulis', function ($row) {
                return $row->user->nama;
            })
            ->addColumn('action', function ($row) {
                return '
                <button class="shadcn-btn edit-btn" data-id="' . $row->id . '"><i class="fas fa-pen"></i></button>
                <button class="shadcn-btn detail-btn" data-id="' . $row->id . '"><i class="fas fa-book-open"></i></button>
                <button class="shadcn-btn delete-btn" data-id="' . $row->id . '"><i class="fas fa-trash"></i></button>
            ';
            })
            ->rawColumns(['action', 'thumbnail'])
            ->make(true);
    }
}
