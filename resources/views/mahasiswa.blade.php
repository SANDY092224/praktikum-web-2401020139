<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
</head>
<body>
    <h1>Data Mahasiswa</h1>

    @if (session('berhasil'))
        <p>{{ session('berhasil') }}</p>
    @endif

    @if (session('gagal'))
        <p>{{ session('gagal') }}</p>
    @endif

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <h2>Tambah Mahasiswa</h2>
    <form action="{{ route('mahasiswa.store') }}" method="POST">
        @csrf
        <p>NIM<br><input type="text" name="nim" value="{{ old('nim') }}"></p>
        <p>Nama<br><input type="text" name="nama" value="{{ old('nama') }}"></p>
        <p>Email<br><input type="email" name="email" value="{{ old('email') }}"></p>
        <p>Usia<br><input type="number" name="usia" value="{{ old('usia') }}"></p>
        <p>Program Studi<br>
            <select name="program_studi_id">
                <option value="">-- Pilih Program Studi --</option>
                @foreach ($daftarProgramStudi as $programStudi)
                    <option value="{{ $programStudi['id'] }}"
                        @selected(old('program_studi_id') == $programStudi['id'])>
                        {{ $programStudi['nama_prodi'] }}
                    </option>
                @endforeach
            </select>
        </p>
        <button type="submit">Tambah Data</button>
    </form>

    <h2>Pencarian dan Filter</h2>
    <form action="{{ route('mahasiswa.index') }}" method="GET">
        <p>Kata Kunci<br>
            <input type="text" name="q" value="{{ $kataKunci }}">
        </p>
        <p>Program Studi<br>
            <select name="program_studi_id">
                <option value="">-- Semua Program Studi --</option>
                @foreach ($daftarProgramStudi as $programStudi)
                    <option value="{{ $programStudi['id'] }}"
                        @selected((string) $programStudiId === (string) $programStudi['id'])>
                        {{ $programStudi['nama_prodi'] }}
                    </option>
                @endforeach
            </select>
        </p>
        <button type="submit">Cari</button>
        <a href="{{ route('mahasiswa.index') }}">Reset</a>
    </form>

    <h2>Daftar Mahasiswa</h2>
    <table border="1" cellpadding="6" cellspacing="0">
        <tr>
            <th>NIM</th><th>Nama</th><th>Email</th>
            <th>Usia</th><th>Program Studi</th><th>Aksi</th>
        </tr>
        @forelse ($daftarMahasiswa as $mahasiswa)
            <tr>
                <td>{{ $mahasiswa['nim'] }}</td>
                <td>{{ $mahasiswa['nama'] }}</td>
                <td>{{ $mahasiswa['email'] }}</td>
                <td>{{ $mahasiswa['usia'] }}</td>
                <td>{{ $mahasiswa['nama_prodi'] }}</td>
                <td>
                    <a href="{{ route('mahasiswa.edit', $mahasiswa['id']) }}">
                        Ubah
                    </a>
                    <form action="{{ route('mahasiswa.destroy', $mahasiswa['id']) }}"
                          method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                onclick="return confirm('Hapus data ini?')">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6">Data tidak ditemukan.</td></tr>
        @endforelse
    </table>
</body>
</html>