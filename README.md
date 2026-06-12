# LMS POLSA (Learning Management System)

LMS POLSA adalah platform Learning Management System terintegrasi yang dirancang untuk mengelola seluruh kegiatan akademik, perkuliahan, dan manajemen data pokok perguruan tinggi. Sistem ini mendukung multi-peran dengan pembagian hak akses yang ketat untuk memastikan kelancaran administrasi perkuliahan dan penjaminan mutu akademik.

---

## 🚀 Fitur Utama & Alur Jalan Sistem

Sistem ini berjalan berdasarkan alur kerja terintegrasi antar pengguna (aktor):

### 1. Manajemen Data Pokok & Sinkronisasi (Admin)
* **Sesi Akademik:** Mengatur tahun ajaran aktif dan kalender akademik.
* **Sinkronisasi API:** Fitur sinkronisasi otomatis data Program Studi, Dosen, Mahasiswa, dan Kelas melalui API eksternal.
* **Manajemen Kurikulum:** Mengelola data kurikulum, program studi, dan pengelolaan mata kuliah (*courses*).
* **Jabatan Struktural:** Mengatur hak akses khusus dosen yang mendapat tugas tambahan (seperti Kaprodi atau BPM).

### 2. Transaksi Akademik & Kontrak Kuliah (Mahasiswa)
* **Pengisian KRS:** Mahasiswa melakukan kontrak mata kuliah secara digital melalui modul KRS.
* **Ruang Kelas Virtual:** Mengakses kelas yang diikuti, melihat agenda perkuliahan, mengunduh materi, dan mengumpulkan tugas (*assignment submission*).

### 3. Manajemen Pembelajaran & Nilai (Dosen)
* **Manajemen Kelas:** Mengunggah materi perkuliahan, membuat pengumuman, dan memberikan tugas.
* **Penilaian:** Memeriksa tugas mahasiswa, mengarsipkan nilai perkuliahan, serta mengekspor rekap nilai langsung ke format Excel.

### 4. Monitoring & Penjaminan Mutu (Struktural)
* **Dashboard Kaprodi:** Memantau jalannya perkuliahan dan kurikulum di tingkat program studi.
* **Dashboard BPM (Badan Penjaminan Mutu):** Melakukan monitoring dan evaluasi mutu akademik secara keseluruhan.

---

## 🛠️ Arsitektur Alur (System Flow)

1. **Autentikasi:** Semua pengguna masuk melalui satu gerbang login (`/login`). Sistem mendeteksi *role* pengguna menggunakan Middleware untuk mengarahkan pengguna ke dashboard masing-masing.
2. **Persiapan Data:** Admin mempersiapkan tahun akademik, menyinkronkan data mahasiswa/dosen, serta membuka kelas perkuliahan.
3. **Proses KRS:** Mahasiswa mengontrak mata kuliah yang dibuka pada semester aktif.
4. **KBM (Kegiatan Belajar Mengajar):** Dosen mengisi kelas dengan materi/tugas -> Mahasiswa mengunduh materi & mengumpulkan tugas -> Dosen memberikan nilai.
5. **Pelaporan:** Dosen mengekspor nilai ke Excel, sementara Kaprodi & BPM memantau statistik aktivitas melalui dashboard monitoring.

---

## 📂 Dokumentasi Kode & Struktur Controller

Aplikasi ini dibangun menggunakan arsitektur MVC (Model-View-Controller) Laravel dengan pembagian tugas controller sebagai berikut:

| Nama Controller | Deskripsi Tugas / Fitur |
| :--- | :--- |
| `AuthController` | Menangani proses autentikasi (Login, Logout, Session). |
| `ProfileController` | Mengelola pembaruan data profil pengguna yang sedang login. |
| `AdminDashboardController` | Menyediakan statistik data master untuk halaman utama Admin. |
| `ProdiController` & `KurikulumController` | Mengelola data program studi dan draf kurikulum aktif. |
| `DosenController` | Mengelola data profil dosen dan pengaturan jabatan struktural. |
| `MahasiswaController` | Mengelola data pokok mahasiswa. |
| `KelasController` | Mengatur pembukaan kelas perkuliahan dan pemetaan mata kuliah (*courses*). |
| `ApiSyncController` | Menangani *gateway* sinkronisasi data dari API eksternal ke database lokal. |
| `AkademikController` | Mengatur pergantian semester dan tahun ajaran aktif. |
| `DosenDashboardController` | Mengelola aktivitas mengajar dosen, pembuatan tugas, pengumuman, dan arsip nilai. |
| `MaterialController` | Menangani proses *upload* dan pengelolaan berkas materi kuliah. |
| `MahasiswaDashboardController` | Mengelola ruang belajar mahasiswa, melihat agenda, dan *upload* tugas. |
| `KrsController` | Menangani proses bisnis kontrak mata kuliah oleh mahasiswa. |
| `KaprodiDashboardController` | Menyediakan visualisasi data monitoring khusus Ketua Program Studi. |
| `BpmDashboardController` | Menyediakan visualisasi data monitoring kepatuhan mutu akademik untuk BPM. |

---

## 💻 Panduan Instalasi & Penggunaan

Ikuti langkah-langkah berikut untuk menjalankan proyek `lms_polsa` di lingkungan lokal Anda:

### Prasyarat (Prerequisites)
* PHP >= 8.1
* Composer
* Node.js & NPM
* Database Server (MySQL / MariaDB / SQLite)

### Langkah Langkah Instalasi

1. **Clone Repositori**
   ```bash
   git clone <url-repositori-anda>
   cd lms_polsa