<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use App\Http\Resources\BukuResource;
use App\Models\Buku;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BukuController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Buku::with('kategori')
            ->search($request->query('search'))
            ->when($request->query('kategori_id'), fn ($q, $catId) => $q->where('kategori_id', $catId))
            ->when($request->boolean('ada_stok'), fn ($q) => $q->adaStok())
            ->latest();

        $perPage = (int) $request->query('per_page', 15);

        return BukuResource::collection($query->paginate($perPage));
    }

    public function store(StoreBukuRequest $request): JsonResponse
    {
        $buku = Buku::create($request->validated());
        $buku->load('kategori');

        return response()->json([
            'message' => 'Buku berhasil ditambahkan.',
            'data' => new BukuResource($buku),
        ], 201);
    }

    public function show(Buku $buku): JsonResponse
    {
        $buku->load('kategori');

        return response()->json([
            'data' => new BukuResource($buku),
        ], 200);
    }

    public function update(UpdateBukuRequest $request, Buku $buku): JsonResponse
    {
        $buku->update($request->validated());
        $buku->load('kategori');

        return response()->json([
            'message' => 'Buku berhasil diperbarui.',
            'data' => new BukuResource($buku),
        ], 200);
    }

    public function destroy(Buku $buku): JsonResponse
    {
        if ($buku->peminjaman()->whereIn('status', ['dipinjam', 'terlambat'])->count() > 0) {
            return response()->json([
                'message' => 'Buku tidak dapat dihapus karena sedang dipinjam.',
            ], 422);
        }

        $buku->delete();

        return response()->json([
            'message' => 'Buku berhasil dihapus.',
        ], 200);
    }
}
