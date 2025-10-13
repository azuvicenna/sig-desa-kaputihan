<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NewsPhotosRequest;
use App\Http\Requests\Admin\NewsRequest;
use App\Models\News;
use App\Models\NewsPhoto;
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

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('folder_berita', 'public');
            $validatedData['thumbnail'] = $path;
        }

        $validatedData['slug'] = Str::slug($validatedData['judul']);
        $validatedData['user_id'] = Auth::id();

        News::create($validatedData);

        session()->flash('success', 'Berhasil menambahkan berita baru.');
        return Inertia::location(route('admin.news'));
    }

    public function storeImages(NewsPhotosRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('image_name')) {

            foreach ($validatedData['image_name'] as $imageFile) {
                $originalName = $imageFile->getClientOriginalName();

                $filenameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME);
                $extension = $imageFile->getClientOriginalExtension();
                $newFileName = uniqid() . '.' . $extension;

                $path = $imageFile->storeAs('foto_berita', $newFileName, 'public');

                NewsPhoto::create([
                    'news_id' => $validatedData['news_id'],
                    'image_name' => $path,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Gambar dokumentasi berhasil ditambahkan!');
    }

    public function show()
    {
        $title = 'Detail Berita Kegiatan';

        return Inertia::render('Admin/News/Detail', compact('title'));
    }

    public function edit($id)
    {
        $title = 'Edit Berita Kegiatan';
        $news = News::findOrFail($id);

        return Inertia::render('Admin/News/Edit', compact('title', 'news'));
    }

    public function update() {}

    public function delete() {}
}
