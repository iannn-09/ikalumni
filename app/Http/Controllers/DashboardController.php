<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\User;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Tag(
 *     name="Dashboard",
 *     description="Dashboard statistics"
 * )
 */
class DashboardController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/dashboard/stats",
     *     summary="Get dashboard statistics",
     *     tags={"Dashboard"},
     *     @OA\Response(
     *         response=200,
     *         description="Dashboard statistics",
     *         @OA\JsonContent(
     *             @OA\Property(property="totalNews", type="integer"),
     *             @OA\Property(property="totalUsers", type="integer"),
     *             @OA\Property(property="totalAlumni", type="integer"),
     *             @OA\Property(property="totalCategories", type="integer")
     *         )
     *     )
     * )
     */
    public function getStats()
    {
        $stats = [
            'totalNews' => Berita::count(),
            'totalUsers' => User::count(),
            'totalAlumni' => User::where('role', 'alumni')->count(),
            'totalCategories' => Kategori::count()
        ];

        return response()->json($stats);
    }

    /**
     * @OA\Get(
     *     path="/api/dashboard/news-by-category",
     *     summary="Get news count by category for chart",
     *     tags={"Dashboard"},
     *     @OA\Response(
     *         response=200,
     *         description="News count by category",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="value", type="integer"),
     *                 @OA\Property(property="color", type="string")
     *             )
     *         )
     *     )
     * )
     */
    public function getNewsByCategory()
    {
        $colors = ['#3B82F6', '#10B981', '#8B5CF6', '#F59E0B', '#EF4444', '#6B7280', '#EC4899', '#14B8A6'];

        try {
            $newsData = Kategori::leftJoin('berita', 'kategori.id', '=', 'berita.kategori_id')
                ->select('kategori.nama as name', DB::raw('COUNT(berita.id) as value'))
                ->groupBy('kategori.id', 'kategori.nama')
                ->orderBy('value', 'desc')
                ->get()
                ->map(function ($item, $index) use ($colors) {
                    return [
                        'name' => $item->name,
                        'value' => (int) $item->value,
                        'color' => $colors[$index % count($colors)]
                    ];
                });

            // Log the result for debugging
            \Log::info('News by category data:', $newsData->toArray());

            return response()->json($newsData, 200, [
                'Content-Type' => 'application/json',
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getNewsByCategory: ' . $e->getMessage());

            return response()->json([
                'error' => 'Failed to fetch news by category',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
