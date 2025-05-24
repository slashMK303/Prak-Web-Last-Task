**rancangan aplikasi perpustakaan berbasis website** menggunakan PHP, MySQL, JavaScript, dan Tailwind CSS, sesuai dengan kriteria yang kamu berikan:

---

## 🧩 **1. Struktur Database (MySQL)**

### 📌 Tabel: `users`

| Kolom      | Tipe Data              | Keterangan        |
| ---------- | ---------------------- | ----------------- |
| id         | INT (PK, AI)           | ID unik user      |
| nama       | VARCHAR(100)           | Nama lengkap      |
| email      | VARCHAR(100)           | Email unik        |
| password   | VARCHAR(255)           | Password hash     |
| role       | ENUM('admin','member') | Role user         |
| created_at | DATETIME               | Waktu pendaftaran |

---

### 📌 Tabel: `buku`

| Kolom      | Tipe Data    | Keterangan            |
| ---------- | ------------ | --------------------- |
| id         | INT (PK, AI) | ID buku               |
| judul      | VARCHAR(255) | Judul buku            |
| penulis    | VARCHAR(100) | Nama penulis          |
| penerbit   | VARCHAR(100) | Nama penerbit         |
| tahun      | YEAR         | Tahun terbit          |
| stok       | INT          | Jumlah stok tersedia  |
| created_at | DATETIME     | Waktu penambahan buku |

---

### 📌 Tabel: `peminjaman`

| Kolom           | Tipe Data                       | Keterangan                    |
| --------------- | ------------------------------- | ----------------------------- |
| id              | INT (PK, AI)                    | ID peminjaman                 |
| user_id         | INT (FK)                        | ID member yang meminjam       |
| buku_id         | INT (FK)                        | ID buku yang dipinjam         |
| tanggal_pinjam  | DATE                            | Tanggal peminjaman            |
| tanggal_kembali | DATE                            | Tanggal pengembalian (jadwal) |
| status          | ENUM('dipinjam','dikembalikan') | Status                        |
| created_at      | DATETIME                        | Waktu transaksi               |

---

## 🧭 **2. Hak Akses (Role)**

| Role   | Akses                                                          |
| ------ | -------------------------------------------------------------- |
| Admin  | Kelola user, kelola buku, lihat & kelola semua data peminjaman |
| Member | Lihat buku, pinjam buku, lihat riwayat peminjaman pribadi      |

---

## 🗺️ **3. Struktur Folder (Direktori)**

```
/perpustakaan
│
├── /config             # Koneksi database
├── /assets             # CSS, JS, gambar
├── /views              # Tampilan halaman
├── /controllers        # Logika aplikasi
├── /models             # Query-query database
├── /auth               # Login dan register
├── /admin              # Halaman admin
├── /member             # Halaman member
├── index.php           # Routing utama
```

---

## 🧑‍💻 **4. Fitur-fitur**

### 🔐 **Authentication**

-   Login & register (satu form, deteksi `role`)
-   Password hash menggunakan `password_hash()` PHP

### 📚 **Manajemen Buku (Admin)**

-   Tambah buku
-   Edit buku
-   Hapus buku
-   Tampilkan daftar buku

### 🧑‍🤝‍🧑 **Manajemen User (Admin)**

-   Tampilkan semua user
-   Nonaktifkan/hapus user

### 📄 **Peminjaman & Pengembalian**

-   Member dapat meminjam buku jika stok tersedia
-   Admin dapat melihat semua peminjaman
-   Member hanya bisa melihat riwayat pribadinya
-   Admin/member dapat mengembalikan buku → update status dan stok

---

## 🎨 **Desain UI (Tailwind CSS)**

### 🔷 Halaman Login/Register

-   Form sederhana, responsif
-   Validasi dengan JS

### 🔷 Dashboard Admin

-   Statistik: Jumlah buku, member, peminjaman aktif
-   Navigasi sidebar

### 🔷 Dashboard Member

-   Daftar buku tersedia
-   Riwayat peminjaman
-   Form peminjaman

---

#

---

## ✅ **Urutan Pengembangan Selanjutnya**

### 1. **Dashboard Admin dan Member (sudah mulai)** ✅

* ✨ Tambahkan menu navigasi.
* ✨ Tampilkan informasi singkat (jumlah buku, jumlah member, pinjaman aktif, dll).

---

### 2. **CRUD Buku (khusus Admin)** ✅

Fitur: tambah, lihat, ubah, hapus buku.

Tabel: `buku`

Halaman:

* `buku/index.php` → daftar buku
* `buku/tambah.php`
* `buku/edit.php?id=`
* `buku/hapus.php?id=`

---

### 3. **Manajemen Member (khusus Admin)** ✅

Fitur: lihat data member (dari `users` dengan role `member`)

* Daftar semua member
* Hapus / ubah data member (opsional)

---

### 4. **Peminjaman Buku (khusus Member)** 

Fitur:

* Lihat daftar buku yang tersedia
* Pinjam buku
* Batasi peminjaman jika stok = 0

Tabel: `peminjaman`

---

### 5. **Pengembalian Buku**

Fitur:

* Member bisa klik tombol “kembalikan”
* Admin bisa melihat riwayat peminjaman dan statusnya

Update field `status = 'dikembalikan'` di tabel `peminjaman`
Stok buku ditambah kembali saat pengembalian.

---

### 6. **Validasi Akses Role**

* Batasi akses:

  * Member tidak boleh masuk dashboard admin
  * Admin tidak bisa akses halaman member

---

### 7. **Bonus (opsional):**

* ✅ Pencarian buku
* ✅ Filter status peminjaman
* ✅ Histori peminjaman untuk member
* ✅ Fitur cetak laporan (PDF)

---

## Mau saya bantu kerjakan bagian CRUD buku selanjutnya?

