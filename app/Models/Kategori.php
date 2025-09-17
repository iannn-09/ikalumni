<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Kategori",
 *     type="object",
 *     title="Kategori",
 *     description="Model data kategori",
 *     @OA\Property(property="id", type="integer", description="ID kategori"),
 *     @OA\Property(property="nama", type="string", description="Nama kategori"),
 *     @OA\Property(property="slug", type="string", description="Slug kategori"),
 *     @OA\Property(property="deskripsi", type="string", nullable=true, description="Deskripsi kategori"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Tanggal dibuat"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Tanggal diupdate")
 * )
 */
class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';

    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
    ];

    public function berita()
    {
        return $this->hasMany(Berita::class, 'kategori_id');
    }
}
