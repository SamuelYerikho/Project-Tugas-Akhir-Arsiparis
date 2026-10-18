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
| Backend | Laravel 13, PHP 8.5 (FPM) |
| Database | PostgreSQL 16 |
| Web server | Nginx |
| Queue | Laravel Queue (driver `database`) |
| Realtime | Laravel Reverb, Laravel Echo, pusher-js |
| Frontend | Blade, Alpine.js, Tailwind (CDN), Vite (hanya untuk Echo) |
| OCR | Server FastAPI terpisah yang berjalan di laptop/host |
| Infrastruktur | Docker Compose |

## Arsitektur Container

| Service | Container | Fungsi | Port host |
|---|---|---|---|
| `app` | `samuel_app` | PHP-FPM (aplikasi Laravel) | - |
| `web` | `samuel_web` | Nginx | 8000 |
| `queue` | `samuel_queue` | Worker queue (job OCR) | - |
| `reverb` | `samuel_reverb` | Server WebSocket untuk notifikasi | 8080 |
| `db` | `samuel_db` | PostgreSQL | 5433 |

Server OCR (FastAPI) tidak ada di Docker. Ia berjalan di host dan dipanggil oleh service `queue` lewat `OCR_URL`.

## Prasyarat

- Docker dan Docker Compose
- Git
- Server OCR FastAPI berjalan di host pada port 8001
- Node.js **tidak perlu** dipasang. Perintah npm dijalankan lewat container Node sekali jalan (lihat bagian Frontend).

## Instalasi dari Nol

### 1. Ambil kode dan siapkan `.env`

```bash
git clone <url-repo> ProjectSamuel
cd ProjectSamuel
cp .env.example .env
```

Sesuaikan `.env` seperti pada bagian [Konfigurasi `.env`](#konfigurasi-env).

### 2. Jalankan container

```bash
docker compose up -d --build
```

### 3. Pasang dependensi PHP dan siapkan aplikasi

```bash
docker compose exec -e COMPOSER_MEMORY_LIMIT=-1 app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
docker compose exec app php artisan storage:link
```

Jika proyek memiliki seeder (peran, hak akses, dan akun awal), jalankan juga:

```bash
docker compose exec app php artisan db:seed
```

### 4. Bangun aset frontend

Dockerfile tidak memasang Node, jadi pakai container Node sementara. Opsi `-u` membuat `node_modules` dan `public/build` dimiliki user kamu, bukan root.

```bash
docker run --rm -u $(id -u):$(id -g) -v "$PWD":/app -w /app node:22 \
  sh -c "npm install && npm run build"
```

### 5. Buka aplikasi

- Aplikasi: <http://localhost:8000>
- WebSocket (Reverb): `ws://localhost:8080`

## Konfigurasi `.env`

Bagian penting yang harus diisi:

```dotenv
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=samuel
DB_USERNAME=samuel
DB_PASSWORD=root

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database

# OCR (server FastAPI di host)
OCR_URL=http://host.docker.internal:8001
OCR_TIMEOUT=300

# Broadcasting / Reverb
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=samuel
REVERB_APP_KEY=<isi dengan hasil: openssl rand -hex 16>
REVERB_APP_SECRET=<isi dengan hasil: openssl rand -hex 16>

# Dipakai Laravel (di dalam container) untuk mengirim event ke Reverb
REVERB_HOST=reverb
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

- `REVERB_APP_KEY` dan `REVERB_APP_SECRET` harus diisi sendiri. Jangan dibiarkan kosong, karena Laravel akan gagal membuat koneksi broadcast.
- Server memakai `REVERB_HOST=reverb` (nama service Docker), sedangkan browser memakai `VITE_REVERB_HOST=localhost`.
- Nilai `VITE_*` ditanam saat build. Setiap kali diubah, jalankan ulang build frontend (langkah 4).
- Jika `OCR_URL` dengan `host.docker.internal` tidak terhubung, gunakan IP host WSL (misalnya `http://172.17.224.1:8001`). IP ini bisa berubah setiap Windows dinyalakan ulang.

## Docker Compose

```yaml
services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: samuel_app
    working_dir: /var/www
    volumes:
      - .:/var/www
    depends_on:
      - db
    mem_limit: 512m
    cpus: 0.75
    networks:
      - sail

  web:
    image: nginx:latest
    container_name: samuel_web
    ports:
      - "8000:80"
    volumes:
      - .:/var/www
      - ./nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - app
    mem_limit: 256m
    cpus: 0.25
    networks:
      - sail

  # Queue worker: memproses job OCR di belakang layar.
  # Job mengirim file arsip ke server FastAPI di laptop
  # (http://host.docker.internal:8001/ocr).
  queue:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: samuel_queue
    working_dir: /var/www
    command: php artisan queue:work --tries=2 --timeout=600 --sleep=3
    restart: unless-stopped
    volumes:
      - .:/var/www
    depends_on:
      - db
    extra_hosts:
      - "host.docker.internal:host-gateway"
    mem_limit: 256m
    cpus: 0.25
    networks:
      - sail

  # Reverb: server WebSocket Laravel untuk notifikasi realtime.
  # Browser terhubung ke port 8080 di host (ws://localhost:8080).
  reverb:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: samuel_reverb
    working_dir: /var/www
    command: php artisan reverb:start --host=0.0.0.0 --port=8080
    restart: unless-stopped
    ports:
      - "8080:8080"
    volumes:
      - .:/var/www
    depends_on:
      - app
    mem_limit: 256m
    cpus: 0.25
    networks:
      - sail

  db:
    image: postgres:16
    container_name: samuel_db
    # Padanan innodb-buffer-pool-size (MySQL) di Postgres = shared_buffers.
    command: >
      postgres
      -c shared_buffers=256MB
      -c max_connections=40
    environment:
      POSTGRES_USER: samuel
      POSTGRES_PASSWORD: root
      POSTGRES_DB: samuel
    ports:
      - "5433:5432"
    volumes:
      - samuel_dbdata:/var/lib/postgresql/data
    mem_limit: 1g
    cpus: 0.75
    networks:
      - sail

networks:
  sail:
    driver: bridge

volumes:
  samuel_dbdata:
```

Dockerfile tidak perlu diubah untuk Reverb. Reverb adalah package PHP (diinstal lewat Composer), dan ekstensi `pcntl` yang dibutuhkan sudah terpasang.

## Frontend (npm)

Echo dan pusher-js dibundel Vite dari `resources/js/app.js`. Layout hanya memuat JS ini (`@vite('resources/js/app.js')`), sedangkan Tailwind dan Alpine tetap dari CDN.

```bash
# Pasang dependensi dan build produksi
docker run --rm -u $(id -u):$(id -g) -v "$PWD":/app -w /app node:22 \
  sh -c "npm install && npm run build"

# Hanya build ulang (setelah mengubah JS atau nilai VITE_* di .env)
docker run --rm -u $(id -u):$(id -g) -v "$PWD":/app -w /app node:22 \
  sh -c "npm run build"
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
# Menyalakan dan mematikan
docker compose up -d
docker compose down

# Lihat status dan log
docker compose ps
docker compose logs -f reverb
docker compose logs -f queue
tail -f storage/logs/laravel.log

# Artisan dan Composer
docker compose exec app php artisan migrate
docker compose exec app php artisan config:clear
docker compose exec app composer dump-autoload

# Restart worker setelah mengubah kode job atau notifikasi
docker compose restart queue

# Restart Reverb setelah mengubah .env atau konfigurasi
docker compose restart reverb
```

Worker `queue` dan `reverb` menyimpan kode di memori. Setiap kali mengubah job, notifikasi, atau `.env`, restart service terkait.

## Pengujian Notifikasi Manual

1. Login di browser, buka DevTools, lalu pastikan `window.Echo` berupa objek dan tab **Network → WS** menampilkan koneksi `localhost:8080` berstatus `101`.
2. Lewat tinker, kirim notifikasi ke user yang sedang login:

```bash
docker compose exec app php artisan tinker
```

```php
App\Models\User::find(1)->notifyNow(new App\Notifications\AktivitasNotifikasi('Tes', 'Notifikasi uji.'));
```

Titik kuning di lonceng harus muncul tanpa refresh.

Untuk pengujian antarpengguna, gunakan dua browser berbeda (atau satu jendela biasa dan satu incognito) yang login sebagai pengguna berbeda.

## Pemecahan Masalah

| Gejala | Penyebab dan solusi |
|---|---|
| `Failed to create broadcaster ... $auth_key null` | `REVERB_APP_KEY` di `.env` kosong. Isi, lalu `php artisan config:clear`. |
| `window.Echo` undefined | `@vite('resources/js/app.js')` belum ada di layout, atau build belum dijalankan. |
| Tidak ada koneksi WS | Cek `docker compose ps` (service `reverb` harus jalan) dan nilai `VITE_REVERB_*`, lalu build ulang. |
| `broadcasting/auth` 403 | User yang login tidak sama dengan penerima notifikasi. |
| `broadcasting/auth` 404 | `channels:` belum terdaftar di `bootstrap/app.php`. |
| `composer require` gagal soal `guzzlehttp/psr7` | Jalankan dengan `-W` (`--with-all-dependencies`). |
| Error `mailpit:1025` saat notifikasi | Class notifikasi memakai channel `mail`. Pastikan `via()` hanya `['database', 'broadcast']`. |
| `npm: executable file not found` di container | Node tidak ada di image PHP. Pakai container `node:22` seperti di atas. |
| Composer `Killed` | Memori container `app` (512m) kurang. Naikkan sementara atau pakai `COMPOSER_MEMORY_LIMIT=-1`. |
| OCR tidak jalan | Pastikan service `queue` hidup, server FastAPI aktif di host, dan `OCR_URL` benar. |
| Notifikasi tidak muncul setelah mengubah kode | Restart worker: `docker compose restart queue`. |
| Port 8080 bentrok | Ubah mapping menjadi `"8081:8080"` dan set `VITE_REVERB_PORT=8081`, lalu build ulang. |

## Catatan Keamanan

- Jangan commit file `.env`. Ganti `APP_KEY`, `REVERB_APP_KEY`, `REVERB_APP_SECRET`, dan kata sandi database sebelum dipakai di server sungguhan.
- Port database (5433) dan Reverb (8080) sebaiknya tidak dibuka ke jaringan publik.
- Untuk produksi, gunakan HTTPS: set `REVERB_SCHEME=https` dan `VITE_REVERB_SCHEME=https`, serta proxy WebSocket lewat Nginx.

## Lisensi

Proyek ini dibangun di atas Laravel, yang berlisensi [MIT](https://opensource.org/licenses/MIT).