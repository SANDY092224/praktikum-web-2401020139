<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

Route::get('/', function () {
    return view('welcome');
});

// Pertemuan 5: koneksi PDO dan prepared statement
Route::get('/mahasiswa/{nim?}', function (?string $nim = null) {
    try {
        $pdo = DB::connection()->getPdo();

        $sql = 'SELECT m.nim, m.nama, m.email, m.usia,
                       p.nama_prodi
                FROM mahasiswa AS m
                JOIN program_studi AS p
                    ON p.id = m.program_studi_id';

        if ($nim !== null) {
            $sql .= ' WHERE m.nim = :nim';
        }

        $sql .= ' ORDER BY m.nim';

        $statement = $pdo->prepare($sql);
        $statement->execute($nim !== null ? ['nim' => $nim] : []);

        $daftarMahasiswa = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return view('mahasiswa', compact('daftarMahasiswa', 'nim'));
    } catch (\Throwable $error) {
        report($error);

        return response(
            'Koneksi atau query basis data gagal. Periksa file .env dan layanan MySQL.',
            500
        );
    }
});

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