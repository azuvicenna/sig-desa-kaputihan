<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NewsRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;

class NewsController extends Controller
{
    public function index()
    {
        $title = 'Berita Kegiatan';
        
        return Inertia::render('Admin/News/Index', compact('title'));
    }

    public function create()
    {
        $title = 'Buat Berita Kegiatan';
        
        return Inertia::render('Admin/News/Create', compact('title'));
    }

    public function store(NewsRequest $request)
    {
        $validatedData = $request->validated();

        $validatedData['slug'] = Str::slug($validatedData);
        $validatedData['user_id'] = Auth::user()->id;

        // belum kelar
    }

    public function show()
    {
        $title = 'Detail Berita Kegiatan';
        
        return Inertia::render('Admin/News/Detail', compact('title'));
    }

    public function edit()
    {
        $title = 'Edit Berita Kegiatan';
        
        return Inertia::render('Admin/News/Edit', compact('title'));
    }

    public function update()
    {
        
    }

    public function delete()
    {
        
    }
}
