<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kategori')->insert([
            [
                'nama' => 'Pengumuman',
                'slug' => 'pengumuman',
                'deskripsi' => 'Kategori pengumuman',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Kegiatan',
                'slug' => 'kegiatan',
                'deskripsi' => 'Kategori kegiatan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
