CREATE DATABASE IF NOT EXISTS praktikum_web_2401020139
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE praktikum_web_2401020139;

-- Perbaikan hasil review: berkas ini dapat dijalankan ulang.
-- Tabel anak (mahasiswa) dihapus lebih dulu agar foreign key tidak menghalangi.
DROP TABLE IF EXISTS mahasiswa;
DROP TABLE IF EXISTS program_studi;

CREATE TABLE program_studi (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_prodi VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE mahasiswa (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nim CHAR(10) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    usia TINYINT UNSIGNED NOT NULL,
    program_studi_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_mahasiswa_program_studi
        FOREIGN KEY (program_studi_id)
        REFERENCES program_studi(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT chk_usia
        CHECK (usia BETWEEN 17 AND 60),
    CONSTRAINT chk_nim_format
        CHECK (nim REGEXP '^[0-9]{10}$')
) ENGINE=InnoDB;
