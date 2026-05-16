# Deployment ke cPanel / Shared Hosting

## Requirement
- PHP 8.3 atau lebih baru.
- MySQL 8+ atau MariaDB kompatibel.
- Composer tersedia dari terminal hosting atau build dari lokal.
- Extension PHP minimum:
  - `bcmath`
  - `ctype`
  - `curl`
  - `dom`
  - `fileinfo`
  - `filter`
  - `gd` atau `imagick` jika nanti pakai image processing
  - `intl`
  - `json`
  - `mbstring`
  - `openssl`
  - `pdo`
  - `pdo_mysql`
  - `session`
  - `tokenizer`
  - `xml`
  - `zip`

## Langkah Upload
1. Upload source project ke folder di luar `public_html` bila memungkinkan.
2. Jalankan `composer install --no-dev --optimize-autoloader`.
3. Jalankan `npm install` lalu `npm run build` di lokal atau server, lalu pastikan folder `public/build` ikut ter-upload.
4. Buat database MySQL dari cPanel.
5. Salin `.env.example` menjadi `.env` lalu isi kredensial database, app key, app URL, mail, dan setting lain.

## Konfigurasi `.env`
- `APP_NAME="BantuKerja.online"`
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://bantukerja.online`
- `DB_CONNECTION=mysql`
- `DB_HOST=localhost`
- `DB_PORT=3306`
- `DB_DATABASE=...`
- `DB_USERNAME=...`
- `DB_PASSWORD=...`

## Migrasi dan Seeder
- Jalankan:
```bash
php artisan migrate --force
php artisan db:seed --force
```

## Document Root
- Opsi terbaik: arahkan document root domain/subdomain ke folder `public`.
- Jika cPanel hanya mendukung `public_html`:
  - simpan seluruh source Laravel di luar `public_html`
  - copy isi folder `public/` ke `public_html`
  - sesuaikan `index.php` agar menunjuk ke folder aplikasi yang benar
  - jangan pernah memindahkan `.env`, `app`, `storage`, atau `vendor` ke area publik

## Optimasi Production
```bash
php artisan key:generate
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Jika Ada Perubahan
```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Backup
- Backup database rutin dari cPanel.
- Backup file aplikasi terutama `.env`, `storage/app`, dan `public/build`.

## Checklist Sebelum Launch
- `APP_DEBUG=false`
- `APP_ENV=production`
- URL domain sudah benar
- `.env` tidak bisa diakses publik
- halaman `sitemap.xml` dan `robots.txt` aktif
- admin login default sudah diganti passwordnya
- slot AdSense sudah diisi jika siap monetisasi
