<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePengembalianRequest;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PengembalianController extends Controller
{
    public function index(Request $request): View
    {
        $pengembalian = Pengembalian::with(['peminjaman.anggota.user', 'peminjaman.buku', 'petugas'])
            ->latest()
            ->paginate(10);

        return view('pengembalian.index', compact('pengembalian'));
    }

    public function create(): View
    {
        $peminjaman = Peminjaman::with(['anggota.user', 'buku'])
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->get();

        return view('pengembalian.create', compact('peminjaman'));
    }

    public function store(StorePengembalianRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $peminjaman = Peminjaman::findOrFail($validated['peminjaman_id']);
        if ($peminjaman->status === 'dikembalikan') {
            return redirect()->back()->withInput()->with('error', 'Buku sudah dikembalikan sebelumnya.');
        }

        $petugasId = Auth::id() ?? 2;
        $tglPengembalian = isset($validated['tanggal_pengembalian'])
            ? Carbon::parse($validated['tanggal_pengembalian'])
            : now()->startOfDay();

        $denda = isset($validated['denda'])
            ? (float) $validated['denda']
            : $peminjaman->hitungDenda($tglPengembalian);

        DB::transaction(function () use ($peminjaman, $petugasId, $tglPengembalian, $denda) {
            $peminjaman->update([
                'status' => 'dikembalikan',
            ]);

            $peminjaman->buku()->increment('stok', 1);

            Pengembalian::create([
                'peminjaman_id' => $peminjaman->id,
                'petugas_id' => $petugasId,
                'tanggal_pengembalian' => $tglPengembalian->format('Y-m-d'),
                'denda' => $denda,
            ]);
        });

        return redirect()
            ->route('pengembalian.index')
            ->with('success', 'Pengembalian buku berhasil diproses.');
    }
}
