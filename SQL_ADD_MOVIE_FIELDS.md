# SQL Command untuk Menambah Kolom Poster & Description ke Tabel Movies

## Instruksi

Karena project ini menggunakan database MySQL yang sudah ada, kamu perlu **jalankan SQL command ini secara manual** di MySQL ya! 😊

## SQL Command

```sql
ALTER TABLE movies 
ADD COLUMN movie_poster VARCHAR(500) NULL AFTER production_year,
ADD COLUMN movie_description_en TEXT NULL AFTER movie_poster,
ADD COLUMN movie_description_id TEXT NULL AFTER movie_description_en;
```

## Penjelasan Sederhana

Command di atas akan **menambah 3 kolom baru** di tabel movies:

1. **`movie_poster`**
   - Type: VARCHAR(500)
   - Nullable (boleh kosong)
   - Isi: URL poster dari TMDb
   - Contoh: `https://image.tmdb.org/t/p/w500/abc123.jpg`

2. **`movie_description_en`**
   - Type: TEXT (untuk teks panjang)
   - Nullable (boleh kosong)
   - Isi: Deskripsi film dalam **Bahasa Inggris**
   - Contoh: `"A seventeen-year-old aristocrat falls in love..."`

3. **`movie_description_id`**
   - Type: TEXT
   - Nullable (boleh kosong)
   - Isi: Deskripsi film dalam **Bahasa Indonesia**
   - Contoh: `"Seorang bangsawan berusia tujuh belas tahun jatuh cinta..."`

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
ALTER TABLE movies 
ADD COLUMN movie_poster VARCHAR(500) NULL AFTER production_year,
ADD COLUMN movie_description_en TEXT NULL AFTER movie_poster,
ADD COLUMN movie_description_id TEXT NULL AFTER movie_description_en;
```

### Opsi 3: Lewat IDE (MySQL Workbench, DBeaver, dll)
1. Connect ke database kamu
2. Pilih database `mini_project_distreaming`
3. Jalankan SQL command di atas

## Verifikasi (Cek Apakah Berhasil)

Setelah jalankan command di atas, coba jalankan ini untuk cek:
```sql
DESCRIBE movies;
```

Harusnya kamu lihat 3 kolom baru:
- `movie_poster` (varchar(500), NULL)
- `movie_description_en` (text, NULL)
- `movie_description_id` (text, NULL)

## Tips Mengisi Data

### Untuk movie_poster:
Ambil dari **TMDb** (https://www.themoviedb.org/):
1. Cari film di TMDb
2. Klik kanan poster → Copy Image Address
3. Paste URL-nya

Format URL TMDb:
```
https://image.tmdb.org/t/p/w500/[poster_path]
```

### Untuk movie_description:
Bisa ambil dari TMDb juga (ada overview), atau bikin sendiri:
- **EN**: Copy dari TMDb atau IMDb (English version)
- **ID**: Translate manual atau pakai Google Translate

## Contoh Update Data (Setelah Kolom Ditambah)

```sql
-- Update movie Titanic
UPDATE movies SET 
  movie_poster = 'https://image.tmdb.org/t/p/w500/9xjZS2rlVxm8SFx8kPC3aIGCOYQ.jpg',
  movie_description_en = 'A seventeen-year-old aristocrat falls in love with a kind but poor artist aboard the luxurious, ill-fated R.M.S. Titanic.',
  movie_description_id = 'Seorang bangsawan berusia tujuh belas tahun jatuh cinta dengan seorang seniman yang baik namun miskin di kapal mewah R.M.S. Titanic yang malang.'
WHERE movie_id = 1;
```

## Catatan Penting! ⚠️

- Semua kolom **NULLABLE** (boleh kosong), jadi tidak akan error kalau ada movie tanpa poster/description
- Kamu bisa isi data secara bertahap (tidak harus sekaligus semua 17 movies)
- Posterior bisa pakai size `w500` dari TMDb (balance bagus antara quality & file size)

---

Kalau sudah selesai jalankan SQL-nya, kasih tahu aku ya! 🎉
