# Generator Template System

## Konsep umum

Sistem ini memisahkan:

1. metadata template generator di database `generator_templates`
2. logic normalisasi dan render dokumen di service
3. tampilan desain dokumen di Blade template

Dengan pola ini, generator baru tidak perlu menaruh semua variasi desain di controller.

## Generator yang sudah tersambung

Saat ini sistem template sudah aktif untuk:

1. `generator-invoice` dengan tipe template `invoice`
2. `generator-surat-izin` dengan tipe template `letter`

Template untuk `receipt` dan `minutes` sudah disiapkan agar generator berikutnya tinggal disambungkan.

## Struktur Blade

### Form public generator

- `resources/views/tools/generators/invoice.blade.php`
- `resources/views/tools/generators/letter.blade.php`
- `resources/views/tools/generators/receipt.blade.php`
- `resources/views/tools/generators/minutes.blade.php`

### Preview wrapper per tipe

- `resources/views/generators/invoice/preview.blade.php`
- `resources/views/generators/letter/preview.blade.php`
- `resources/views/generators/receipt/preview.blade.php`
- `resources/views/generators/minutes/preview.blade.php`

### Template desain dokumen

- `resources/views/generators/invoice/templates/*`
- `resources/views/generators/letter/templates/*`
- `resources/views/generators/receipt/templates/*`
- `resources/views/generators/minutes/templates/*`

### Shared partials

- `resources/views/generators/shared/partials/document-header.blade.php`
- `resources/views/generators/shared/partials/document-footer.blade.php`
- `resources/views/generators/shared/partials/signature-block.blade.php`

## Cara kerja preview

1. User memilih template.
2. User mengisi form.
3. Request divalidasi.
4. `DocumentGeneratorService` memilih template aktif lewat `GeneratorTemplateService`.
5. Payload dinormalisasi sesuai tipe generator.
6. Preview dirender dari Blade dan dikirim kembali ke halaman tool yang sama.
7. Input user tidak disimpan ke database.

## Cara kerja download PDF

1. User submit ke route download.
2. Payload divalidasi ulang.
3. Service memilih template aktif.
4. Dokumen dirender ke Blade PDF.
5. `barryvdh/laravel-dompdf` membuat file PDF.

Jika PDF bermasalah di hosting, user tetap bisa memakai print view browser.

## Cara kerja print view

1. User submit ke route print.
2. Sistem merender halaman minimal tanpa header website, footer website, dan iklan.
3. Browser membuka halaman print-friendly.
4. User bisa print langsung atau Save as PDF.

## Menambah template desain baru

1. Buat file Blade baru di folder template yang sesuai.
   Contoh:
   `resources/views/generators/invoice/templates/soft-blue.blade.php`
2. Tambahkan record baru di Filament `Generator Templates`.
3. Isi:
   - `name`
   - `slug`
   - `generator_type`
   - `view_path`
   - `paper_size`
   - `orientation`
   - `sort_order`
4. Pastikan `view_path` cocok dengan file Blade.
5. Pastikan template aktif.

## Menambah generator baru

1. Tambahkan tool baru di tabel `tools` atau seeder.
2. Tentukan mapping slug tool ke `generator_type` di `DocumentGeneratorService::generatorTypeForTool()`.
3. Buat request validation baru jika payload-nya berbeda.
4. Tambahkan partial form baru di `resources/views/tools/generators/`.
5. Tambahkan preview wrapper dan template desain Blade.
6. Tambahkan route preview, download, dan print.

## Privacy rule

1. Jangan simpan isi invoice, surat, kwitansi, atau berita acara ke database.
2. Jangan simpan nama pelanggan, nomor invoice, alamat, atau isi surat ke log.
3. Hanya metadata template dan konten tool yang boleh tersimpan permanen.

## Troubleshooting DOMPDF di cPanel

1. Gunakan CSS inline atau CSS sederhana.
2. Hindari Tailwind kompleks di template PDF.
3. Gunakan font aman seperti `DejaVu Sans` atau font standar.
4. Jika layout rusak, cek template lewat print view lebih dulu.
5. Jika gambar tidak muncul, pastikan file benar-benar ada di `public/uploads`.

## Checklist sebelum deploy

1. Jalankan migration baru.
2. Jalankan `GeneratorTemplateSeeder`.
3. Pastikan `public/uploads` writable.
4. Pastikan template baru tampil di Filament.
5. Cek preview invoice.
6. Cek download PDF invoice.
7. Cek preview surat izin.
8. Cek download PDF surat izin.
9. Cek print view.
10. Pastikan tidak ada data personal tersimpan di database.
