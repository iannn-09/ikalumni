<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Kategori",
 *     description="Manajemen data kategori"
 * )
 */
class KategoriController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/kategori",
     *     summary="List semua kategori",
     *     tags={"Kategori"},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar kategori",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Kategori"))
     *     )
     * )
     */
    public function index()
    {
        $kategori = Kategori::all();
        return response()->json($kategori);
    }

    /**
     * @OA\Post(
     *     path="/api/kategori",
     *     summary="Tambah kategori baru",
     *     tags={"Kategori"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nama","slug"},
     *             @OA\Property(property="nama", type="string"),
     *             @OA\Property(property="slug", type="string"),
     *             @OA\Property(property="deskripsi", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Kategori berhasil dibuat",
     *         @OA\JsonContent(ref="#/components/schemas/Kategori")
     *     ),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:kategori,slug',
            'deskripsi' => 'nullable|string',
        ]);
        $kategori = Kategori::create($validated);
        return response()->json($kategori, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/kategori/{id}",
     *     summary="Detail kategori",
     *     tags={"Kategori"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Data kategori",
     *         @OA\JsonContent(ref="#/components/schemas/Kategori")
     *     ),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function show($id)
    {
        $kategori = Kategori::findOrFail($id);
        return response()->json($kategori);
    }

    /**
     * @OA\Put(
     *     path="/api/kategori/{id}",
     *     summary="Update kategori",
     *     tags={"Kategori"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nama", type="string"),
     *             @OA\Property(property="slug", type="string"),
     *             @OA\Property(property="deskripsi", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Kategori berhasil diupdate",
     *         @OA\JsonContent(ref="#/components/schemas/Kategori")
     *     ),
     *     @OA\Response(response=404, description="Not found"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|max:255|unique:kategori,slug,' . $id,
            'deskripsi' => 'nullable|string',
        ]);
        $kategori->update($validated);
        return response()->json($kategori);
    }

    /**
     * @OA\Delete(
     *     path="/api/kategori/{id}",
     *     summary="Hapus kategori",
     *     tags={"Kategori"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Kategori dihapus"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();
        return response()->json(null, 204);
    }
}
