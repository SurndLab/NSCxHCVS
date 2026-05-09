<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SongController extends Controller
{
    // GET /api/songs (public)
    public function index(Request $request): JsonResponse
    {
        // TODO: keyword filter + cursor pagination
        return response()->json(['success' => true, 'data' => [], 'meta' => ['next_cursor' => null, 'prev_cursor' => null]]);
    }

    // GET /api/songs/{song}/cover (public)
    public function cover(int $song)
    {
        // TODO: stream cover_image_path as image/jpeg; 404 if missing
        abort(404, 'Cover Not Found');
    }

    // GET /api/songs/{song} (auth)
    public function show(int $song): JsonResponse
    {
        // TODO: increment view_count, return full detail
        return response()->json(['success' => true, 'data' => null]);
    }

    // POST /api/albums/{album}/songs (admin)
    public function storeInAlbum(Request $request, int $album): JsonResponse
    {
        // TODO: multipart create with cover_image upload, label CSV → labels[]
        // is_cover=true 上限 3 張 → 400 Too many covers provided
        return response()->json(['success' => true, 'data' => null], 201);
    }

    // PUT /api/albums/{album}/songs/order (admin)
    public function updateOrder(Request $request, int $album): JsonResponse
    {
        // TODO: validate song_ids belong to album, reassign `order`
        return response()->json(['success' => true]);
    }

    // POST /api/albums/{album}/songs/{song} (admin)
    public function updateInAlbum(Request $request, int $album, int $song): JsonResponse
    {
        // TODO: multipart update, same is_cover guard
        return response()->json(['success' => true, 'data' => null]);
    }

    // DELETE /api/albums/{album}/songs/{song} (admin)
    public function destroyFromAlbum(int $album, int $song): JsonResponse
    {
        // TODO: soft delete, ownership check
        return response()->json(['success' => true]);
    }
}
