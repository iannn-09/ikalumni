<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Berita",
 *     type="object",
 *     title="Berita",
 *     description="Model data berita",
 *     @OA\Property(property="id", type="integer", description="ID berita"),
 *     @OA\Property(property="judul", type="string", description="Judul berita"),
 *     @OA\Property(property="slug", type="string", description="Slug berita"),
 *     @OA\Property(property="content", type="string", description="Isi berita"),
 *     @OA\Property(property="thumbnail", type="string", nullable=true, description="URL thumbnail"),
 *     @OA\Property(property="kategori_id", type="integer", description="ID kategori"),
 *     @OA\Property(property="user_id", type="integer", description="ID user pembuat"),
 *     @OA\Property(property="status", type="string", enum={"draft","published","archived"}, description="Status berita"),
 *     @OA\Property(property="published_at", type="string", format="date-time", nullable=true, description="Tanggal publikasi"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Tanggal dibuat"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Tanggal diupdate"),
 *     @OA\Property(property="kategori", ref="#/components/schemas/Kategori", description="Data kategori"),
 *     @OA\Property(property="user", ref="#/components/schemas/User", description="Data user pembuat")
 * )
 */
class Berita extends Model
{
    use HasFactory;

    protected $table = 'berita';

    protected $fillable = [
        'judul',
        'slug',
        'content',
        'thumbnail',
        'kategori_id',
        'user_id',
        'status',
        'published_at',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
