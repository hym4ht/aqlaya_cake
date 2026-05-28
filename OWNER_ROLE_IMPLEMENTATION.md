# Owner (Super Admin) Role Implementation

## Overview
Telah ditambahkan role **Owner** (Super Admin) ke dalam aplikasi Ayaqla Cake. Owner memiliki akses yang sama dengan Admin, namun dengan tampilan dashboard yang berbeda dan lebih premium.

## Bug Fix: Dynamic Route Prefix
Telah diperbaiki masalah hak akses dimana controller dan view menggunakan hardcoded route names `admin.*`. Sekarang sistem menggunakan dynamic route prefix yang mendeteksi apakah user adalah owner atau admin, sehingga redirect dan link akan sesuai dengan role user.

### Perubahan:
1. **Controller Base Class** - Menambahkan method [`getRoutePrefix()`](app/Http/Controllers/Controller.php:8) untuk mendeteksi prefix route
2. **AppServiceProvider** - Menambahkan view composer untuk share variable `$routePrefix` ke semua view
3. **Admin Controllers** - Update semua redirect untuk menggunakan dynamic prefix
4. **Admin Views** - Update semua route helper untuk menggunakan `{$routePrefix}` variable

## Fitur Owner Role

### 1. **User Model**
- Ditambahkan method [`isOwner()`](app/Models/User.php:76) untuk mengecek apakah user adalah owner
- Owner memiliki role `'owner'` di database

### 2. **Authentication**
- Login sebagai owner akan redirect ke [`/owner`](routes/web.php:50) dashboard
- Credentials owner default:
  - Email: `owner@aqlaya.test`
  - Password: `password`

### 3. **Dashboard Owner**
Dashboard owner memiliki fitur yang sama dengan admin:
- Statistik pendapatan (hari ini & bulan ini)
- Monitoring pesanan masuk
- Approval customer baru
- Produk terlaris
- Stok rendah
- Notifikasi sistem
- Quick actions

### 4. **Routes**
Semua route owner menggunakan prefix [`/owner`](routes/web.php:50) dengan middleware `auth` dan `role:owner`:
- [`owner.dashboard`](routes/web.php:54) - Dashboard utama
- [`owner.products.*`](routes/web.php:57) - Manajemen produk
- [`owner.orders.*`](routes/web.php:60) - Manajemen pesanan
- [`owner.banners.*`](routes/web.php:59) - Manajemen banner
- [`owner.reports.index`](routes/web.php:65) - Laporan
- [`owner.customers.decide`](routes/web.php:55) - Approval customer

### 5. **UI/UX Differences**
Owner dashboard memiliki tampilan yang lebih premium:
- Sidebar dengan gradient dark (slate-900 to slate-800)
- Accent color amber/orange untuk highlight
- Icon crown/star untuk branding
- Label "Super Admin" di user section
- Gradient buttons untuk active menu items

### 6. **Controllers**
- [`App\Http\Controllers\Owner\DashboardController`](app/Http/Controllers/Owner/DashboardController.php) - Controller khusus untuk owner dashboard
- Owner menggunakan controller Admin yang sama untuk fitur lainnya (products, orders, banners, reports)

### 7. **Views**
- Layout: [`resources/views/layouts/owner.blade.php`](resources/views/layouts/owner.blade.php)
- Dashboard: [`resources/views/owner/dashboard/index.blade.php`](resources/views/owner/dashboard/index.blade.php)

### 8. **Notifications**
[`NotificationService`](app/Services/NotificationService.php:20) telah diupdate untuk mengirim notifikasi ke owner dan admin secara bersamaan.

## Testing

### Login sebagai Owner
1. Buka `/login`
2. Gunakan credentials:
   - Email: `owner@aqlaya.test`
   - Password: `password`
3. Setelah login, akan redirect ke `/owner` dashboard

### Membuat Owner Baru
Jalankan di tinker atau seeder:
```php
\App\Models\User::create([
    'name' => 'Owner Name',
    'email' => 'owner@example.com',
    'phone' => '08123456789',
    'address' => 'Address',
    'role' => 'owner',
    'is_approved' => true,
    'approved_at' => now(),
    'api_token' => \Illuminate\Support\Str::random(60),
    'password' => \Illuminate\Support\Facades\Hash::make('password'),
]);
```

## File Changes Summary

### Modified Files:
1. [`app/Models/User.php`](app/Models/User.php) - Added `isOwner()` method
2. [`database/seeders/UserSeeder.php`](database/seeders/UserSeeder.php) - Added owner user seed
3. [`routes/web.php`](routes/web.php) - Added owner routes
4. [`app/Http/Controllers/AuthController.php`](app/Http/Controllers/AuthController.php) - Updated login redirect logic
5. [`app/Services/NotificationService.php`](app/Services/NotificationService.php) - Updated to notify owners

### New Files:
1. [`app/Http/Controllers/Owner/DashboardController.php`](app/Http/Controllers/Owner/DashboardController.php)
2. [`resources/views/layouts/owner.blade.php`](resources/views/layouts/owner.blade.php)
3. [`resources/views/owner/dashboard/index.blade.php`](resources/views/owner/dashboard/index.blade.php)

## Notes
- Owner memiliki akses penuh ke semua fitur admin
- Owner dan Admin dapat bekerja secara bersamaan
- Notifikasi akan dikirim ke kedua role (owner & admin)
- UI owner lebih premium dengan gradient dan accent color berbeda
