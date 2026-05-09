<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    // GET /api/albums
    public function index(Request $request): JsonResponse
    {
        // TODO: cursor pagination, capital + year filter
        return response()->json(['success' => true, 'data' => [], 'meta' => ['next_cursor' => null, 'prev_cursor' => null]]);
    }

    // GET /api/albums/{album}
    public function show(int $album): JsonResponse
    {
        // TODO: return album with publisher
        return response()->json(['success' => true, 'data' => null]);
    }

    // GET /api/albums/{album}/cover
    public function cover(int $album)
    {
        // TODO: composite 1/2/3 song covers (intervention/image)
        // 0 covers → 404 Cover Not Found, >3 → 400 Too many covers provided
        abort(404, 'Cover Not Found');
    }

    // GET /api/albums/{album}/songs
    public function songs(int $album): JsonResponse
    {
        // TODO: list songs ordered by `order` asc
        return response()->json(['success' => true, 'data' => []]);
    }

    // POST /api/albums (admin)
    public function store(Request $request): JsonResponse
    {
        // TODO: multipart create — title, artist, release_year, genre, description
        return response()->json(['success' => true, 'data' => null], 201);
    }

    // PUT /api/albums/{album} (admin)
    public function update(Request $request, int $album): JsonResponse
    {
        // TODO: ownership check; update title + description
        return response()->json(['success' => true, 'data' => null]);
    }

    // DELETE /api/albums/{album} (admin)
    public function destroy(int $album): JsonResponse
    {
        // TODO: soft delete + ownership check
        return response()->json(['success' => true]);
    }
}
