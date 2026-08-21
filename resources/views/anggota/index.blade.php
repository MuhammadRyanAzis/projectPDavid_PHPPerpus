<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Anggota Perpustakaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Daftar Anggota</h1>
            <a href="{{ route('anggota.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Tambah Anggota</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="py-3 px-4">No. Anggota</th>
                    <th class="py-3 px-4">Nama</th>
                    <th class="py-3 px-4">Email</th>
                    <th class="py-3 px-4">Alamat</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($anggota as $item)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-4 font-mono font-bold text-blue-600">{{ $item->nomor_anggota }}</td>
                        <td class="py-3 px-4">{{ $item->user->name ?? '-' }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $item->user->email ?? '-' }}</td>
                        <td class="py-3 px-4">{{ $item->alamat }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 text-xs rounded {{ $item->status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 flex gap-2">
                            <a href="{{ route('anggota.edit', $item->id) }}" class="text-yellow-600 hover:underline">Edit</a>
                            <form action="{{ route('anggota.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-4 text-center text-gray-500">Belum ada data anggota.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $anggota->links() }}
        </div>
    </div>
</body>
</html>
