<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAnggotaRequest;
use App\Http\Requests\UpdateAnggotaRequest;
use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnggotaController extends Controller
{
    public function index(Request $request): View
    {
        $anggota = Anggota::with('user')
            ->search($request->query('search'))
            ->latest()
            ->paginate(10);

        return view('anggota.index', compact('anggota'));
    }

    public function create(): View
    {
        $users = User::whereDoesntHave('anggota')->get();

        return view('anggota.create', compact('users'));
    }

    public function store(StoreAnggotaRequest $request): RedirectResponse
    {
        Anggota::create($request->validated());

        return redirect()
            ->route('anggota.index')
            ->with('success', 'Data anggota berhasil ditambahkan.');
    }

    public function edit(Anggota $anggota): View
    {
        $users = User::all();

        return view('anggota.edit', compact('anggota', 'users'));
    }

    public function update(UpdateAnggotaRequest $request, Anggota $anggota): RedirectResponse
    {
        $anggota->update($request->validated());

        return redirect()
            ->route('anggota.index')
            ->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(Anggota $anggota): RedirectResponse
    {
        if ($anggota->peminjaman()->whereIn('status', ['dipinjam', 'terlambat'])->count() > 0) {
            return redirect()
                ->route('anggota.index')
                ->with('error', 'Anggota tidak dapat dihapus karena masih memiliki peminjaman aktif.');
        }

        $anggota->delete();

        return redirect()
            ->route('anggota.index')
            ->with('success', 'Data anggota berhasil dihapus.');
    }
}
