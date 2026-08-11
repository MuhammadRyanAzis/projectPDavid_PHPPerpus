<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnggotaRequest;
use App\Http\Requests\UpdateAnggotaRequest;
use App\Http\Resources\AnggotaResource;
use App\Models\Anggota;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AnggotaController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Anggota::with('user')
            ->search($request->query('search'))
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->latest();

        $perPage = (int) $request->query('per_page', 15);

        return AnggotaResource::collection($query->paginate($perPage));
    }

    public function store(StoreAnggotaRequest $request): JsonResponse
    {
        $anggota = Anggota::create($request->validated());
        $anggota->load('user');

        return response()->json([
            'message' => 'Anggota berhasil ditambahkan.',
            'data' => new AnggotaResource($anggota),
        ], 201);
    }

    public function show(Anggota $anggota): JsonResponse
    {
        $anggota->load('user');

        return response()->json([
            'data' => new AnggotaResource($anggota),
        ], 200);
    }

    public function update(UpdateAnggotaRequest $request, Anggota $anggota): JsonResponse
    {
        $anggota->update($request->validated());
        $anggota->load('user');

        return response()->json([
            'message' => 'Anggota berhasil diperbarui.',
            'data' => new AnggotaResource($anggota),
        ], 200);
    }

    public function destroy(Anggota $anggota): JsonResponse
    {
        if ($anggota->peminjaman()->whereIn('status', ['dipinjam', 'terlambat'])->count() > 0) {
            return response()->json([
                'message' => 'Anggota tidak dapat dihapus karena masih memiliki peminjaman aktif.',
            ], 422);
        }

        $anggota->delete();

        return response()->json([
            'message' => 'Anggota berhasil dihapus.',
        ], 200);
    }
}
