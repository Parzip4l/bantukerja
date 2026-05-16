<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\DocumentTemplate;
use App\Models\Faq;
use Illuminate\Database\Seeder;

class DocumentTemplateSeeder extends Seeder
{
    protected function resolveCategoryId(string $slug): int
    {
        $category = Category::query()->where('slug', $slug)->first();

        if (! $category) {
            throw new \RuntimeException("Category with slug [{$slug}] was not found for document template seeding.");
        }

        return (int) $category->getKey();
    }

    public function run(): void
    {
        $templates = [
            [
                'title' => 'Surat Resign',
                'slug' => 'surat-resign',
                'category_slug' => 'surat-kerja',
                'short_description' => 'Template surat resign profesional yang sopan, jelas, dan siap diedit untuk kebutuhan pengunduran diri resmi.',
                'meta_title' => 'Template Surat Resign Profesional Siap Pakai',
                'meta_description' => 'Unduh template surat resign profesional yang lengkap, sopan, dan mudah disesuaikan untuk pengunduran diri kerja.',
                'content' => <<<'HTML'
<h2>Template Surat Resign Profesional</h2>
<p><strong>[Kota], [Tanggal Bulan Tahun]</strong></p>
<p>Kepada Yth.<br>[Nama Atasan / HRD]<br>[Jabatan]<br>[Nama Perusahaan]<br>Di tempat</p>
<p>Dengan hormat,</p>
<p>Melalui surat ini, saya <strong>[Nama Lengkap]</strong>, yang saat ini menjabat sebagai <strong>[Jabatan]</strong> di <strong>[Nama Perusahaan]</strong>, bermaksud untuk mengajukan pengunduran diri dari posisi saya. Rencana pengunduran diri ini akan efektif terhitung mulai <strong>[Tanggal Efektif Resign]</strong>, atau mengikuti ketentuan masa pemberitahuan yang berlaku di perusahaan.</p>
<p>Keputusan ini saya ambil setelah mempertimbangkan berbagai hal secara matang. Saya menyampaikan terima kasih atas kesempatan, kepercayaan, serta pengalaman kerja yang telah saya peroleh selama menjadi bagian dari perusahaan ini. Banyak pelajaran, relasi profesional, dan pengalaman berharga yang akan saya bawa ke langkah karier berikutnya.</p>
<p>Selama masa transisi, saya bersedia membantu proses serah terima pekerjaan agar berjalan dengan tertib. Saya juga siap menyusun catatan pekerjaan, menyerahkan dokumen yang diperlukan, dan berkoordinasi dengan atasan maupun rekan tim agar proses pergantian tanggung jawab dapat berlangsung dengan baik.</p>
<p>Besar harapan saya agar hubungan baik yang telah terjalin selama ini tetap terjaga. Saya memohon maaf apabila selama bekerja terdapat kesalahan, kekurangan, atau hal-hal yang kurang berkenan.</p>
<p>Demikian surat pengunduran diri ini saya sampaikan dengan sebenar-benarnya. Atas perhatian, dukungan, dan pengertiannya, saya ucapkan terima kasih.</p>
<p>Hormat saya,</p>
<p><br><br><strong>[Nama Lengkap]</strong><br>[Nomor Karyawan jika ada]</p>
<h3>Catatan penggunaan</h3>
<p>Ganti bagian dalam tanda kurung siku sesuai kondisi Anda. Jika ingin lebih ringkas, paragraf alasan pengunduran diri dapat dipersingkat tanpa mengubah inti surat.</p>
HTML,
            ],
            [
                'title' => 'Surat Kuasa',
                'slug' => 'surat-kuasa',
                'category_slug' => 'bisnis-legal-ringan',
                'short_description' => 'Template surat kuasa lengkap untuk pengurusan dokumen, pengambilan berkas, atau perwakilan administrasi umum.',
                'meta_title' => 'Template Surat Kuasa Lengkap Siap Edit',
                'meta_description' => 'Gunakan template surat kuasa lengkap dan jelas untuk kebutuhan administrasi, pengurusan dokumen, dan perwakilan resmi.',
                'content' => <<<'HTML'
<h2>Template Surat Kuasa</h2>
<p><strong>SURAT KUASA</strong></p>
<p>Yang bertanda tangan di bawah ini:</p>
<p><strong>Nama:</strong> [Nama Pemberi Kuasa]<br><strong>Tempat/Tanggal Lahir:</strong> [Tempat, Tanggal Lahir]<br><strong>Alamat:</strong> [Alamat Lengkap]<br><strong>No. Identitas:</strong> [KTP/SIM/Paspor]</p>
<p>Selanjutnya disebut sebagai <strong>Pemberi Kuasa</strong>.</p>
<p>Dengan ini memberikan kuasa kepada:</p>
<p><strong>Nama:</strong> [Nama Penerima Kuasa]<br><strong>Tempat/Tanggal Lahir:</strong> [Tempat, Tanggal Lahir]<br><strong>Alamat:</strong> [Alamat Lengkap]<br><strong>No. Identitas:</strong> [KTP/SIM/Paspor]</p>
<p>Selanjutnya disebut sebagai <strong>Penerima Kuasa</strong>.</p>
<p>Untuk dan atas nama Pemberi Kuasa melaksanakan keperluan berikut:</p>
<p><strong>[Tulis secara jelas tujuan kuasa, misalnya: mengambil dokumen asli, mengurus berkas administrasi, menandatangani dokumen tertentu, atau mewakili proses pengajuan pada instansi terkait].</strong></p>
<p>Segala hal yang dilakukan oleh Penerima Kuasa dalam batas kuasa yang disebutkan di atas menjadi tanggung jawab Pemberi Kuasa sepenuhnya.</p>
<p>Surat kuasa ini dibuat dalam keadaan sadar, tanpa paksaan dari pihak mana pun, untuk dipergunakan sebagaimana mestinya.</p>
<p><strong>[Kota], [Tanggal Bulan Tahun]</strong></p>
<table style="width:100%; border-collapse:collapse;">
    <tr>
        <td style="width:50%; vertical-align:top;">
            <p>Pemberi Kuasa,</p>
            <p><br><br><strong>[Nama Pemberi Kuasa]</strong></p>
        </td>
        <td style="width:50%; vertical-align:top;">
            <p>Penerima Kuasa,</p>
            <p><br><br><strong>[Nama Penerima Kuasa]</strong></p>
        </td>
    </tr>
</table>
<h3>Catatan penggunaan</h3>
<p>Pastikan ruang lingkup kuasa ditulis spesifik. Jika diperlukan oleh instansi tujuan, tambahkan materai dan lampiran fotokopi identitas para pihak.</p>
HTML,
            ],
            [
                'title' => 'Berita Acara Serah Terima',
                'slug' => 'berita-acara',
                'category_slug' => 'bisnis-legal-ringan',
                'short_description' => 'Template berita acara serah terima lengkap untuk barang, dokumen, aset kantor, atau pekerjaan tertentu.',
                'meta_title' => 'Template Berita Acara Serah Terima Siap Pakai',
                'meta_description' => 'Template berita acara serah terima lengkap untuk barang, dokumen, dan aset dengan format yang rapi dan mudah diedit.',
                'content' => <<<'HTML'
<h2>Template Berita Acara Serah Terima</h2>
<p><strong>BERITA ACARA SERAH TERIMA</strong></p>
<p>Pada hari ini, <strong>[Hari]</strong>, tanggal <strong>[Tanggal Bulan Tahun]</strong>, bertempat di <strong>[Lokasi]</strong>, kami yang bertanda tangan di bawah ini:</p>
<p><strong>Pihak Pertama</strong><br>Nama: [Nama Penyerah]<br>Jabatan: [Jabatan]<br>Instansi/Perusahaan: [Nama Instansi/Perusahaan]</p>
<p><strong>Pihak Kedua</strong><br>Nama: [Nama Penerima]<br>Jabatan: [Jabatan]<br>Instansi/Perusahaan: [Nama Instansi/Perusahaan]</p>
<p>Dengan ini menyatakan bahwa Pihak Pertama telah menyerahkan kepada Pihak Kedua, dan Pihak Kedua telah menerima dari Pihak Pertama, berupa:</p>
<ul>
    <li>[Nama barang/dokumen/aset 1] sebanyak [jumlah] dalam kondisi [baik/lengkap/dll].</li>
    <li>[Nama barang/dokumen/aset 2] sebanyak [jumlah] dalam kondisi [baik/lengkap/dll].</li>
    <li>[Tambahkan rincian lain bila diperlukan].</li>
</ul>
<p>Serah terima ini dilakukan untuk keperluan <strong>[tujuan serah terima, misalnya operasional kantor, pergantian PIC, penyelesaian proyek, atau keperluan administrasi]</strong>.</p>
<p>Dengan ditandatanganinya berita acara ini, maka tanggung jawab atas barang, dokumen, atau aset tersebut beralih dari Pihak Pertama kepada Pihak Kedua terhitung sejak tanggal yang disebutkan di atas.</p>
<p>Demikian berita acara ini dibuat dengan sebenar-benarnya untuk dipergunakan sebagaimana mestinya.</p>
<table style="width:100%; border-collapse:collapse;">
    <tr>
        <td style="width:50%; vertical-align:top;">
            <p>Pihak Pertama,</p>
            <p><br><br><strong>[Nama Penyerah]</strong></p>
        </td>
        <td style="width:50%; vertical-align:top;">
            <p>Pihak Kedua,</p>
            <p><br><br><strong>[Nama Penerima]</strong></p>
        </td>
    </tr>
</table>
<h3>Catatan penggunaan</h3>
<p>Jika aset yang diserahkan cukup banyak, tambahkan tabel rincian lampiran agar dokumen lebih rapi dan mudah diverifikasi.</p>
HTML,
            ],
            [
                'title' => 'Surat Izin Kerja',
                'slug' => 'surat-izin-kerja',
                'category_slug' => 'surat-kerja',
                'short_description' => 'Template surat izin kerja yang lengkap, sopan, dan bisa dipakai untuk izin pribadi, kesehatan, atau urusan keluarga.',
                'meta_title' => 'Template Surat Izin Kerja Lengkap dan Sopan',
                'meta_description' => 'Template surat izin kerja yang rapi dan siap pakai untuk kebutuhan kantor, operasional, atau administrasi kepegawaian.',
                'content' => <<<'HTML'
<h2>Template Surat Izin Kerja</h2>
<p><strong>[Kota], [Tanggal Bulan Tahun]</strong></p>
<p>Kepada Yth.<br>[Nama Atasan / HRD]<br>[Jabatan]<br>[Nama Perusahaan]<br>Di tempat</p>
<p>Dengan hormat,</p>
<p>Saya yang bertanda tangan di bawah ini:</p>
<p><strong>Nama:</strong> [Nama Lengkap]<br><strong>Jabatan:</strong> [Jabatan]<br><strong>Divisi/Departemen:</strong> [Divisi]<br><strong>Nomor Karyawan:</strong> [Jika ada]</p>
<p>Dengan ini mengajukan izin untuk tidak masuk kerja pada tanggal <strong>[Tanggal Izin]</strong> karena <strong>[alasan izin, misalnya kondisi kesehatan, urusan keluarga, atau keperluan penting lain]</strong>.</p>
<p>Sebagai bentuk tanggung jawab, saya telah menginformasikan pekerjaan yang sedang berjalan kepada rekan atau atasan terkait, serta akan menindaklanjuti hal-hal yang diperlukan setelah kembali bekerja. Jika dibutuhkan, saya juga dapat dihubungi melalui nomor <strong>[Nomor Telepon]</strong>.</p>
<p>Demikian surat izin ini saya sampaikan. Saya berharap permohonan izin ini dapat dipertimbangkan dan disetujui. Atas perhatian dan pengertiannya, saya ucapkan terima kasih.</p>
<p>Hormat saya,</p>
<p><br><br><strong>[Nama Lengkap]</strong></p>
<h3>Catatan penggunaan</h3>
<p>Untuk izin sakit atau izin lebih dari satu hari, Anda bisa menambahkan lampiran pendukung seperti surat dokter atau bukti administrasi bila diperlukan perusahaan.</p>
HTML,
            ],
            [
                'title' => 'Surat Lamaran Kerja',
                'slug' => 'surat-lamaran-kerja',
                'category_slug' => 'karier-lamaran',
                'short_description' => 'Template surat lamaran kerja formal yang lengkap, sopan, dan cocok untuk berbagai posisi kerja profesional.',
                'meta_title' => 'Template Surat Lamaran Kerja Formal Siap Pakai',
                'meta_description' => 'Download template surat lamaran kerja formal yang lengkap dan mudah disesuaikan untuk berbagai lowongan pekerjaan.',
                'content' => <<<'HTML'
<h2>Template Surat Lamaran Kerja</h2>
<p><strong>[Kota], [Tanggal Bulan Tahun]</strong></p>
<p>Kepada Yth.<br>HRD / Tim Rekrutmen<br>[Nama Perusahaan]<br>Di tempat</p>
<p>Dengan hormat,</p>
<p>Berdasarkan informasi lowongan pekerjaan untuk posisi <strong>[Nama Posisi]</strong> yang saya peroleh dari <strong>[Sumber Informasi Lowongan]</strong>, melalui surat ini saya bermaksud mengajukan lamaran kerja untuk posisi tersebut.</p>
<p>Saya yang bertanda tangan di bawah ini:</p>
<p><strong>Nama:</strong> [Nama Lengkap]<br><strong>Tempat/Tanggal Lahir:</strong> [Tempat, Tanggal Lahir]<br><strong>Alamat:</strong> [Alamat Lengkap]<br><strong>No. Telepon:</strong> [Nomor Aktif]<br><strong>Email:</strong> [Alamat Email]</p>
<p>Saya memiliki latar belakang pendidikan <strong>[Pendidikan Terakhir]</strong> dan pengalaman di bidang <strong>[Bidang / Pengalaman Relevan]</strong>. Selama ini saya terbiasa mengerjakan tugas yang berkaitan dengan <strong>[sebutkan keterampilan atau tanggung jawab utama yang relevan]</strong>. Saya juga memiliki kemampuan <strong>[skill utama]</strong> yang saya yakini dapat memberikan kontribusi positif bagi perusahaan.</p>
<p>Sebagai bahan pertimbangan, bersama surat ini saya lampirkan beberapa dokumen pendukung, seperti daftar riwayat hidup, fotokopi identitas, ijazah, transkrip nilai, serta dokumen lain yang relevan.</p>
<p>Besar harapan saya untuk dapat diberikan kesempatan mengikuti tahapan seleksi lebih lanjut agar saya dapat menjelaskan potensi dan pengalaman saya secara lebih rinci. Demikian surat lamaran ini saya buat dengan sebenar-benarnya. Atas perhatian dan kesempatan yang diberikan, saya ucapkan terima kasih.</p>
<p>Hormat saya,</p>
<p><br><br><strong>[Nama Lengkap]</strong></p>
<h3>Lampiran umum</h3>
<ul>
    <li>Curriculum Vitae</li>
    <li>Fotokopi identitas</li>
    <li>Fotokopi ijazah dan transkrip</li>
    <li>Sertifikat pendukung bila ada</li>
</ul>
HTML,
            ],
            [
                'title' => 'CV Sederhana',
                'slug' => 'cv-lamaran-kerja',
                'category_slug' => 'karier-lamaran',
                'short_description' => 'Template CV sederhana yang lengkap, ATS-friendly, dan siap diisi untuk fresh graduate maupun profesional awal.',
                'meta_title' => 'Template CV Sederhana ATS Friendly Siap Edit',
                'meta_description' => 'Gunakan template CV sederhana yang rapi, mudah dibaca recruiter, dan siap disesuaikan untuk lamaran kerja.',
                'content' => <<<'HTML'
<h2>Template CV Sederhana</h2>
<p><strong>[NAMA LENGKAP]</strong><br>[Nomor Telepon] | [Email] | [Kota Domisili] | [LinkedIn / Portfolio]</p>
<h3>Profil Singkat</h3>
<p>[Tulis ringkasan profesional 3-5 kalimat tentang pengalaman, kekuatan utama, serta posisi atau bidang yang Anda minati. Fokus pada hal yang relevan dengan pekerjaan yang dilamar.]</p>
<h3>Pengalaman Kerja</h3>
<p><strong>[Nama Jabatan]</strong> - [Nama Perusahaan]<br>[Bulan Tahun Mulai] - [Bulan Tahun Selesai / Sekarang]</p>
<ul>
    <li>[Tanggung jawab atau pencapaian utama 1]</li>
    <li>[Tanggung jawab atau pencapaian utama 2]</li>
    <li>[Tanggung jawab atau pencapaian utama 3]</li>
</ul>
<p><strong>[Nama Jabatan]</strong> - [Nama Perusahaan]<br>[Bulan Tahun Mulai] - [Bulan Tahun Selesai]</p>
<ul>
    <li>[Tanggung jawab atau pencapaian utama 1]</li>
    <li>[Tanggung jawab atau pencapaian utama 2]</li>
</ul>
<h3>Pendidikan</h3>
<p><strong>[Nama Institusi]</strong> - [Jurusan / Program Studi]<br>[Tahun Masuk] - [Tahun Lulus]</p>
<p>[Prestasi, fokus studi, organisasi, atau catatan penting bila relevan]</p>
<h3>Keterampilan</h3>
<ul>
    <li>[Keterampilan teknis / software / tools yang dikuasai]</li>
    <li>[Keterampilan komunikasi / administrasi / analisis]</li>
    <li>[Bahasa asing atau kompetensi lain]</li>
</ul>
<h3>Sertifikasi atau Pelatihan</h3>
<p>[Nama Sertifikasi / Pelatihan] - [Penyelenggara] - [Tahun]</p>
<h3>Catatan penggunaan</h3>
<p>Pastikan isi CV disesuaikan dengan posisi yang dilamar. Hapus bagian yang tidak perlu dan prioritaskan pengalaman yang paling relevan agar CV tetap ringkas namun kuat.</p>
HTML,
            ],
            [
                'title' => 'Invoice Sederhana',
                'slug' => 'invoice-sederhana',
                'category_slug' => 'bisnis-legal-ringan',
                'short_description' => 'Template invoice sederhana yang lengkap untuk freelancer, UMKM, jasa harian, dan penagihan bisnis kecil.',
                'meta_title' => 'Template Invoice Sederhana Lengkap Siap Pakai',
                'meta_description' => 'Template invoice sederhana dengan data bisnis, pelanggan, item tagihan, subtotal, pajak, dan total pembayaran.',
                'content' => <<<'HTML'
<h2>Template Invoice Sederhana</h2>
<p><strong>INVOICE</strong></p>
<p><strong>Nama Bisnis:</strong> [Nama Bisnis]<br><strong>Alamat:</strong> [Alamat Bisnis]<br><strong>Telepon:</strong> [Nomor Telepon]<br><strong>Email:</strong> [Email Bisnis]</p>
<p><strong>No. Invoice:</strong> [Nomor Invoice]<br><strong>Tanggal Invoice:</strong> [Tanggal]<br><strong>Jatuh Tempo:</strong> [Tanggal Jatuh Tempo]</p>
<p><strong>Ditagihkan kepada:</strong><br>[Nama Pelanggan / Nama Perusahaan]<br>[Alamat Pelanggan]<br>[Nomor Telepon / Email]</p>
<table style="width:100%; border-collapse:collapse;" border="1" cellpadding="8">
    <tr>
        <th align="left">Nama Item / Layanan</th>
        <th align="left">Deskripsi</th>
        <th align="left">Qty</th>
        <th align="left">Harga Satuan</th>
        <th align="left">Subtotal</th>
    </tr>
    <tr>
        <td>[Item 1]</td>
        <td>[Deskripsi singkat]</td>
        <td>[Jumlah]</td>
        <td>[Harga]</td>
        <td>[Subtotal]</td>
    </tr>
    <tr>
        <td>[Item 2]</td>
        <td>[Deskripsi singkat]</td>
        <td>[Jumlah]</td>
        <td>[Harga]</td>
        <td>[Subtotal]</td>
    </tr>
</table>
<p><strong>Subtotal:</strong> [Nominal]<br><strong>Pajak:</strong> [Nominal / Persentase]<br><strong>Diskon:</strong> [Nominal / Persentase]<br><strong>Total Tagihan:</strong> [Grand Total]</p>
<p><strong>Metode Pembayaran:</strong><br>Bank: [Nama Bank]<br>Nomor Rekening: [Nomor Rekening]<br>Atas Nama: [Nama Pemilik Rekening]</p>
<p><strong>Catatan:</strong><br>[Tulis catatan pembayaran, syarat termin, atau informasi tambahan lain bila diperlukan.]</p>
<p>Terima kasih atas kerja sama dan kepercayaannya.</p>
HTML,
            ],
            [
                'title' => 'MoU Sederhana',
                'slug' => 'mou-sederhana',
                'category_slug' => 'bisnis-legal-ringan',
                'short_description' => 'Template MoU sederhana yang lengkap untuk kerja sama awal antarindividu, usaha kecil, atau mitra proyek.',
                'meta_title' => 'Template MoU Sederhana untuk Kerja Sama Awal',
                'meta_description' => 'Template MoU sederhana yang jelas dan siap diedit untuk kebutuhan kerja sama bisnis, proyek, atau kolaborasi awal.',
                'content' => <<<'HTML'
<h2>Template MoU Sederhana</h2>
<p><strong>MEMORANDUM OF UNDERSTANDING (MoU)</strong></p>
<p>Pada hari ini, <strong>[Hari]</strong> tanggal <strong>[Tanggal Bulan Tahun]</strong>, kami yang bertanda tangan di bawah ini:</p>
<p><strong>Pihak Pertama</strong><br>Nama: [Nama Pihak Pertama]<br>Jabatan: [Jabatan]<br>Alamat / Nama Usaha: [Alamat / Nama Usaha]</p>
<p><strong>Pihak Kedua</strong><br>Nama: [Nama Pihak Kedua]<br>Jabatan: [Jabatan]<br>Alamat / Nama Usaha: [Alamat / Nama Usaha]</p>
<p>Kedua belah pihak sepakat untuk menjalin kerja sama dalam ruang lingkup <strong>[jenis kerja sama]</strong> dengan ketentuan sebagai berikut:</p>
<h3>Pasal 1 - Ruang Lingkup Kerja Sama</h3>
<p>Pihak Pertama dan Pihak Kedua sepakat untuk bekerja sama dalam kegiatan <strong>[jelaskan ruang lingkup kerja sama secara ringkas dan jelas]</strong>.</p>
<h3>Pasal 2 - Hak dan Kewajiban</h3>
<p>Pihak Pertama berkewajiban untuk <strong>[kewajiban pihak pertama]</strong> dan berhak atas <strong>[hak pihak pertama]</strong>.</p>
<p>Pihak Kedua berkewajiban untuk <strong>[kewajiban pihak kedua]</strong> dan berhak atas <strong>[hak pihak kedua]</strong>.</p>
<h3>Pasal 3 - Jangka Waktu</h3>
<p>Kesepahaman ini berlaku sejak tanggal ditandatangani sampai dengan <strong>[tanggal berakhir / jangka waktu]</strong>, dan dapat diperpanjang berdasarkan kesepakatan kedua belah pihak.</p>
<h3>Pasal 4 - Kerahasiaan dan Penyelesaian Perselisihan</h3>
<p>Kedua belah pihak sepakat menjaga kerahasiaan informasi yang diperoleh selama kerja sama berlangsung. Apabila terjadi perselisihan, penyelesaiannya akan dilakukan terlebih dahulu secara musyawarah untuk mufakat.</p>
<h3>Pasal 5 - Penutup</h3>
<p>Dokumen ini dibuat sebagai dasar kesepahaman awal dan dapat ditindaklanjuti dalam bentuk perjanjian kerja sama yang lebih rinci apabila diperlukan.</p>
<p>Demikian MoU ini dibuat dan ditandatangani dalam keadaan sadar, tanpa paksaan dari pihak mana pun.</p>
<table style="width:100%; border-collapse:collapse;">
    <tr>
        <td style="width:50%; vertical-align:top;">
            <p>Pihak Pertama,</p>
            <p><br><br><strong>[Nama Pihak Pertama]</strong></p>
        </td>
        <td style="width:50%; vertical-align:top;">
            <p>Pihak Kedua,</p>
            <p><br><br><strong>[Nama Pihak Kedua]</strong></p>
        </td>
    </tr>
</table>
HTML,
            ],
            [
                'title' => 'Surat Pernyataan',
                'slug' => 'surat-pernyataan',
                'category_slug' => 'bisnis-legal-ringan',
                'short_description' => 'Template surat pernyataan umum yang lengkap dan fleksibel untuk kebutuhan administrasi personal maupun bisnis ringan.',
                'meta_title' => 'Template Surat Pernyataan Umum Siap Pakai',
                'meta_description' => 'Template surat pernyataan umum yang lengkap, jelas, dan mudah disesuaikan untuk berbagai kebutuhan administrasi.',
                'content' => <<<'HTML'
<h2>Template Surat Pernyataan</h2>
<p><strong>SURAT PERNYATAAN</strong></p>
<p>Saya yang bertanda tangan di bawah ini:</p>
<p><strong>Nama:</strong> [Nama Lengkap]<br><strong>Tempat/Tanggal Lahir:</strong> [Tempat, Tanggal Lahir]<br><strong>Alamat:</strong> [Alamat Lengkap]<br><strong>No. Identitas:</strong> [KTP/SIM/Paspor]</p>
<p>Dengan ini menyatakan dengan sebenar-benarnya bahwa:</p>
<p><strong>[Tuliskan isi pernyataan secara jelas dan spesifik. Contoh: saya belum pernah menerima bantuan sejenis, saya bertanggung jawab atas dokumen yang diserahkan, saya bersedia memenuhi ketentuan tertentu, dan lain-lain.]</strong></p>
<p>Apabila di kemudian hari pernyataan ini terbukti tidak benar, maka saya bersedia menerima konsekuensi sesuai ketentuan yang berlaku.</p>
<p>Demikian surat pernyataan ini saya buat dalam keadaan sadar, tanpa paksaan dari pihak mana pun, untuk dipergunakan sebagaimana mestinya.</p>
<p><strong>[Kota], [Tanggal Bulan Tahun]</strong></p>
<p>Yang membuat pernyataan,</p>
<p><br><br><strong>[Nama Lengkap]</strong></p>
<h3>Catatan penggunaan</h3>
<p>Surat pernyataan akan lebih kuat jika isi pernyataannya spesifik, bukan terlalu umum. Tambahkan materai jika dibutuhkan untuk keperluan resmi tertentu.</p>
HTML,
            ],
            [
                'title' => 'Surat Permohonan',
                'slug' => 'surat-permohonan',
                'category_slug' => 'surat-kerja',
                'short_description' => 'Template surat permohonan resmi yang lengkap untuk kebutuhan kerja, administrasi, instansi, atau pengajuan formal.',
                'meta_title' => 'Template Surat Permohonan Resmi Siap Edit',
                'meta_description' => 'Template surat permohonan resmi dengan format lengkap, sopan, dan mudah disesuaikan untuk berbagai kebutuhan administrasi.',
                'content' => <<<'HTML'
<h2>Template Surat Permohonan Resmi</h2>
<p><strong>[Kota], [Tanggal Bulan Tahun]</strong></p>
<p>Nomor: [Nomor Surat jika ada]<br>Lampiran: [Jumlah Lampiran]<br>Perihal: Permohonan [Isi Singkat Perihal]</p>
<p>Kepada Yth.<br>[Nama Penerima / Jabatan]<br>[Instansi / Perusahaan]<br>Di tempat</p>
<p>Dengan hormat,</p>
<p>Sehubungan dengan <strong>[latar belakang atau kebutuhan permohonan]</strong>, melalui surat ini kami bermaksud mengajukan permohonan <strong>[jenis permohonan secara jelas]</strong>.</p>
<p>Adapun maksud dari permohonan ini adalah untuk <strong>[tujuan permohonan]</strong>. Sebagai bahan pertimbangan, bersama surat ini kami lampirkan dokumen pendukung berupa <strong>[sebutkan lampiran]</strong>.</p>
<p>Kami berharap permohonan ini dapat dipertimbangkan dan memperoleh persetujuan. Besar harapan kami agar proses tindak lanjut dapat dilakukan sesuai ketentuan yang berlaku.</p>
<p>Demikian surat permohonan ini kami sampaikan. Atas perhatian, bantuan, dan kerja sama yang baik, kami ucapkan terima kasih.</p>
<p>Hormat kami,</p>
<p><br><br><strong>[Nama Pengirim]</strong><br>[Jabatan]<br>[Nama Instansi / Perusahaan]</p>
<h3>Catatan penggunaan</h3>
<p>Surat permohonan biasanya lebih baik jika dilengkapi nomor surat, lampiran, dan penjelasan tujuan yang singkat namun spesifik.</p>
HTML,
            ],
        ];

        foreach ($templates as $index => $templateData) {
            $categorySlug = $templateData['category_slug'];
            unset($templateData['category_slug']);

            $template = DocumentTemplate::updateOrCreate(
                ['slug' => $templateData['slug']],
                array_merge($templateData, [
                    'category_id' => $this->resolveCategoryId($categorySlug),
                    'is_featured' => $index < 6,
                    'is_published' => true,
                    'published_at' => now()->subDays(20 - $index),
                ]),
            );

            Faq::updateOrCreate(
                [
                    'faqable_type' => DocumentTemplate::class,
                    'faqable_id' => $template->id,
                    'question' => 'Apakah template ini bisa langsung dipakai untuk kebutuhan nyata?',
                ],
                [
                    'answer' => 'Bisa. Template ini sudah ditulis dalam format lengkap sebagai draft siap pakai, tetapi tetap sebaiknya Anda sesuaikan dengan nama pihak, tanggal, tujuan, dan konteks penggunaan yang sebenarnya.',
                    'sort_order' => 1,
                    'is_active' => true,
                ],
            );

            Faq::updateOrCreate(
                [
                    'faqable_type' => DocumentTemplate::class,
                    'faqable_id' => $template->id,
                    'question' => 'Apakah template ini boleh diedit sebelum dikirim atau dicetak?',
                ],
                [
                    'answer' => 'Ya. Justru template ini dibuat agar mudah diedit. Anda bisa menyesuaikan isi, menambah detail, atau menghapus bagian yang tidak relevan sebelum dipakai.',
                    'sort_order' => 2,
                    'is_active' => true,
                ],
            );
        }
    }
}
