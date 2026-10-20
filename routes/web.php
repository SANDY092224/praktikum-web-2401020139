<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

Route::get('/', function () {
    return view('welcome');
});

// Pertemuan 6: Read (menampilkan form dan tabel data)
Route::get('/mahasiswa', function () {
    try {
        $pdo = DB::connection()->getPdo();

        $pernyataanMahasiswa = $pdo->prepare(
            'SELECT m.nim, m.nama, m.email, m.usia,
                    p.nama_prodi
             FROM mahasiswa AS m
             JOIN program_studi AS p
                 ON p.id = m.program_studi_id
             ORDER BY m.nim'
        );
        $pernyataanMahasiswa->execute();
        $daftarMahasiswa = $pernyataanMahasiswa->fetchAll(
            \PDO::FETCH_ASSOC
        );

        $pernyataanProdi = $pdo->prepare(
            'SELECT id, nama_prodi
             FROM program_studi
             ORDER BY nama_prodi'
        );
        $pernyataanProdi->execute();
        $daftarProgramStudi = $pernyataanProdi->fetchAll(
            \PDO::FETCH_ASSOC
        );

        return view('mahasiswa', compact(
            'daftarMahasiswa',
            'daftarProgramStudi'
        ));
    } catch (\Throwable $error) {
        report($error);

        return response(
            'Data tidak dapat dibaca. Periksa koneksi dan query.',
            500
        );
    }
})->name('mahasiswa.index');

// Pertemuan 6: Create (validasi, cek duplikat, INSERT)
Route::post('/mahasiswa', function (Request $request) {
    $data = $request->validate([
        'nim'              => ['required', 'digits:10'],
        'nama'             => ['required', 'string', 'max:100'],
        'email'            => ['required', 'email', 'max:100'],
        'usia'             => ['required', 'integer', 'min:17', 'max:60'],
        'program_studi_id' => ['required', 'integer'],
    ], [
        'nim.required'              => 'NIM wajib diisi.',
        'nim.digits'                => 'NIM harus berupa 10 digit angka.',
        'nama.required'             => 'Nama wajib diisi.',
        'email.required'            => 'Email wajib diisi.',
        'email.email'               => 'Format email tidak valid.',
        'usia.required'             => 'Usia wajib diisi.',
        'usia.integer'              => 'Usia harus berupa angka.',
        'usia.min'                  => 'Usia minimal 17 tahun.',
        'usia.max'                  => 'Usia maksimal 60 tahun.',
        'program_studi_id.required' => 'Program studi wajib dipilih.',
    ]);

    try {
        $pdo = DB::connection()->getPdo();

        $cekDuplikat = $pdo->prepare(
            'SELECT COUNT(*) FROM mahasiswa
             WHERE nim = :nim OR email = :email'
        );
        $cekDuplikat->execute([
            'nim'   => $data['nim'],
            'email' => $data['email'],
        ]);

        if ((int) $cekDuplikat->fetchColumn() > 0) {
            return back()->withInput()->with(
                'gagal',
                'NIM atau email sudah digunakan.'
            );
        }

        $simpan = $pdo->prepare(
            'INSERT INTO mahasiswa
                (nim, nama, email, usia, program_studi_id)
             VALUES (:nim, :nama, :email, :usia, :program_studi_id)'
        );
        $simpan->execute([
            'nim'              => $data['nim'],
            'nama'             => $data['nama'],
            'email'            => $data['email'],
            'usia'             => $data['usia'],
            'program_studi_id' => $data['program_studi_id'],
        ]);

        return redirect()->route('mahasiswa.index')->with(
            'berhasil',
            'Data mahasiswa berhasil ditambahkan.'
        );
    } catch (\Throwable $error) {
        report($error);

        return back()->withInput()->with(
            'gagal',
            'Data gagal disimpan. Periksa koneksi dan data.'
        );
    }
})->name('mahasiswa.store');

Route::get('/latihan-php', function () {
    $nama = 'Sandy'; // ganti dengan nama lengkapmu
    $nilai = [60, 70, 65, 80, 55];

    $hitungRataRata = function (array $data): float {
        $total = 0;
        foreach ($data as $angka) {
            $total += $angka;
        }
        return $total / count($data);
    };

    $rataRata = $hitungRataRata($nilai);

    if ($rataRata >= 75) {
        $status = 'Lulus';
    } else {
        $status = 'Perlu Perbaikan';
    }

    return view('latihan-php', compact(
        'nama', 'nilai', 'rataRata', 'status'
    ));
});

Route::get('/form-mahasiswa', function () {
    return view('form-mahasiswa');
});

Route::post('/form-mahasiswa', function (Request $request) {
    $dataBersih = [
        'nama'  => strip_tags(trim((string) $request->input('nama'))),
        'email' => filter_var(
            (string) $request->input('email'),
            FILTER_SANITIZE_EMAIL
        ),
        'usia'  => trim((string) $request->input('usia')),
        'nim'   => trim((string) $request->input('nim')),
    ];

    $validator = Validator::make($dataBersih, [
        'nama'  => ['required', 'min:3', 'max:50'],
        'email' => ['required', 'email'],
        'usia'  => ['required', 'integer', 'min:17', 'max:60'],
        'nim'   => ['required', 'digits_between:8,12'],
    ], [
        'nama.required'      => 'Nama wajib diisi.',
        'nama.min'           => 'Nama minimal 3 karakter.',
        'email.required'     => 'Email wajib diisi.',
        'email.email'        => 'Format email tidak valid.',
        'usia.required'      => 'Usia wajib diisi.',
        'usia.integer'       => 'Usia harus berupa angka.',
        'usia.min'           => 'Usia minimal 17 tahun.',
        'usia.max'           => 'Usia maksimal 60 tahun.',
        'nim.required'       => 'NIM wajib diisi.',
        'nim.digits_between' => 'NIM harus berupa angka 8-12 digit.',
    ]);

    if ($validator->fails()) {
        return redirect('/form-mahasiswa')
            ->withErrors($validator)
            ->withInput();
    }

    $data = $validator->validated();
    $data['usia'] = (int) $data['usia'];

    return view('hasil-form', ['data' => $data]);
});