<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BeritaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('berita')->insert([
            [
                'judul' => 'Pengumuman Penting',
                'slug' => 'pengumuman-penting',
                'content' => 'Isi pengumuman penting.',
                'thumbnail' => null,
                'kategori_id' => 1,
                'user_id' => 1,
                'status' => 'published',
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Kegiatan Alumni',
                'slug' => 'kegiatan-alumni',
                'content' => 'Laporan kegiatan alumni.',
                'thumbnail' => null,
                'kategori_id' => 2,
                'user_id' => 1,
                'status' => 'draft',
                'published_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
