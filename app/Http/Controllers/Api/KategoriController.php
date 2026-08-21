<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreKategoriRequest;
use App\Http\Requests\UpdateKategoriRequest;
use App\Http\Resources\KategoriResource;
use App\Models\Kategori;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class KategoriController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Kategori::withCount('buku')
            ->search($request->query('search'))
            ->latest();

        $perPage = (int) $request->query('per_page', 15);

        return KategoriResource::collection($query->paginate($perPage));
    }

    public function store(StoreKategoriRequest $request): JsonResponse
    {
        $kategori = Kategori::create($request->validated());

        return response()->json([
            'message' => 'Kategori berhasil ditambahkan.',
            'data' => new KategoriResource($kategori),
        ], 201);
    }

    public function show(Kategori $kategori): JsonResponse
    {
        $kategori->loadCount('buku');

        return response()->json([
            'data' => new KategoriResource($kategori),
        ], 200);
    }

    public function update(UpdateKategoriRequest $request, Kategori $kategori): JsonResponse
    {
        $kategori->update($request->validated());

        return response()->json([
            'message' => 'Kategori berhasil diperbarui.',
            'data' => new KategoriResource($kategori),
        ], 200);
    }

    public function destroy(Kategori $kategori): JsonResponse
    {
        if ($kategori->buku()->count() > 0) {
            return response()->json([
                'message' => 'Kategori tidak dapat dihapus karena masih memiliki buku terkait.',
            ], 422);
        }

        $kategori->delete();

        return response()->json([
            'message' => 'Kategori berhasil dihapus.',
        ], 200);
    }
}
