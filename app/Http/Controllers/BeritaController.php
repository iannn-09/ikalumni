<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Berita",
 *     description="Manajemen data berita"
 * )
 */
class BeritaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/berita",
     *     summary="List semua berita",
     *     tags={"Berita"},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar berita",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Berita"))
     *     )
     * )
     */
    public function index()
    {
        $berita = Berita::with(['kategori', 'user'])
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($berita, 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/berita",
     *     summary="Tambah berita baru",
     *     tags={"Berita"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"judul","slug","content","kategori_id","user_id","status"},
     *             @OA\Property(property="judul", type="string"),
     *             @OA\Property(property="slug", type="string"),
     *             @OA\Property(property="content", type="string"),
     *             @OA\Property(property="thumbnail", type="string", nullable=true),
     *             @OA\Property(property="kategori_id", type="integer"),
     *             @OA\Property(property="user_id", type="integer"),
     *             @OA\Property(property="status", type="string", enum={"draft","published","archived"}),
     *             @OA\Property(property="published_at", type="string", format="date-time", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Berita berhasil dibuat",
     *         @OA\JsonContent(ref="#/components/schemas/Berita")
     *     ),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:berita,slug',
            'content' => 'required|string',
            'thumbnail' => 'nullable|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
        ]);
        $berita = Berita::create($validated);
        return response()->json($berita, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/berita/{slug}",
     *     summary="Detail berita",
     *     tags={"Berita"},
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Data berita",
     *         @OA\JsonContent(ref="#/components/schemas/Berita")
     *     ),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function show($slug)
    {
        $berita = Berita::with(['kategori', 'user'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();
        return response()->json($berita);
    }

    /**
     * @OA\Put(
     *     path="/api/berita/{id}",
     *     summary="Update berita",
     *     tags={"Berita"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="judul", type="string"),
     *             @OA\Property(property="slug", type="string"),
     *             @OA\Property(property="content", type="string"),
     *             @OA\Property(property="thumbnail", type="string", nullable=true),
     *             @OA\Property(property="kategori_id", type="integer"),
     *             @OA\Property(property="user_id", type="integer"),
     *             @OA\Property(property="status", type="string", enum={"draft","published","archived"}),
     *             @OA\Property(property="published_at", type="string", format="date-time", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berita berhasil diupdate",
     *         @OA\JsonContent(ref="#/components/schemas/Berita")
     *     ),
     *     @OA\Response(response=404, description="Not found"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function update(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);
        $validated = $request->validate([
            'judul' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|max:255|unique:berita,slug,' . $id,
            'content' => 'sometimes|required|string',
            'thumbnail' => 'nullable|string|max:255',
            'kategori_id' => 'sometimes|required|exists:kategori,id',
            'user_id' => 'sometimes|required|exists:users,id',
            'status' => 'sometimes|required|in:draft,published,archived',
            'published_at' => 'nullable|date',
        ]);
        $berita->update($validated);
        return response()->json($berita);
    }

    /**
     * @OA\Delete(
     *     path="/api/berita/{id}",
     *     summary="Hapus berita",
     *     tags={"Berita"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Berita dihapus"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);
        $berita->delete();
        return response()->json(null, 204);
    }
}
