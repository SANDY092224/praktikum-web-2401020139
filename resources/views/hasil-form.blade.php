<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Hasil Form</title>
</head>
<body>
<h1>Data Berhasil Diproses</h1>
<p>Nama: {{ $data['nama'] }}</p>
<p>Email: {{ $data['email'] }}</p>
<p>Usia: {{ $data['usia'] }} tahun</p>
<p>NIM: {{ $data['nim'] }}</p>
<p><a href="/form-mahasiswa">Kembali ke form</a></p>
</body>
</html>