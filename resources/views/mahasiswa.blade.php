<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
</head>
<body>
    <h1>Data Mahasiswa</h1>

    @if ($nim)
        <p>Filter NIM: {{ $nim }}</p>
    @else
        <p>Menampilkan semua data.</p>
    @endif

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>NIM</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Usia</th>
                <th>Program Studi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($daftarMahasiswa as $mahasiswa)
                <tr>
                    <td>{{ $mahasiswa['nim'] }}</td>
                    <td>{{ $mahasiswa['nama'] }}</td>
                    <td>{{ $mahasiswa['email'] }}</td>
                    <td>{{ $mahasiswa['usia'] }}</td>
                    <td>{{ $mahasiswa['nama_prodi'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Data tidak ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($nim)
        <p><a href="{{ url('/mahasiswa') }}">Tampilkan semua data</a></p>
    @endif
</body>
</html>