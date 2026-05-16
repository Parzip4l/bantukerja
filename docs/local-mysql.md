# Setup Lokal MySQL

## Tujuan
- Menyamakan environment lokal dengan production agar perilaku query, encoding, dan relasi lebih konsisten.

## Langkah Cepat
1. Buat database MySQL lokal bernama `bantukerja`.
2. Pastikan user lokal Anda punya akses ke database tersebut.
3. Salin `.env.example` menjadi `.env`.
4. Sesuaikan kredensial berikut:
   - `DB_CONNECTION=mysql`
   - `DB_HOST=127.0.0.1`
   - `DB_PORT=3306`
   - `DB_DATABASE=bantukerja`
   - `DB_USERNAME=root`
   - `DB_PASSWORD=...`
5. Jalankan:

```bash
php artisan key:generate
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

## Jika Ingin Tetap Memakai SQLite
- Salin `.env.sqlite.example` menjadi `.env`.
- Buat file `database/database.sqlite` jika belum ada.
- Jalankan migration dan seeder seperti biasa.

## Catatan
- Untuk shared hosting tertentu, `DB_CONNECTION=mariadb` bisa lebih stabil daripada `mysql`.
- Gunakan `127.0.0.1` untuk memaksa koneksi TCP bila `localhost` bermasalah.
