<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePeminjamanRequest;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
    public function index(Request $request): View
    {
        $peminjaman = Peminjaman::with(['anggota.user', 'buku.kategori', 'petugas'])
            ->latest()
            ->paginate(10);

        return view('peminjaman.index', compact('peminjaman'));
    }

    public function create(): View
    {
        $anggota = Anggota::where('status', 'aktif')->get();
        $buku = Buku::where('stok', '>', 0)->get();

        return view('peminjaman.create', compact('anggota', 'buku'));
    }

    public function store(StorePeminjamanRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $anggota = Anggota::findOrFail($validated['anggota_id']);
        if ($anggota->status !== 'aktif') {
            return redirect()->back()->withInput()->with('error', 'Anggota sedang tidak aktif.');
        }

        $buku = Buku::findOrFail($validated['buku_id']);
        if ($buku->stok <= 0) {
            return redirect()->back()->withInput()->with('error', 'Stok buku sudah habis.');
        }

        $petugasId = Auth::id() ?? 2;
        $tglPinjam = isset($validated['tanggal_pinjam'])
            ? Carbon::parse($validated['tanggal_pinjam'])
            : now()->startOfDay();

        $tglJatuhTempo = isset($validated['tanggal_jatuh_tempo'])
            ? Carbon::parse($validated['tanggal_jatuh_tempo'])
            : $tglPinjam->copy()->addDays(7);

        DB::transaction(function () use ($validated, $petugasId, $tglPinjam, $tglJatuhTempo, $buku) {
            $buku->decrement('stok', 1);

            Peminjaman::create([
                'anggota_id' => $validated['anggota_id'],
                'buku_id' => $validated['buku_id'],
                'petugas_id' => $petugasId,
                'tanggal_pinjam' => $tglPinjam->format('Y-m-d'),
                'tanggal_jatuh_tempo' => $tglJatuhTempo->format('Y-m-d'),
                'status' => 'dipinjam',
            ]);
        });

        return redirect()
            ->route('peminjaman.index')
            ->with('success', 'Transaksi peminjaman berhasil dicatat.');
    }

    public function destroy(Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status === 'dikembalikan') {
            return redirect()
                ->route('peminjaman.index')
                ->with('error', 'Transaksi yang sudah selesai tidak dapat dihapus.');
        }

        DB::transaction(function () use ($peminjaman) {
            $peminjaman->buku()->increment('stok', 1);
            $peminjaman->delete();
        });

        return redirect()
            ->route('peminjaman.index')
            ->with('success', 'Transaksi peminjaman berhasil dibatalkan.');
    }
}
