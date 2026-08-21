<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BukuController extends Controller
{
    public function index(Request $request): View
    {
        $buku = Buku::with('kategori')
            ->search($request->query('search'))
            ->latest()
            ->paginate(10);

        return view('buku.index', compact('buku'));
    }

    public function create(): View
    {
        $kategori = Kategori::all();

        return view('buku.create', compact('kategori'));
    }

    public function store(StoreBukuRequest $request): RedirectResponse
    {
        Buku::create($request->validated());

        return redirect()
            ->route('buku.index')
            ->with('success', 'Data buku berhasil ditambahkan.');
    }

    public function edit(Buku $buku): View
    {
        $kategori = Kategori::all();

        return view('buku.edit', compact('buku', 'kategori'));
    }

    public function update(UpdateBukuRequest $request, Buku $buku): RedirectResponse
    {
        $buku->update($request->validated());

        return redirect()
            ->route('buku.index')
            ->with('success', 'Data buku berhasil diperbarui.');
    }

    public function destroy(Buku $buku): RedirectResponse
    {
        if ($buku->peminjaman()->whereIn('status', ['dipinjam', 'terlambat'])->count() > 0) {
            return redirect()
                ->route('buku.index')
                ->with('error', 'Buku tidak dapat dihapus karena sedang dipinjam.');
        }

        $buku->delete();

        return redirect()
            ->route('buku.index')
            ->with('success', 'Data buku berhasil dihapus.');
    }
}
