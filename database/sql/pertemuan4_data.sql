USE praktikum_web_2401020139;

INSERT INTO program_studi (nama_prodi) VALUES
    ('Teknik Perkapalan'),
    ('Teknik Elektro');

INSERT INTO mahasiswa
    (nim, nama, email, usia, program_studi_id)
VALUES
    ('2401010011', 'Rizky Maulana',
     'rizky@example.com', 19, 1),
    ('2401010012', 'Dewi Anggraini',
     'dewi@example.com', 20, 1),
    ('2401020021', 'Fajar Nugroho',
     'fajar@example.com', 21, 2),
    ('2401020099', 'Data Sementara',
     'sementara@example.com', 18, 2);

UPDATE mahasiswa
SET email = 'rizky.maulana@example.com'
WHERE nim = '2401010011';

DELETE FROM mahasiswa
WHERE nim = '2401020099';

SELECT m.nim, m.nama, m.email, m.usia,
       p.nama_prodi
FROM mahasiswa AS m
JOIN program_studi AS p
    ON p.id = m.program_studi_id
ORDER BY m.nim;
