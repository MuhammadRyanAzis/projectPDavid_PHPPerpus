<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Kategori</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 40px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        h1 {
            margin: 0;
            color: #222;
        }

        .btn {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background: #2563eb;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-warning {
            background: #f59e0b;
        }

        .btn-danger {
            background: #dc2626;
        }

        .card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 14px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #f8fafc;
        }

        .actions {
            display: flex;
            gap: 8px;
        }

        .alert {
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 6px;
            background: #dcfce7;
            color: #166534;
        }

        .empty {
            text-align: center;
            padding: 30px;
            color: #777;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1>Data Kategori</h1>

        <a href="{{ route('kategori.create') }}"
           class="btn btn-primary">
            + Tambah Kategori
        </a>
    </div>

    @if(session('success'))
        <div class="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">

        <table>

            <thead>
                <tr>
                    <th width="80">No</th>
                    <th>Nama Kategori</th>
                    <th width="200">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($kategori as $item)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $item->nama_kategori }}
                        </td>

                        <td>

                            <div class="actions">

                                <a
                                    href="{{ route('kategori.edit', $item->id) }}"
                                    class="btn btn-warning">
                                    Edit
                                </a>

                                <form
                                    action="{{ route('kategori.destroy', $item->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger">
                                        Hapus
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="3" class="empty">
                            Belum ada data kategori.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

</body>
</html>