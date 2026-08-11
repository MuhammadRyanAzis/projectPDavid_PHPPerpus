<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePengembalianRequest;
use App\Http\Resources\PengembalianResource;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengembalianController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Pengembalian::with(['peminjaman.anggota.user', 'peminjaman.buku', 'petugas'])
            ->latest();

        $perPage = (int) $request->query('per_page', 15);

        return PengembalianResource::collection($query->paginate($perPage));
    }

    public function store(StorePengembalianRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $peminjaman = Peminjaman::findOrFail($validated['peminjaman_id']);
        if ($peminjaman->status === 'dikembalikan') {
            return response()->json([
                'message' => 'Buku untuk transaksi peminjaman ini sudah dikembalikan sebelumnya.',
            ], 422);
        }

        $petugasId = $validated['petugas_id'] ?? (Auth::id() ?? 2);
        $tglPengembalian = isset($validated['tanggal_pengembalian'])
            ? Carbon::parse($validated['tanggal_pengembalian'])
            : now()->startOfDay();

        $denda = isset($validated['denda'])
            ? (float) $validated['denda']
            : $peminjaman->hitungDenda($tglPengembalian);

        $pengembalian = DB::transaction(function () use ($peminjaman, $petugasId, $tglPengembalian, $denda) {
            $peminjaman->update([
                'status' => 'dikembalikan',
            ]);

            $peminjaman->buku()->increment('stok', 1);

            return Pengembalian::create([
                'peminjaman_id' => $peminjaman->id,
                'petugas_id' => $petugasId,
                'tanggal_pengembalian' => $tglPengembalian->format('Y-m-d'),
                'denda' => $denda,
            ]);
        });

        $pengembalian->load(['peminjaman.anggota.user', 'peminjaman.buku', 'petugas']);

        return response()->json([
            'message' => 'Pengembalian buku berhasil diproses.',
            'data' => new PengembalianResource($pengembalian),
        ], 201);
    }

    public function show(Pengembalian $pengembalian): JsonResponse
    {
        $pengembalian->load(['peminjaman.anggota.user', 'peminjaman.buku.kategori', 'petugas']);

        return response()->json([
            'data' => new PengembalianResource($pengembalian),
        ], 200);
    }
}
