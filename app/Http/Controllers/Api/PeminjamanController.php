<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePeminjamanRequest;
use App\Http\Resources\PeminjamanResource;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Peminjaman::with(['anggota.user', 'buku.kategori', 'petugas', 'pengembalian'])
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->when($request->query('anggota_id'), fn ($q, $anggotaId) => $q->where('anggota_id', $anggotaId))
            ->when($request->query('buku_id'), fn ($q, $bukuId) => $q->where('buku_id', $bukuId))
            ->latest();

        $perPage = (int) $request->query('per_page', 15);

        return PeminjamanResource::collection($query->paginate($perPage));
    }

    public function store(StorePeminjamanRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $anggota = Anggota::findOrFail($validated['anggota_id']);
        if ($anggota->status !== 'aktif') {
            return response()->json([
                'message' => 'Anggota dengan nomor '.$anggota->nomor_anggota.' sedang tidak aktif.',
            ], 422);
        }

        $buku = Buku::findOrFail($validated['buku_id']);
        if ($buku->stok <= 0) {
            return response()->json([
                'message' => 'Stok buku "'.$buku->judul.'" sudah habis.',
            ], 422);
        }

        $petugasId = $validated['petugas_id'] ?? (Auth::id() ?? 2); // Default to petugas user ID if not passed
        $tglPinjam = isset($validated['tanggal_pinjam'])
            ? Carbon::parse($validated['tanggal_pinjam'])
            : now()->startOfDay();

        $tglJatuhTempo = isset($validated['tanggal_jatuh_tempo'])
            ? Carbon::parse($validated['tanggal_jatuh_tempo'])
            : $tglPinjam->copy()->addDays(7);

        $peminjaman = DB::transaction(function () use ($validated, $petugasId, $tglPinjam, $tglJatuhTempo, $buku) {
            $buku->decrement('stok', 1);

            return Peminjaman::create([
                'anggota_id' => $validated['anggota_id'],
                'buku_id' => $validated['buku_id'],
                'petugas_id' => $petugasId,
                'tanggal_pinjam' => $tglPinjam->format('Y-m-d'),
                'tanggal_jatuh_tempo' => $tglJatuhTempo->format('Y-m-d'),
                'status' => 'dipinjam',
            ]);
        });

        $peminjaman->load(['anggota.user', 'buku.kategori', 'petugas']);

        return response()->json([
            'message' => 'Peminjaman berhasil dicatat.',
            'data' => new PeminjamanResource($peminjaman),
        ], 201);
    }

    public function show(Peminjaman $peminjaman): JsonResponse
    {
        $peminjaman->load(['anggota.user', 'buku.kategori', 'petugas', 'pengembalian']);

        return response()->json([
            'data' => new PeminjamanResource($peminjaman),
        ], 200);
    }

    public function destroy(Peminjaman $peminjaman): JsonResponse
    {
        if ($peminjaman->status === 'dikembalikan') {
            return response()->json([
                'message' => 'Transaksi peminjaman yang sudah selesai tidak dapat dihapus.',
            ], 422);
        }

        DB::transaction(function () use ($peminjaman) {
            $peminjaman->buku()->increment('stok', 1);
            $peminjaman->delete();
        });

        return response()->json([
            'message' => 'Transaksi peminjaman berhasil dibatalkan dan dihapus.',
        ], 200);
    }
}
