# ARIS: Sistem Arsip RRI Stasiun Batam

Aplikasi manajemen arsip dokumen untuk RRI Stasiun Batam. Pengguna dapat mengunggah arsip, mencari isi dokumen lewat OCR, mengajukan izin akses, membuat laporan, dan menerima notifikasi realtime.

## Fitur

- Autentikasi, peran, dan hak akses per fitur (Spatie Laravel Permission)
- Unggah, ubah, hapus, dan unduh arsip (maksimal 10 MB per berkas)
- OCR otomatis di belakang layar lewat queue, dengan pencarian pada isi dokumen
- Permintaan izin akses arsip (ajukan, setujui, tolak)
- Laporan (PDF dan Excel)
- Notifikasi realtime lewat Laravel Reverb (WebSocket), dengan dropdown di navbar

## Teknologi

| Bagian | Teknologi |
|---|---|
| Backend | Laravel 13, PHP 8.5 |
| Database | PostgreSQL 16 |
| Web server | Laragon (Nginx atau Apache) atau `php artisan serve` |
| Queue | Laravel Queue (driver `database`) |
| Realtime | Laravel Reverb, Laravel Echo, pusher-js |
| Frontend | Blade, Alpine.js, Tailwind (CDN), Vite (hanya untuk Echo) |
| OCR | Server FastAPI terpisah yang berjalan di laptop/host |
| Lingkungan | Windows + Laragon |

## Proses yang Harus Berjalan

Selain web server, aplikasi ini butuh beberapa proses berjalan bersamaan:

| Proses | Perintah | Fungsi |
|---|---|---|
| Web | Laragon (Start All) atau `php artisan serve` | Menjalankan aplikasi di `http://localhost:8000` (artisan serve) atau `http://projectsamuel.test` (Laragon) |
| Queue | `php artisan queue:work --tries=2 --sleep=3` | Memproses job OCR |
| Reverb | `php artisan reverb:start` | Server WebSocket untuk notifikasi (port 8080) |
| OCR | Server FastAPI (di luar proyek ini) | Dipanggil oleh queue lewat `OCR_URL` (port 8001) |
| PostgreSQL | Service Windows | Database |

## Prasyarat

- [Laragon](https://laragon.org/) (edisi Full disarankan) dengan PHP 8.5 dan Composer
- PostgreSQL 16 (Laragon tidak menyertakannya secara bawaan, pasang dari [postgresql.org](https://www.postgresql.org/download/windows/))
- Node.js 22 atau lebih baru (sudah ada di Laragon Full, atau pasang dari [nodejs.org](https://nodejs.org/))
- Git
- Server OCR FastAPI berjalan di komputer yang sama pada port 8001

### Aktifkan ekstensi PHP

Di Laragon, klik kanan ikon Laragon, pilih **PHP → Extensions**, lalu centang:

- `pdo_pgsql`
- `pgsql`
- `fileinfo`, `mbstring`, `openssl`, `gd` (biasanya sudah aktif)

Atau edit `php.ini` (**Menu → PHP → php.ini**) dan hilangkan tanda `;` di depan:

```ini
extension=pdo_pgsql
extension=pgsql
```

Karena batas unggah arsip 10 MB, pastikan juga nilai berikut di `php.ini`:

```ini
upload_max_filesize = 10M
post_max_size = 12M
```

Restart PHP/web server Laragon setelah mengubah `php.ini`.

## Instalasi dari Nol

Buka **Terminal Laragon** (atau PowerShell/CMD) dan jalankan langkah berikut.

### 1. Ambil kode dan siapkan `.env`

Letakkan proyek di folder web root Laragon agar otomatis mendapat alamat `.test`:

```bash
cd C:\laragon\www
git clone <url-repo> ProjectSamuel
cd ProjectSamuel
copy .env.example .env
```

Sesuaikan `.env` seperti pada bagian [Konfigurasi `.env`](#konfigurasi-env).

### 2. Buat database PostgreSQL

Lewat pgAdmin atau `psql`:

```sql
CREATE USER samuel WITH PASSWORD 'root';
CREATE DATABASE samuel OWNER samuel;
```

### 3. Pasang dependensi PHP dan siapkan aplikasi

```bash
composer install
php artisan key:generate
php artisan migrate
php artisan storage:link
```

Jika proyek memiliki seeder (peran, hak akses, dan akun awal), jalankan juga:

```bash
php artisan db:seed
```

### 4. Pasang dan bangun aset frontend

```bash
npm install
npm run build
```

### 5. Jalankan aplikasi

Pilih salah satu cara menjalankan web:

- **Laragon**: klik **Start All**. Jika proyek ada di `C:\laragon\www\ProjectSamuel`, Laragon otomatis membuat alamat `http://projectsamuel.test` (aktifkan **Auto virtual hosts** di Laragon bila belum). Pastikan `APP_URL` di `.env` cocok dengan alamat ini.
- **Laravel CLI**: jalankan `php artisan serve`, lalu buka <http://localhost:8000>.

Kemudian buka dua terminal tambahan (di folder proyek) dan biarkan tetap berjalan:

```bash
# Terminal 2: worker queue (OCR)
php artisan queue:work --tries=2 --sleep=3

# Terminal 3: server WebSocket (notifikasi realtime)
php artisan reverb:start
```

Alamat penting:

- Aplikasi: `http://localhost:8000` (artisan serve) atau `http://projectsamuel.test` (Laragon)
- WebSocket (Reverb): `ws://localhost:8080`

## Konfigurasi `.env`

Bagian penting yang harus diisi:

```dotenv
# Sesuaikan: http://localhost:8000 (artisan serve) atau http://projectsamuel.test (Laragon)
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=samuel
DB_USERNAME=samuel
DB_PASSWORD=root

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database

# OCR (server FastAPI di komputer yang sama)
OCR_URL=http://127.0.0.1:8001
OCR_TIMEOUT=300

# Broadcasting / Reverb
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=samuel
REVERB_APP_KEY=<isi dengan string acak 32 karakter>
REVERB_APP_SECRET=<isi dengan string acak 32 karakter>

# Dipakai Laravel untuk mengirim event ke Reverb
REVERB_HOST=127.0.0.1
REVERB_PORT=8080
REVERB_SCHEME=http

# Dipakai server Reverb itu sendiri
REVERB_SERVER_HOST=0.0.0.0
REVERB_SERVER_PORT=8080

# Dipakai browser untuk terhubung
VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST=localhost
VITE_REVERB_PORT=8080
VITE_REVERB_SCHEME=http
```

Catatan:

- `REVERB_APP_KEY` dan `REVERB_APP_SECRET` harus diisi sendiri. Jangan dibiarkan kosong, karena Laravel akan gagal membuat koneksi broadcast. Windows tidak punya `openssl rand` bawaan, jadi buat nilainya lewat Terminal Laragon (Git Bash menyertakan `openssl`) atau lewat tinker:

```bash
  php artisan tinker --execute="echo bin2hex(random_bytes(16));"
```

- Karena semuanya berjalan di satu komputer, `REVERB_HOST` dan `VITE_REVERB_HOST` cukup mengarah ke komputer yang sama (`127.0.0.1` dan `localhost`).
- Nilai `VITE_*` ditanam saat build. Setiap kali diubah, jalankan ulang `npm run build`.
- Setelah mengubah `.env`, jalankan `php artisan config:clear` dan restart worker `queue` dan `reverb`.

## Frontend (npm)

Echo dan pusher-js dibundel Vite dari `resources/js/app.js`. Layout hanya memuat JS ini (`@vite('resources/js/app.js')`), sedangkan Tailwind dan Alpine tetap dari CDN.

```bash
# Pasang dependensi dan build produksi
npm install
npm run build

# Hanya build ulang (setelah mengubah JS atau nilai VITE_* di .env)
npm run build
```

`pusher-js` hanyalah library klien di browser. Aplikasi ini tidak memakai layanan Pusher dan tidak butuh akun Pusher.

## Notifikasi Realtime

Alur kerja:

1. Controller atau job memanggil `AktivitasNotifikasi::kirim(...)`.
2. Notifikasi disimpan ke tabel `notifications` dan di-broadcast ke channel privat `App.Models.User.{id}`.
3. Reverb meneruskannya ke browser, lalu lonceng di navbar langsung menampilkan titik dan item baru tanpa refresh.

Notifikasi yang dikirim:

| Kejadian | Penerima |
|---|---|
| Arsip baru diunggah | Pengguna dengan izin `view all archives` |
| Arsip diperbarui | Pemilik arsip |
| Arsip dihapus | Pemilik arsip |
| Permintaan akses baru | Pengguna dengan izin `approve archive access` |
| Permintaan akses disetujui atau ditolak | Pengaju permintaan |
| OCR selesai atau gagal | Pemilik arsip |

Pelaku aksi tidak menerima notifikasi dari aksinya sendiri.

Route notifikasi (di dalam grup `auth`):

| Method | URL | Fungsi |
|---|---|---|
| GET | `/notifikasi` | Daftar 10 notifikasi terbaru dan jumlah belum dibaca |
| POST | `/notifikasi/baca-semua` | Tandai semua sudah dibaca |
| POST | `/notifikasi/{id}/baca` | Tandai satu notifikasi sudah dibaca |

## Perintah Sehari-hari

```bash
# Menyalakan (masing-masing di terminal sendiri)
php artisan serve                                   # jika tidak memakai Laragon
php artisan queue:work --tries=2 --sleep=3
php artisan reverb:start

# Mematikan: tekan Ctrl+C di tiap terminal

# Artisan dan Composer
php artisan migrate
php artisan config:clear
composer dump-autoload

# Lihat log aplikasi (PowerShell)
Get-Content storage\logs\laravel.log -Wait -Tail 50
```

Worker `queue` dan `reverb` menyimpan kode di memori. Setiap kali mengubah job, notifikasi, atau `.env`, hentikan prosesnya dengan `Ctrl+C` lalu jalankan lagi. Untuk worker queue, bisa juga memakai `php artisan queue:restart`.

> **Catatan Windows:** ekstensi `pcntl` tidak tersedia di Windows. Akibatnya opsi `--timeout` pada `queue:work` tidak bisa memutus job yang menggantung, dan proses tidak berhenti secara halus (graceful) saat menerima sinyal. Untuk pemakaian pengembangan hal ini tidak masalah, tetapi pastikan server OCR membalas dalam batas `OCR_TIMEOUT`.

## Pengujian Notifikasi Manual

1. Login di browser, buka DevTools, lalu pastikan `window.Echo` berupa objek dan tab **Network → WS** menampilkan koneksi `localhost:8080` berstatus `101`.
2. Lewat tinker, kirim notifikasi ke user yang sedang login:

```bash
php artisan tinker
```

```php
App\Models\User::find(1)->notifyNow(new App\Notifications\AktivitasNotifikasi('Tes', 'Notifikasi uji.'));
```

Titik kuning di lonceng harus muncul tanpa refresh.

Untuk pengujian antarpengguna, gunakan dua browser berbeda (atau satu jendela biasa dan satu incognito) yang login sebagai pengguna berbeda.

## Pemecahan Masalah

| Gejala | Penyebab dan solusi |
|---|---|
| `could not find driver` | Ekstensi `pdo_pgsql` belum aktif di `php.ini`. Aktifkan lalu restart Laragon. |
| `SQLSTATE[08006] connection refused` | Service PostgreSQL belum berjalan, atau `DB_HOST`/`DB_PORT` salah. Cek di `services.msc`. |
| `Failed to create broadcaster ... $auth_key null` | `REVERB_APP_KEY` di `.env` kosong. Isi, lalu `php artisan config:clear`. |
| `window.Echo` undefined | `@vite('resources/js/app.js')` belum ada di layout, atau `npm run build` belum dijalankan. |
| Tidak ada koneksi WS | Pastikan `php artisan reverb:start` sedang berjalan, cek nilai `VITE_REVERB_*`, lalu build ulang. |
| `broadcasting/auth` 403 | User yang login tidak sama dengan penerima notifikasi. |
| `broadcasting/auth` 404 | `channels:` belum terdaftar di `bootstrap/app.php`. |
| `composer require` gagal soal `guzzlehttp/psr7` | Jalankan dengan `-W` (`--with-all-dependencies`). |
| Error `mailpit:1025` atau gagal kirim email saat notifikasi | Class notifikasi memakai channel `mail`. Pastikan `via()` hanya `['database', 'broadcast']`. |
| Unggah arsip gagal (berkas besar) | Naikkan `upload_max_filesize` dan `post_max_size` di `php.ini`, lalu restart Laragon. |
| Gambar profil atau arsip tidak tampil | Jalankan `php artisan storage:link`. Jika gagal di Windows, jalankan terminal sebagai Administrator. |
| OCR tidak jalan | Pastikan `queue:work` aktif, server FastAPI aktif di port 8001, dan `OCR_URL` benar. |
| Notifikasi tidak muncul setelah mengubah kode | Hentikan dan jalankan ulang `queue:work`. |
| Port 8080 bentrok | Ubah `REVERB_SERVER_PORT`, `REVERB_PORT`, dan `VITE_REVERB_PORT` ke port lain (misalnya 8081), lalu `npm run build` ulang. |
| `.test` tidak bisa dibuka | Aktifkan **Auto virtual hosts** di Laragon (Menu → Preferences), lalu Reload. Atau pakai `php artisan serve`. |

## Catatan Keamanan

- Jangan commit file `.env`. Ganti `APP_KEY`, `REVERB_APP_KEY`, `REVERB_APP_SECRET`, dan kata sandi database sebelum dipakai di server sungguhan.
- Port database (5432) dan Reverb (8080) sebaiknya tidak dibuka ke jaringan publik. Cek aturan Windows Firewall.
- Untuk produksi, gunakan HTTPS: set `REVERB_SCHEME=https` dan `VITE_REVERB_SCHEME=https`, serta proxy WebSocket lewat web server. Di produksi, sebaiknya gunakan server Linux dan Supervisor untuk menjaga `queue:work` dan `reverb:start` tetap hidup.

## Lisensi

Proyek ini dibangun di atas Laravel, yang berlisensi [MIT](https://opensource.org/licenses/MIT).