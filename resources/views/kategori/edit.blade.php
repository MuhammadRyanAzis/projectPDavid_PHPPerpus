<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Kategori</title>

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
            max-width: 600px;
            margin: auto;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        h1 {
            margin-top: 0;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .btn {
            padding: 10px 16px;
            border-radius: 6px;
            border: none;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .error {
            color: #dc2626;
            font-size: 14px;
            margin-bottom: 15px;
        }

    </style>

</head>

<body>

<div class="container">

    <div class="card">

        <h1>Edit Kategori</h1>

        @if($errors->any())

            <div class="error">

                <ul>

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif

        <form
            action="{{ route('kategori.update', $kategori->id) }}"
            method="POST">

            @csrf

            @method('PUT')

            <label for="nama_kategori">
                Nama Kategori
            </label>

            <input
                type="text"
                id="nama_kategori"
                name="nama_kategori"
                value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                required>

            <button
                type="submit"
                class="btn btn-primary">

                Update

            </button>

            <a
                href="{{ route('kategori.index') }}"
                class="btn btn-secondary">

                Kembali

            </a>

        </form>

    </div>

</div>

</body>

</html>