<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public static function run(): void
    {
        $judul = 'Review Hasil Pengabdian Masyarakat di Desa Kaputihan oleh LPPM Universitas BSI Kota Tasikmalaya';

        News::create([
            'user_id' => User::first()->id,
            'judul' => $judul,
            'content' => 'belum tersedia',
            'slug' => Str::slug($judul),
            'thumbnail' => 'thumbnail.jpg',
            'status' => 'Draf',
        ]);
    }
}
