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
                <td>{{ ucfirst($archive->status) }}</td>
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