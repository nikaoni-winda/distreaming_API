# 🎬 Panduan Update Movie Poster & Description via MySQL

## 📋 Step-by-Step Guide

### Step 1: Jalankan SQL untuk Tambah Kolom (Kalau Belum)

Pastikan kamu sudah jalankan command ini dulu:

```sql
ALTER TABLE movies 
ADD COLUMN movie_poster VARCHAR(500) NULL AFTER production_year,
ADD COLUMN movie_description_en TEXT NULL AFTER movie_poster,
ADD COLUMN movie_description_id TEXT NULL AFTER movie_description_en;
```

### Step 2: Cari Data di TMDb

**Website:** https://www.themoviedb.org/

**Untuk setiap movie:**

1. **Cari film di TMDb:**
   - Ketik judul film di search bar
   - Klik hasil yang sesuai

2. **Copy Poster URL:**
   - Klik kanan pada poster film
   - Pilih "Copy Image Address" atau "Open Image in New Tab"
   - URL-nya biasanya format: `https://image.tmdb.org/t/p/original/xxxxx.jpg`
   - **UBAH `original` jadi `w500`** untuk size lebih kecil!
   - Jadi: `https://image.tmdb.org/t/p/w500/xxxxx.jpg`

3. **Copy Description (Overview):**
   - Scroll ke bagian "Overview"
   - Copy teks English-nya
   - Translate ke Indonesia pakai Google Translate

### Step 3: Isi Template SQL

Copy template di bawah, ganti `[POSTER_URL]`, `[DESC_EN]`, dan `[DESC_ID]` dengan data dari TMDb.

### Step 4: Execute di MySQL

- Buka phpMyAdmin / MySQL Workbench / DBeaver
- Select database `mini_project_distreaming`
- Paste SQL yang sudah diisi
- Execute!

---

## 🎯 Template SQL untuk Semua Movies

**Copy template ini dan isi satu per satu:**

```sql
-- ============================================
-- UPDATE MOVIE DATA WITH POSTER & DESCRIPTIONS
-- ============================================

USE mini_project_distreaming;

-- Movie 1: Interstellar 2: Murphy's Laws (2026)
UPDATE movies SET 
  movie_poster = '[INSERT_POSTER_URL_HERE]',
  movie_description_en = '[INSERT_ENGLISH_DESCRIPTION_HERE]',
  movie_description_id = '[INSERT_INDONESIAN_DESCRIPTION_HERE]'
WHERE movie_id = 1;

-- Movie 2: Inception (2010)
UPDATE movies SET 
  movie_poster = 'https://image.tmdb.org/t/p/w500/ljsZTbVsrQSqZgWeep2B1QiDKuh.jpg',
  movie_description_en = 'Cobb, a skilled thief who commits corporate espionage by infiltrating the subconscious of his targets is offered a chance to regain his old life as payment for a task considered to be impossible: "inception", the implantation of another person\'s idea into a target\'s subconscious.',
  movie_description_id = 'Cobb, seorang pencuri terampil yang melakukan spionase perusahaan dengan menyusup ke alam bawah sadar targetnya, ditawari kesempatan untuk mendapatkan kembali kehidupan lamanya sebagai pembayaran untuk tugas yang dianggap mustahil: "inception", penanaman ide orang lain ke dalam alam bawah sadar target.'
WHERE movie_id = 2;

-- Movie 3: The Dark Knight (2008)
UPDATE movies SET 
  movie_poster = 'https://image.tmdb.org/t/p/w500/qJ2tW6WMUDux911r6m7haRef0WH.jpg',
  movie_description_en = 'Batman raises the stakes in his war on crime. With the help of Lt. Jim Gordon and District Attorney Harvey Dent, Batman sets out to dismantle the remaining criminal organizations that plague the streets. The partnership proves to be effective, but they soon find themselves prey to a reign of chaos unleashed by a rising criminal mastermind known to the terrified citizens of Gotham as the Joker.',
  movie_description_id = 'Batman meningkatkan taruhannya dalam perangnya melawan kejahatan. Dengan bantuan Letnan Jim Gordon dan Jaksa Wilayah Harvey Dent, Batman berusaha membongkar organisasi kriminal yang tersisa di jalanan. Kemitraan ini terbukti efektif, tetapi mereka segera menjadi mangsa kekacauan yang dilepaskan oleh penjahat baru yang dikenal penduduk Gotham sebagai Joker.'
WHERE movie_id = 3;

-- Movie 4: La La Land (2016)
UPDATE movies SET 
  movie_poster = 'https://image.tmdb.org/t/p/w500/uDO8zWDhfWwoFdKS4fzkUJt0Rf0.jpg',
  movie_description_en = 'Mia, an aspiring actress, serves lattes to movie stars in between auditions and Sebastian, a jazz musician, scrapes by playing cocktail party gigs in dingy bars, but as success mounts they are faced with decisions that begin to fray the fragile fabric of their love affair, and the dreams they worked so hard to maintain in each other threaten to rip them apart.',
  movie_description_id = 'Mia, seorang calon aktris, menyajikan kopi untuk bintang film di antara audisi dan Sebastian, seorang musisi jazz, bertahan dengan bermain di bar-bar kumuh. Namun ketika kesuksesan datang, mereka dihadapkan pada keputusan yang mulai mengoyak hubungan cinta mereka, dan mimpi yang mereka perjuangkan mengancam memisahkan mereka.'
WHERE movie_id = 4;

-- Movie 5: Titanic (1997)
UPDATE movies SET 
  movie_poster = 'https://image.tmdb.org/t/p/w500/9xjZS2rlVxm8SFx8kPC3aIGCOYQ.jpg',
  movie_description_en = 'A seventeen-year-old aristocrat falls in love with a kind but poor artist aboard the luxurious, ill-fated R.M.S. Titanic.',
  movie_description_id = 'Seorang bangsawan berusia tujuh belas tahun jatuh cinta dengan seorang seniman yang baik namun miskin di atas kapal mewah R.M.S. Titanic yang malang.'
WHERE movie_id = 5;

-- Movie 6: The Lord of the Rings: The Fellowship of the Ring (2001)
UPDATE movies SET 
  movie_poster = 'https://image.tmdb.org/t/p/w500/6oom5QYQ2yQTMJIbnvbkBL9cHo6.jpg',
  movie_description_en = 'Young hobbit Frodo Baggins, after inheriting a mysterious ring from his uncle Bilbo, must leave his home in order to keep it from falling into the hands of its evil creator. Along the way, a fellowship is formed to protect the ringbearer and make sure that the ring arrives at its final destination: Mt. Doom, the only place where it can be destroyed.',
  movie_description_id = 'Hobbit muda Frodo Baggins, setelah mewarisi cincin misterius dari pamannya Bilbo, harus meninggalkan rumahnya agar cincin tidak jatuh ke tangan penciptanya yang jahat. Dalam perjalanan, sebuah persekutuan dibentuk untuk melindungi pembawa cincin dan memastikan cincin sampai ke tujuan akhirnya: Gunung Doom, satu-satunya tempat di mana cincin dapat dihancurkan.'
WHERE movie_id = 6;

-- Movie 7: Harry Potter and the Sorcerer's Stone (2001)
UPDATE movies SET 
  movie_poster = 'https://image.tmdb.org/t/p/w500/wuMc08IPKEatf9rnMNXvIDxqP4W.jpg',
  movie_description_en = 'Harry Potter has lived under the stairs at his aunt and uncle\'s house his whole life. But on his 11th birthday, he learns he\'s a powerful wizard—with a place waiting for him at the Hogwarts School of Witchcraft and Wizardry. As he learns to harness his newfound powers with the help of the school\'s kindly headmaster, Harry uncovers the truth about his parents\' deaths—and about the villain who\'s to blame.',
  movie_description_id = 'Harry Potter telah tinggal di bawah tangga di rumah bibi dan pamannya sepanjang hidupnya. Namun pada ulang tahunnya yang ke-11, dia mengetahui bahwa dia adalah penyihir yang kuat—dengan tempat yang menunggunya di Sekolah Sihir Hogwarts. Saat dia belajar menguasai kekuatan barunya dengan bantuan kepala sekolah yang baik hati, Harry mengungkap kebenaran tentang kematian orang tuanya—dan tentang penjahat yang bertanggung jawab.'
WHERE movie_id = 7;

-- Movie 8: Gladiator (2000)
UPDATE movies SET 
  movie_poster = 'https://image.tmdb.org/t/p/w500/ty8TGRuvJLPUmAR1H1nRIsgwvim.jpg',
  movie_description_en = 'In the year 180, the death of emperor Marcus Aurelius throws the Roman Empire into chaos. Maximus is one of the Roman army\'s most capable and trusted generals and a key advisor to the emperor. As Marcus\' devious son Commodus ascends to the throne, Maximus is set to be executed. He escapes, but is captured by slave traders. Renamed Spaniard and forced to become a gladiator, Maximus must battle to the death with other men for the amusement of paying audiences.',
  movie_description_id = 'Pada tahun 180, kematian kaisar Marcus Aurelius melemparkan Kekaisaran Romawi ke dalam kekacauan. Maximus adalah salah satu jenderal tentara Romawi yang paling cakap dan dipercaya serta penasihat utama kaisar. Ketika putra Marcus yang licik, Commodus, naik takhta, Maximus akan dieksekusi. Dia melarikan diri, tetapi ditangkap oleh pedagang budak. Diberi nama Spaniard dan dipaksa menjadi gladiator, Maximus harus bertarung sampai mati dengan pria lain untuk hiburan penonton yang membayar.'
WHERE movie_id = 8;

-- Movie 9: Parasite (2019)
UPDATE movies SET 
  movie_poster = 'https://image.tmdb.org/t/p/w500/7IiTTgloJzvGI1TAYymCfbfl3vT.jpg',
  movie_description_en = 'All unemployed, Ki-taek\'s family takes peculiar interest in the wealthy and glamorous Parks for their livelihood until they get entangled in an unexpected incident.',
  movie_description_id = 'Semua menganggur, keluarga Ki-taek mengambil minat aneh pada keluarga Park yang kaya dan glamor untuk mata pencaharian mereka sampai mereka terjerat dalam insiden yang tidak terduga.'
WHERE movie_id = 9;

-- Movie 10: The Matrix (1999)
UPDATE movies SET 
  movie_poster = 'https://image.tmdb.org/t/p/w500/f89U3ADr1oiB1s9GkdPOEpXUk5H.jpg',
  movie_description_en = 'Set in the 22nd century, The Matrix tells the story of a computer hacker who joins a group of underground insurgents fighting the vast and powerful computers who now rule the earth.',
  movie_description_id = 'Berlatar abad ke-22, The Matrix menceritakan kisah seorang peretas komputer yang bergabung dengan sekelompok pemberontak bawah tanah yang melawan komputer besar dan kuat yang sekarang menguasai bumi.'
WHERE movie_id = 10;

-- Movie 11: The Wolf of Wall Street (2013)
UPDATE movies SET 
  movie_poster = 'https://image.tmdb.org/t/p/w500/34m2tygAYBGqA9MXKhRDtzYd4MR.jpg',
  movie_description_en = 'A New York stockbroker refuses to cooperate in a large securities fraud case involving corruption on Wall Street, corporate banking world and mob infiltration. Based on Jordan Belfort\'s autobiography.',
  movie_description_id = 'Seorang pialang saham New York menolak bekerja sama dalam kasus penipuan sekuritas besar yang melibatkan korupsi di Wall Street, dunia perbankan korporat, dan infiltrasi mafia. Berdasarkan autobiografi Jordan Belfort.'
WHERE movie_id = 11;

-- Movie 12: The Avengers (2012)
UPDATE movies SET 
  movie_poster = 'https://image.tmdb.org/t/p/w500/RYMX2wcKCBAr24UyPD7xwmjaTn.jpg',
  movie_description_en = 'When an unexpected enemy emerges and threatens global safety and security, Nick Fury, director of the international peacekeeping agency known as S.H.I.E.L.D., finds himself in need of a team to pull the world back from the brink of disaster. Spanning the globe, a daring recruitment effort begins!',
  movie_description_id = 'Ketika musuh yang tidak terduga muncul dan mengancam keselamatan dan keamanan global, Nick Fury, direktur badan penjaga perdamaian internasional yang dikenal sebagai S.H.I.E.L.D., mendapati dirinya membutuhkan tim untuk menarik dunia kembali dari jurang bencana. Melintasi dunia, upaya rekrutmen yang berani dimulai!'
WHERE movie_id = 12;

-- Movie 13: Pride & Prejudice (2005)
UPDATE movies SET 
  movie_poster = 'https://image.tmdb.org/t/p/w500/sGjIvtVvTlWnia2zfJfHz81pZ9Q.jpg',
  movie_description_en = 'A story of love and life among the landed English gentry during the Georgian era. Mr. Bennet is a gentleman living in Hertfordshire with his overbearing wife and five daughters, but if he dies their house will be inherited by a distant cousin whom they have never met, so the family\'s future happiness and security is dependent on the daughters making good marriages.',
  movie_description_id = 'Kisah cinta dan kehidupan di antara bangsawan Inggris selama era Georgian. Tuan Bennet adalah seorang pria terhormat yang tinggal di Hertfordshire dengan istri yang dominan dan lima putri, tetapi jika dia meninggal rumah mereka akan diwarisi oleh sepupu jauh yang belum pernah mereka temui, jadi kebahagiaan dan keamanan masa depan keluarga bergantung pada putri-putri yang menikah dengan baik.'
WHERE movie_id = 13;

-- Movie 14: Black Swan (2010)
UPDATE movies SET 
  movie_poster = 'https://image.tmdb.org/t/p/w500/rH19vxjMzMw7eJj3lFJLRWEqVS6.jpg',
  movie_description_en = 'A journey through the psyche of a young ballerina whose starring role as the duplicitous swan queen turns out to be a part for which she becomes frighteningly perfect.',
  movie_description_id = 'Perjalanan melalui jiwa seorang balerina muda yang peran utamanya sebagai ratu angsa yang licik ternyata menjadi bagian yang membuatnya menjadi sempurna dengan cara yang menakutkan.'
WHERE movie_id = 14;

-- Movie 15: Saving Private Ryan (1998)
UPDATE movies SET 
  movie_poster = 'https://image.tmdb.org/t/p/w500/uqx37AFH1hP10IhJBkGIpB9C3Xf.jpg',
  movie_description_en = 'As U.S. troops storm the beaches of Normandy, three brothers lie dead on the battlefield, with a fourth trapped behind enemy lines. Ranger captain John Miller and seven men are tasked with penetrating German-held territory and bringing the boy home.',
  movie_description_id = 'Saat pasukan AS menyerbu pantai Normandia, tiga bersaudara tergeletak mati di medan perang, dengan saudara keempat terjebak di belakang garis musuh. Kapten Ranger John Miller dan tujuh pria ditugaskan untuk menembus wilayah yang dikuasai Jerman dan membawa anak itu pulang.'
WHERE movie_id = 15;

-- Movie 16: Love, Maybe (2003) - FICTIONAL MOVIE
UPDATE movies SET 
  movie_poster = '[INSERT_POSTER_URL_OR_USE_PLACEHOLDER]',
  movie_description_en = '[CREATE_YOUR_OWN_DESCRIPTION]',
  movie_description_id = '[BUAT_DESKRIPSI_SENDIRI]'
WHERE movie_id = 16;

-- Movie 17: Dear, Love Symphony (2024) - FICTIONAL MOVIE
UPDATE movies SET 
  movie_poster = '[INSERT_POSTER_URL_OR_USE_PLACEHOLDER]',
  movie_description_en = '[CREATE_YOUR_OWN_DESCRIPTION]',
  movie_description_id = '[BUAT_DESKRIPSI_SENDIRI]'
WHERE movie_id = 17;

-- Movie 23: Admin Test Movie (2024)
UPDATE movies SET 
  movie_poster = '[INSERT_POSTER_URL_OR_NULL]',
  movie_description_en = '[INSERT_DESCRIPTION_OR_NULL]',
  movie_description_id = '[INSERT_DESCRIPTION_OR_NULL]'
WHERE movie_id = 23;
```

---

## 💡 Tips & Tricks

### Untuk Movie yang Tidak Ada di TMDb (Fictional):

**Movie 16: Love, Maybe** dan **Movie 17: Dear, Love Symphony** sepertinya film fiktif (tidak ada di TMDb).

**Opsi:**
1. **Biarkan NULL** (tidak isi apa-apa)
2. **Bikin deskripsi sendiri** (creative freedom!)
3. **Pakai placeholder image** (misal foto generic romantic/fantasy)

**Contoh untuk fictional movie:**
```sql
UPDATE movies SET 
  movie_poster = 'https://via.placeholder.com/500x750?text=Love+Maybe',
  movie_description_en = 'A heartwarming romantic drama about love and second chances.',
  movie_description_id = 'Drama romantis yang menghangatkan hati tentang cinta dan kesempatan kedua.'
WHERE movie_id = 16;
```

### Untuk Movie 1: Interstellar 2

Ini sepertinya sequel dari Interstellar (fictional). Kamu bisa:
- Pakai poster Interstellar original
- Atau bikin deskripsi sendiri

---

## ✅ Verification

Setelah execute SQL, cek hasilnya:

```sql
SELECT 
  movie_id, 
  movie_title, 
  CHAR_LENGTH(movie_poster) as poster_length,
  CHAR_LENGTH(movie_description_en) as desc_en_length,
  CHAR_LENGTH(movie_description_id) as desc_id_length
FROM movies
ORDER BY movie_id;
```

---

## 🎯 Quick Checklist

- [ ] Step 1: Sudah tambah kolom di MySQL
- [ ] Step 2: Buka TMDb dan cari semua movies
- [ ] Step 3: Copy poster URLs (ubah `original` → `w500`)
- [ ] Step 4: Copy descriptions EN + translate ke ID
- [ ] Step 5: Isi template SQL di atas
- [ ] Step 6: Execute di MySQL
- [ ] Step 7: Verification query
- [ ] Step 8: Test API: `GET /api/movies`

---

**Catatan:** Aku udah isi sebagian besar (Movie 2-15) dengan data real dari TMDb! Kamu tinggal isi yang masih `[INSERT_xxx]` atau bisa langsung execute kalau mau pakai yang sudah ada! 🚀
