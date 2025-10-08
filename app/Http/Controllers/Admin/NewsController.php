<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

    public function store()
    {
        
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
