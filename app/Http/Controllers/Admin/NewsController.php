<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NewsPhotosRequest;
use App\Http\Requests\Admin\NewsRequest;
use App\Http\Requests\Admin\UpdateNewsRequest;
use App\Models\News;
use App\Models\NewsPhoto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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

        try {
            $slug = Str::slug($validatedData['judul']);
            $originalSlug = $slug;
            $counter = 1;
            while (News::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }
            $validatedData['slug'] = $slug;

            if ($request->hasFile('thumbnail')) {
                $thumbnail = $request->file('thumbnail');
                $uniqueFileName = uniqid() . '.' . $thumbnail->getClientOriginalExtension();
                $thumbnail->storeAs('foto-berita', $uniqueFileName, 'public');
                $validatedData['thumbnail'] = $uniqueFileName;
            }

            $validatedData['user_id'] = Auth::id();

            News::create($validatedData);

            session()->flash('success', 'Data berita berhasil ditambahkan.');
            return redirect()->route('admin.news');
        } catch (\Exception $e) {
            session()->flash('failed', 'Terjadi kesalahan saat mencoba menyimpan data');
            return Inertia::location(route('admin.newsCreate'));
        }
    }

    public function edit($id)
    {
        $title = 'Edit Berita Kegiatan';
        $news = News::with('newsPhotos')->findOrFail($id);

        return Inertia::render('Admin/News/Edit', compact('title', 'news'));
    }

    public function update(UpdateNewsRequest $request, $id)
    {
        $news = News::findOrFail($id);
        $data = $request->validated();

        try {
            if (isset($data['judul']) && $data['judul'] !== $news->judul) {
                $slug = Str::slug($data['judul']);
                $originalSlug = $slug;
                $counter = 1;
                while (News::where('slug', $slug)->where('id', '!=', $news->id)->exists()) {
                    $slug = $originalSlug . '-' . $counter++;
                }
                $data['slug'] = $slug;
            }

            if ($request->hasFile('thumbnail')) {
                if ($news->thumbnail) {
                    Storage::disk('public')->delete('foto-berita/' . $news->thumbnail);
                }
                $thumbnail = $request->file('thumbnail');
                $uniqueFileName = uniqid() . '.' . $thumbnail->getClientOriginalExtension();
                $thumbnail->storeAs('foto-berita', $uniqueFileName, 'public');
                $data['thumbnail'] = $uniqueFileName;
            } else {
                unset($data['thumbnail']);
            }

            $news->update($data);

            session()->flash('success', 'Data berita berhasil diperbarui.');
            return Inertia::location(route('admin.news'));
        } catch (\Exception $e) {
            session()->flash('failed', 'Terjadi kesalahan: ' . $e->getMessage());
            return Inertia::location(route('admin.newsEdit', $id));
        }
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);

        if ($news->newsPhotos->isNotEmpty()) {
            foreach ($news->newsPhotos as $photo) {
                Storage::disk('public')->delete('foto-berita/' . $photo->image_name);
            }
            $news->newsPhotos()->delete();
        }

        if ($news->thumbnail) {
            Storage::disk('public')->delete('foto-berita/' . $news->thumbnail);
        }

        $news->delete();

        session()->flash('success', 'Data berita dan semua fotonya berhasil dihapus.');
        return Inertia::location(route('admin.news'));
    }

    public function storeImages(NewsPhotosRequest $request, $id)
    {
        $request->validated();
        DB::beginTransaction();
        try {
            $photosToInsert = [];
            $timestamp = now();

            foreach ($request->file('image_name') as $imageFile) {
                $extension = $imageFile->getClientOriginalExtension();
                $newFileName = uniqid() . '_' . time() . '.' . $extension;
                $imageFile->storeAs('foto-berita', $newFileName, 'public');

                $photosToInsert[] = [
                    'id'         => Str::uuid(),
                    'news_id'    => $id,
                    'image_name' => $newFileName,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];
            }

            if (!empty($photosToInsert)) {
                NewsPhoto::insert($photosToInsert);
            }

            DB::commit();
            session()->flash('success', 'Gambar dokumentasi berhasil ditambahkan!');
            return Inertia::location(route('admin.newsEdit', $id));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('failed', 'Terjadi kesalahan saat mencoba mengupload gambar');
            return Inertia::location(route('admin.newsEdit', $id));
        }
    }

    public function deleteImage($id)
    {
        $photo = NewsPhoto::findOrFail($id);

        if ($photo->image_name) {
            Storage::disk('public')->delete('foto-berita/' . $photo->image_name);
        }
        $photo->delete();

        return redirect()->back()->with('success', 'Gambar dokumentasi berhasil dihapus.');
    }
}
