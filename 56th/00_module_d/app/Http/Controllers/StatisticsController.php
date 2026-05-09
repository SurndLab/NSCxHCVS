<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    // GET /api/statistics?metrics=song|album|label&labels=Pop,Rock
    public function index(Request $request): JsonResponse
    {
        $metrics = $request->query('metrics');

        // TODO: implement three branches:
        //  - song: songs ordered by view_count desc, optional labels filter
        //  - album: albums ordered by total_view_count desc (sum of song.view_count)
        //  - label: grouped by label, top 10 songs each, optional labels filter
        return response()->json([
            'success' => true,
            'data' => [],
            'metrics' => $metrics,
        ]);
    }
}
