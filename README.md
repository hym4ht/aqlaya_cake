# Aqlaya Cake

Sistem Informasi Pemesanan Toko Kue Berbasis Website pada Aqlaya Cake, dibangun dengan Laravel 11 dan SQLite untuk kebutuhan demo/prototipe.

## Fitur Utama

- Katalog customer dengan banner promo, kategori, best seller, pencarian, dan filter harga.
- Detail produk dengan kustomisasi ukuran, ucapan, tanggal/jam ambil atau antar, serta validasi lead time minimal H-2.
- Keranjang belanja dan checkout dengan opsi `Ambil di Toko` atau `Antar ke Alamat`.
- Simulasi integrasi Midtrans Snap dan webhook untuk mengubah status pembayaran otomatis.
- Tracking status pesanan customer dari `Pending Payment` sampai `Selesai`.
- Dashboard admin untuk konfirmasi pesanan, update status produksi, CRUD produk, dan laporan penjualan.
- Notifikasi internal untuk admin dan customer saat pembayaran/status order berubah.

## Akun Demo

- Admin: `admin@aqlaya.test` / `password`
- Customer: `customer@aqlaya.test` / `password`

## Menjalankan Project

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

Project default memakai SQLite (`database/database.sqlite`). Jika ingin menghubungkan Midtrans sandbox sungguhan, isi:

```env
MIDTRANS_SERVER_KEY=
MIDTRANS_CLIENT_KEY=
```

## Menjalankan Dengan Docker

Docker setup memakai PHP 8.2 + Apache, build asset Vite saat image dibuat, dan otomatis:

- menyiapkan volume `storage`
- memakai SQLite di `/var/www/storage/app/database.sqlite` agar data ikut persisten di volume
- menjalankan migrasi saat container start
- menjalankan `php artisan optimize` setelah migrasi

Langkah cepat:

```bash
cp .env.example .env
php artisan key:generate
docker compose up --build -d
```

App akan tersedia di `http://localhost:8080` secara default. Port dan perilaku startup bisa diatur dari `.env`:

```env
APP_PORT=8080
RUN_MIGRATIONS=true
RUN_OPTIMIZE=true
MIGRATION_MAX_ATTEMPTS=12
MIGRATION_RETRY_DELAY=5
DOCKER_WEB_NETWORK=web_network
DOCKER_USE_EXTERNAL_NETWORK=false
```

Untuk melihat log container:

```bash
docker compose logs -f app
```

Untuk menghentikan container:

```bash
docker compose down
```

Untuk reset data SQLite yang tersimpan di volume:

```bash
docker compose down -v
```

## Verifikasi

```bash
php artisan test
```

Test mencakup:

- halaman home berhasil dimuat
- validasi lead time H-2 saat menambah ke keranjang
- webhook pembayaran otomatis
- keputusan admin mengurangi stok dan memindahkan order ke tahap produksi
# aqlaya_cake
