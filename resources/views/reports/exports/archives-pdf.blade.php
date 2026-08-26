<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background: #f3f4f6; }
        h2 { margin-bottom: 4px; }
        p { margin-top: 0; color: #555; }
        a { color: #2563eb; text-decoration: none; }
    </style>
</head>
<body>
    <h2>Laporan Arsip</h2>
    <p>Periode: {{ $periodStart ?? '-' }} s/d {{ $periodEnd ?? '-' }}</p>

    <table>
        <thead>
            <tr>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Diunggah Oleh</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Berkas Asli</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($archives as $archive)
                <tr>
                    <td>{{ $archive->title }}</td>
                    <td>{{ $archive->category }}</td>
                    <td>{{ $archive->user->name ?? '-' }}</td>
                    <td>{{ $archive->status }}</td>
                    <td>{{ $archive->created_at->format('d-m-Y') }}</td>
                    <td>
                        <a href="{{ route('archives.download', $archive) }}">
                            {{ $archive->original_filename ?? basename($archive->file_path) }}
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>