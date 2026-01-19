# SQL Command untuk Menambah Kolom Role ke Tabel Users

## Instruksi

Halo! Karena project ini menggunakan database MySQL yang sudah ada, kamu perlu **jalankan SQL command ini secara manual** di MySQL kamu ya! 😊

## SQL Command

```sql
ALTER TABLE users 
ADD COLUMN role ENUM('admin', 'user') NOT NULL DEFAULT 'user' 
AFTER password;
```

## Penjelasan Sederhana

Command di atas seperti kamu **nambah kolom baru** di tabel users namanya `role`:
- **ENUM('admin', 'user')**: Kolom ini cuma bisa diisi 'admin' atau 'user', tidak bisa yang lain
- **NOT NULL**: Kolom ini wajib diisi, tidak boleh kosong
- **DEFAULT 'user'**: Kalau tidak diisi, otomatis jadi 'user'
- **AFTER password**: Taruh kolom ini setelah kolom password

## Cara Menjalankan

### Opsi 1: Lewat phpMyAdmin
1. Buka phpMyAdmin
2. Pilih database `mini_project_distreaming`
3. Klik tab "SQL"
4. Copy-paste command di atas
5. Klik "Go" / "Jalankan"

### Opsi 2: Lewat Terminal/MySQL CLI
```bash
mysql -u root -p
```
Lalu ketik password MySQL kamu, kemudian:
```sql
USE mini_project_distreaming;
ALTER TABLE users ADD COLUMN role ENUM('admin', 'user') NOT NULL DEFAULT 'user' AFTER password;
```

### Opsi 3: Lewat IDE (MySQL Workbench, DBeaver, dll)
1. Connect ke database kamu
2. Pilih database `mini_project_distreaming`
3. Jalankan SQL command di atas

## Verifikasi (Cek Apakah Berhasil)

Setelah jalankan command di atas, coba jalankan ini untuk cek:
```sql
DESCRIBE users;
```

Harusnya kamu lihat kolom baru namanya `role` dengan type `enum('admin','user')`

## Update User yang Sudah Ada (Opsional)

Semua user yang sudah ada di database akan otomatis jadi 'user'. Kalau kamu mau buat salah satu user jadi admin, jalankan:

```sql
-- Lihat dulu semua user
SELECT user_id, user_nickname, user_email, role FROM users;

-- Update user tertentu jadi admin (ganti ID-nya sesuai yang kamu mau)
UPDATE users SET role = 'admin' WHERE user_id = 1;
```

## Catatan Penting! ⚠️

Setelah jalankan SQL command ini, **JANGAN jalankan `php artisan migrate`** ya! Karena bisa bikin error (migration sudah tidak sinkron dengan database).

Migration di project Laravel cuma untuk **dokumentasi** aja, bukan untuk dijalankan.

---

Kalau sudah selesai jalankan SQL-nya, kasih tahu aku ya supaya kita bisa lanjut testing! 🎉
