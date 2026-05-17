<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Faq;
use App\Models\Tool;
use Illuminate\Database\Seeder;

class ToolSeeder extends Seeder
{
    protected function resolveCategoryId(string $slug): int
    {
        $category = Category::query()->where('slug', $slug)->first();

        if (! $category) {
            throw new \RuntimeException("Category with slug [{$slug}] was not found for tool seeding.");
        }

        return (int) $category->getKey();
    }

    public function run(): void
    {
        $tools = [
            [
                'category_slug' => 'kalkulator-kerja',
                'title' => 'Kalkulator THR',
                'slug' => 'kalkulator-thr',
                'short_description' => 'Hitung estimasi THR karyawan berdasarkan gaji bulanan dan masa kerja secara cepat.',
                'tool_type' => 'calculator',
                'meta_title' => 'Kalkulator THR Gratis Online',
                'meta_description' => 'Hitung THR karyawan secara cepat dengan formula sederhana sesuai masa kerja.',
                'body' => '<h2>Cara menggunakan kalkulator THR</h2><p>Masukkan gaji bulanan dan lama bekerja dalam bulan. Sistem akan menghitung estimasi THR penuh atau proporsional sesuai input Anda.</p><h2>Catatan penting</h2><p>Hasil perhitungan ini adalah estimasi praktis untuk edukasi. Kebijakan final tetap mengikuti aturan perusahaan dan regulasi yang berlaku.</p>',
                'faqs' => [
                    ['question' => 'Apakah hasil kalkulator ini resmi?', 'answer' => 'Tidak. Hasilnya adalah estimasi praktis untuk membantu memahami perhitungan dasar THR.'],
                    ['question' => 'Bagaimana jika masa kerja belum 12 bulan?', 'answer' => 'THR dihitung proporsional: masa kerja dibagi 12 lalu dikalikan gaji bulanan.'],
                ],
            ],
            [
                'category_slug' => 'kalkulator-kerja',
                'title' => 'Kalkulator Gaji Bersih',
                'slug' => 'kalkulator-gaji-bersih',
                'short_description' => 'Hitung estimasi gaji bersih bulanan dari gaji pokok, tunjangan, dan potongan.',
                'tool_type' => 'calculator',
                'meta_title' => 'Kalkulator Gaji Bersih Online',
                'meta_description' => 'Hitung estimasi gaji bersih dari gaji pokok, tunjangan, BPJS, pajak, dan potongan lain.',
                'body' => '<h2>Apa yang dihitung?</h2><p>Tool ini menghitung total pendapatan, total potongan, lalu menghasilkan estimasi take home pay.</p><h2>Kapan tool ini berguna?</h2><p>Cocok dipakai saat menilai penawaran kerja, membandingkan komponen payroll, atau memeriksa simulasi gaji bulanan.</p>',
                'faqs' => [
                    ['question' => 'Apakah tool ini menghitung pajak secara detail?', 'answer' => 'Belum. Tool ini menerima input pajak sebagai nilai manual agar tetap ringan dan cepat.'],
                ],
            ],
            [
                'category_slug' => 'kalkulator-kerja',
                'title' => 'Kalkulator Lembur Sederhana',
                'slug' => 'kalkulator-lembur',
                'short_description' => 'Estimasi upah lembur berdasarkan upah bulanan, jumlah jam lembur, dan tipe hari.',
                'tool_type' => 'calculator',
                'meta_title' => 'Kalkulator Lembur Sederhana',
                'meta_description' => 'Hitung estimasi upah lembur sederhana untuk hari kerja atau hari libur.',
                'body' => '<h2>Untuk siapa tool ini dibuat?</h2><p>Tool ini cocok untuk karyawan yang ingin punya gambaran cepat tentang estimasi lembur sebelum mengecek slip gaji resmi.</p><h2>Rumus yang dipakai</h2><p>Perhitungan berbasis estimasi upah per jam dari upah bulanan dibagi 173, lalu dikalikan jam lembur dan multiplier sederhana.</p>',
                'faqs' => [
                    ['question' => 'Apakah rumusnya sama untuk semua perusahaan?', 'answer' => 'Tidak selalu. Setiap perusahaan bisa punya komponen dan aturan lembur yang berbeda.'],
                ],
            ],
            [
                'category_slug' => 'kalkulator-kerja',
                'title' => 'Kalkulator Fee Konsultan Pajak',
                'slug' => 'kalkulator-fee-konsultan-pajak',
                'short_description' => 'Hitung estimasi fee jasa konsultan pajak dari transaksi, revenue, atau nilai terbesar sesuai pendekatan yang Anda pilih.',
                'tool_type' => 'calculator',
                'meta_title' => 'Kalkulator Fee Konsultan Pajak',
                'meta_description' => 'Hitung estimasi fee jasa konsultan pajak 5% dari transaksi, revenue, atau nilai terbesar secara cepat.',
                'body' => '<h2>Bagaimana fee dihitung?</h2><p>Dalam praktik jasa konsultan pajak, fee sering ditentukan dari nilai transaksi atau revenue perusahaan. Tool ini membantu menghitung estimasi 5% dari dasar hitung yang Anda pilih.</p><h2>Kapan tool ini berguna?</h2><p>Cocok dipakai saat menyusun proposal penawaran, memberi estimasi awal ke klien, atau membandingkan beberapa pendekatan fee sebelum negosiasi.</p>',
                'faqs' => [
                    ['question' => 'Apakah fee selalu 5%?', 'answer' => 'Tidak selalu. Tool ini mengikuti patokan awal 5% sesuai brief yang Anda berikan, tetapi angka final tetap bisa dinegosiasikan.'],
                    ['question' => 'Bisa pilih dasar transaksi atau revenue?', 'answer' => 'Bisa. Anda dapat memilih dasar hitung dari transaksi, revenue, atau nilai terbesar di antara keduanya.'],
                ],
            ],
            [
                'category_slug' => 'kalkulator-kerja',
                'title' => 'Kalkulator Fee Accounting Service',
                'slug' => 'kalkulator-fee-accounting-service',
                'short_description' => 'Hitung estimasi fee accounting service dari transaksi, revenue, atau nilai terbesar dengan pendekatan 5%.',
                'tool_type' => 'calculator',
                'meta_title' => 'Kalkulator Fee Accounting Service',
                'meta_description' => 'Hitung fee accounting service 5% dari transaksi, revenue, atau nilai terbesar secara praktis.',
                'body' => '<h2>Untuk apa tool ini dibuat?</h2><p>Accounting service sering memakai dasar nilai transaksi atau revenue untuk membuat estimasi fee. Tool ini mempermudah simulasi awal agar diskusi dengan calon klien lebih cepat.</p><h2>Apa yang ditampilkan?</h2><p>Anda akan melihat dasar hitung yang dipakai, nilai acuannya, dan estimasi fee 5% yang dihasilkan dari basis tersebut.</p>',
                'faqs' => [
                    ['question' => 'Apakah tool ini hanya untuk accounting service bulanan?', 'answer' => 'Tidak. Tool ini bisa dipakai untuk simulasi fee awal, baik untuk layanan bulanan, proyek perapihan, maupun kebutuhan pembukuan tertentu.'],
                ],
            ],
            [
                'category_slug' => 'kalkulator-kerja',
                'title' => 'Kalkulator Fee Audit',
                'slug' => 'kalkulator-fee-audit',
                'short_description' => 'Hitung estimasi fee audit 5% dari nilai yang lebih besar antara total aset dan revenue perusahaan.',
                'tool_type' => 'calculator',
                'meta_title' => 'Kalkulator Fee Audit',
                'meta_description' => 'Hitung estimasi fee audit berdasarkan total aset atau revenue perusahaan, lalu ambil nilai yang lebih besar.',
                'body' => '<h2>Prinsip perhitungan audit</h2><p>Sesuai brief Anda, fee audit diambil dari nilai yang lebih besar antara total aset dan revenue perusahaan. Dari angka itu, tool akan menarik estimasi 5%.</p><h2>Catatan penggunaan</h2><p>Hasil tool ini cocok untuk simulasi awal. Dalam praktik audit nyata, kompleksitas dokumen, jumlah entitas, dan ruang lingkup pemeriksaan tetap dapat memengaruhi fee final.</p>',
                'faqs' => [
                    ['question' => 'Kenapa membandingkan aset dan revenue?', 'answer' => 'Karena pada pendekatan ini, nilai yang lebih besar dianggap lebih representatif sebagai dasar hitung fee audit awal.'],
                ],
            ],
            [
                'category_slug' => 'generator-dokumen',
                'title' => 'Generator Invoice',
                'slug' => 'generator-invoice',
                'short_description' => 'Buat invoice sederhana lengkap dengan preview total, tombol print, dan download PDF.',
                'tool_type' => 'generator',
                'meta_title' => 'Generator Invoice Gratis',
                'meta_description' => 'Buat invoice sederhana online tanpa login, lengkap dengan preview dan PDF.',
                'body' => '<h2>Kenapa invoice generator ini praktis?</h2><p>Anda bisa membuat invoice tanpa login dan tanpa menyimpan data sensitif di server. Form cukup diisi sekali lalu langsung preview dan unduh.</p><h2>Tips penggunaan</h2><p>Gunakan nomor invoice yang konsisten, isi item secara jelas, dan tambahkan pajak atau diskon bila diperlukan.</p>',
                'faqs' => [
                    ['question' => 'Apakah data invoice disimpan?', 'answer' => 'Tidak. Data invoice hanya diproses untuk preview dan file hasil unduhan pada sesi aktif.'],
                ],
            ],
            [
                'category_slug' => 'generator-dokumen',
                'title' => 'Generator Surat Izin Kerja',
                'slug' => 'generator-surat-izin',
                'short_description' => 'Buat surat izin kerja sederhana yang siap disalin atau diunduh dalam format teks.',
                'tool_type' => 'generator',
                'meta_title' => 'Generator Surat Izin Kerja',
                'meta_description' => 'Buat surat izin kerja cepat dan rapi untuk kebutuhan kantor atau administrasi harian.',
                'body' => '<h2>Kapan surat izin ini digunakan?</h2><p>Surat izin kerja membantu menyampaikan ketidakhadiran atau kebutuhan izin tertentu secara lebih profesional dan terdokumentasi.</p><h2>Apa yang dihasilkan?</h2><p>Tool menghasilkan teks surat yang bisa langsung disalin, disesuaikan, atau diunduh dalam format TXT.</p>',
                'faqs' => [
                    ['question' => 'Apakah format surat bisa diedit?', 'answer' => 'Bisa. Anda dapat menyalin hasil surat lalu menyesuaikannya dengan kebutuhan perusahaan.'],
                ],
            ],
            [
                'category_slug' => 'generator-dokumen',
                'title' => 'Generator Kwitansi',
                'slug' => 'generator-kwitansi',
                'short_description' => 'Buat kwitansi pembayaran rapi dengan pilihan template desain, preview, print, dan download PDF.',
                'tool_type' => 'generator',
                'meta_title' => 'Generator Kwitansi Gratis Online',
                'meta_description' => 'Buat kwitansi pembayaran online dengan template desain, preview dokumen, print, dan download PDF.',
                'body' => '<h2>Apa yang bisa dibuat?</h2><p>Generator kwitansi ini membantu Anda membuat bukti pembayaran sederhana namun tetap rapi. Anda bisa memilih template, mengisi data penerima dan nominal, lalu langsung melihat preview sebelum diunduh.</p><h2>Kapan tool ini berguna?</h2><p>Cocok untuk UMKM, freelancer, jasa harian, dan kebutuhan administrasi pembayaran ringan yang ingin terlihat lebih profesional tanpa aplikasi rumit.</p>',
                'faqs' => [
                    ['question' => 'Apakah data pembayaran saya disimpan?', 'answer' => 'Tidak. Data hanya dipakai untuk preview dan file hasil unduhan pada sesi aktif.'],
                    ['question' => 'Apakah kwitansi bisa dicetak langsung?', 'answer' => 'Bisa. Selain download PDF, Anda juga bisa membuka print view untuk langsung menyimpan atau mencetak dokumen.'],
                ],
            ],
            [
                'category_slug' => 'generator-dokumen',
                'title' => 'Generator Berita Acara',
                'slug' => 'generator-berita-acara',
                'short_description' => 'Buat berita acara formal dengan pilihan template, preview, print, dan download PDF.',
                'tool_type' => 'generator',
                'meta_title' => 'Generator Berita Acara Gratis',
                'meta_description' => 'Buat berita acara online dengan format rapi, preview dokumen, print view, dan download PDF.',
                'body' => '<h2>Berita acara untuk kebutuhan apa?</h2><p>Tool ini cocok untuk berita acara serah terima, rapat, pemeriksaan, atau dokumentasi administratif lain yang memerlukan format resmi dan mudah dicetak.</p><h2>Kenapa pakai generator?</h2><p>Anda tidak perlu menyusun struktur dokumen dari awal. Cukup pilih template, isi detail acara, lalu cek preview sebelum mengunduh hasil akhirnya.</p>',
                'faqs' => [
                    ['question' => 'Apakah format berita acara bisa dipakai untuk serah terima?', 'answer' => 'Bisa. Struktur yang disiapkan cocok untuk banyak kebutuhan administratif, termasuk serah terima dan dokumentasi rapat sederhana.'],
                    ['question' => 'Bisa pilih desain yang berbeda?', 'answer' => 'Bisa. Anda dapat memilih template formal atau professional sesuai kebutuhan tampilan dokumen.'],
                ],
            ],
            [
                'category_slug' => 'generator-dokumen',
                'title' => 'Generator CV ATS',
                'slug' => 'generator-cv-ats',
                'short_description' => 'Buat CV ATS-friendly yang detail, rapi, dan siap diunduh dalam format PDF atau Word.',
                'tool_type' => 'generator',
                'meta_title' => 'Generator CV ATS Gratis',
                'meta_description' => 'Buat CV ATS-friendly lengkap dengan pengalaman kerja, pendidikan, skill, dan ekspor PDF atau Word.',
                'body' => '<h2>Kenapa CV ATS penting?</h2><p>CV ATS-friendly membantu informasi inti Anda lebih mudah dipindai oleh sistem rekrutmen modern sekaligus tetap nyaman dibaca recruiter.</p><h2>Apa yang dihasilkan?</h2><p>Tool ini menghasilkan struktur CV profesional yang menonjolkan ringkasan, skill, pengalaman, pendidikan, dan kredensial penting dalam format yang rapi.</p>',
                'faqs' => [
                    ['question' => 'Apakah CV ini cocok untuk ATS?', 'answer' => 'Ya, struktur yang digunakan dibuat sederhana, fokus pada konten inti, dan minim elemen yang mengganggu proses parsing ATS.'],
                    ['question' => 'Apakah hasil CV bisa diedit lagi?', 'answer' => 'Bisa. Anda dapat mengunduh hasilnya dalam format Word atau PDF lalu menyesuaikannya sebelum dikirim.'],
                ],
            ],
            [
                'category_slug' => 'generator-dokumen',
                'title' => 'Generator Surat Lamaran Kerja Gratis',
                'slug' => 'generator-surat-lamaran-kerja',
                'short_description' => 'Buat surat lamaran kerja formal, profesional, fresh graduate, magang, atau email lamaran dengan cepat dan rapi.',
                'tool_type' => 'generator',
                'meta_title' => 'Generator Surat Lamaran Kerja Gratis, Formal & Profesional',
                'meta_description' => 'Buat surat lamaran kerja otomatis untuk fresh graduate, profesional, magang, dan email lamaran. Gratis, rapi, dan siap digunakan.',
                'published_at' => now()->subDays(45),
                'body' => '<p>Generator surat lamaran kerja ini dibuat untuk membantu pencari kerja, fresh graduate, pelamar magang, maupun profesional berpengalaman menyiapkan surat lamaran yang sopan, relevan, dan tidak terasa kaku. Anda cukup mengisi data penting seperti posisi yang dilamar, perusahaan tujuan, pengalaman, serta skill utama. Sistem akan menyusun struktur surat yang rapi agar lebih siap dipakai, disalin, atau disesuaikan lagi sesuai kebutuhan pribadi. Cocok dipakai ketika Anda butuh mengirim lamaran dengan cepat tetapi tetap ingin terlihat serius dan profesional.</p><h2>Apa itu surat lamaran kerja?</h2><p>Surat lamaran kerja adalah dokumen pengantar yang menjelaskan minat Anda pada suatu posisi sekaligus memberi ringkasan singkat tentang latar belakang, pengalaman, dan alasan Anda relevan untuk peran tersebut. Berbeda dengan CV yang lebih berupa rangkuman data, surat lamaran membantu recruiter memahami konteks dan motivasi Anda dengan lebih manusiawi.</p><h2>Cara membuat surat lamaran kerja yang baik</h2><p>Mulailah dengan menyebut posisi yang dilamar dan perusahaan tujuan secara jelas. Setelah itu, jelaskan profil singkat Anda, latar belakang pendidikan atau pengalaman, lalu kaitkan skill yang paling relevan dengan kebutuhan posisi tersebut. Tutup surat dengan nada profesional, sopan, dan terbuka untuk proses seleksi lebih lanjut.</p><h2>Tips agar surat lamaran lebih profesional</h2><p>Hindari kalimat yang terlalu generik atau berlebihan. Sebutkan keahlian yang benar-benar Anda kuasai, gunakan gaya bahasa yang rapi, dan sesuaikan tone dengan posisi yang dilamar. Untuk lamaran email, pastikan subject jelas dan body email langsung to the point. Jika Anda juga sedang menyiapkan CV, lengkapi dokumen karier Anda dengan <a href="'.route('tools.show', 'generator-cv-ats').'">Generator CV ATS</a> agar aplikasi kerja terasa lebih lengkap.</p><h2>Catatan penggunaan</h2><p>Hasil surat dari generator ini adalah draft awal yang bisa Anda edit kembali sebelum dikirim. Sesuaikan nama perusahaan, nama recruiter, detail pengalaman, dan tone bahasa agar surat benar-benar mewakili diri Anda.</p>',
                'faqs' => [
                    ['question' => 'Apakah surat lamaran ini bisa digunakan untuk fresh graduate?', 'answer' => 'Bisa. Tersedia mode fresh graduate agar isi surat tetap relevan meskipun pengalaman kerja masih terbatas.'],
                    ['question' => 'Apakah bisa untuk email lamaran kerja?', 'answer' => 'Bisa. Anda dapat memilih mode email lamaran untuk mendapatkan subject email dan body email yang lebih ringkas.'],
                    ['question' => 'Apa bedanya surat lamaran dan CV?', 'answer' => 'CV merangkum data dan pengalaman secara terstruktur, sedangkan surat lamaran menjelaskan konteks, minat, dan relevansi Anda terhadap posisi yang dilamar.'],
                    ['question' => 'Apakah hasilnya bisa diedit?', 'answer' => 'Bisa. Hasil generator ini sebaiknya tetap Anda cek dan edit ringan agar makin sesuai dengan kebutuhan perusahaan tujuan.'],
                    ['question' => 'Apakah generator ini gratis?', 'answer' => 'Ya. Tool ini bisa dipakai gratis untuk membuat draft surat lamaran kerja yang rapi dan siap dipakai.'],
                ],
            ],
            [
                'category_slug' => 'generator-dokumen',
                'title' => 'Generator Quotation / Penawaran Harga Gratis',
                'slug' => 'generator-quotation',
                'short_description' => 'Buat quotation atau surat penawaran harga online lengkap dengan item, total, pajak, dan terms pembayaran.',
                'tool_type' => 'generator',
                'meta_title' => 'Generator Quotation Gratis, Buat Penawaran Harga Online',
                'meta_description' => 'Buat quotation atau surat penawaran harga profesional untuk jasa, freelancer, vendor, dan UMKM. Lengkap dengan item, total, pajak, dan terms.',
                'published_at' => now()->subDays(44),
                'body' => '<p>Generator quotation ini membantu freelancer, vendor, konsultan, kontraktor kecil, hingga UMKM membuat surat penawaran harga yang lebih rapi tanpa perlu menyusun format dari nol. Anda bisa menambahkan item pekerjaan, diskon, pajak, masa berlaku penawaran, hingga terms pembayaran agar dokumen terlihat profesional sejak awal. Cocok dipakai saat mengirim proposal jasa pembuatan website, desain, maintenance, pengadaan barang, dan kebutuhan bisnis lain yang memerlukan penawaran tertulis.</p><h2>Apa itu quotation?</h2><p>Quotation adalah dokumen penawaran harga yang menjelaskan ruang lingkup pekerjaan atau barang, rincian item, total biaya, serta syarat pembayaran kepada calon klien. Dokumen ini sering menjadi dasar awal diskusi sebelum invoice diterbitkan atau proyek dimulai.</p><h2>Kapan quotation digunakan?</h2><p>Quotation dipakai saat calon klien meminta rincian biaya sebelum menyetujui kerja sama. Misalnya saat menawarkan jasa pembuatan website, paket desain, maintenance bulanan, suplai barang, atau proyek operasional tertentu. Dengan quotation yang rapi, proses negosiasi dan approval biasanya menjadi lebih mudah.</p><h2>Komponen penting dalam quotation</h2><p>Dokumen quotation yang baik minimal mencakup identitas vendor, identitas client, judul penawaran, item pekerjaan, subtotal, pajak, biaya tambahan jika ada, total akhir, masa berlaku penawaran, serta terms pembayaran. Tambahan catatan dan syarat kerja juga penting agar ekspektasi kedua pihak lebih jelas.</p><h2>Tips membuat penawaran harga agar terlihat profesional</h2><p>Gunakan judul yang spesifik, rincikan item secara jelas, dan hindari deskripsi yang terlalu singkat bila ruang lingkup pekerjaannya cukup kompleks. Pastikan total biaya mudah dibaca, termin pembayaran realistis, dan masa berlaku penawaran tertulis. Setelah quotation disetujui, Anda bisa lanjutkan proses administrasi ke <a href="'.route('tools.show', 'generator-invoice').'">Generator Invoice</a> atau <a href="'.route('tools.show', 'generator-kwitansi').'">Generator Kwitansi</a>.</p><h2>Catatan penggunaan</h2><p>Quotation ini adalah draft bisnis yang dapat disesuaikan kembali mengikuti negosiasi, kebijakan internal, dan kebutuhan proyek masing-masing. Jika nanti Anda ingin menambahkan template premium atau format Word/Excel, struktur UI-nya juga sudah bisa dikembangkan ke sana.</p>',
                'faqs' => [
                    ['question' => 'Apa bedanya quotation dan invoice?', 'answer' => 'Quotation adalah penawaran harga sebelum transaksi disepakati, sedangkan invoice adalah tagihan resmi setelah pekerjaan atau penjualan berjalan.'],
                    ['question' => 'Apakah quotation mengikat secara hukum?', 'answer' => 'Quotation bisa menjadi dasar kesepakatan awal, tetapi kekuatan hukumnya tetap bergantung pada persetujuan, kontrak, atau dokumen lanjutan yang menyertainya.'],
                    ['question' => 'Apakah bisa digunakan untuk jasa freelance?', 'answer' => 'Bisa. Tool ini cocok untuk freelancer yang ingin mengirim penawaran harga dengan tampilan lebih rapi dan profesional.'],
                    ['question' => 'Apakah bisa menambahkan pajak?', 'answer' => 'Bisa. Anda dapat memasukkan persentase pajak atau PPN sesuai kebutuhan dokumen penawaran.'],
                    ['question' => 'Apakah hasil quotation bisa diunduh?', 'answer' => 'Bisa. Selama export PDF tersedia di project ini, hasil quotation juga dapat dipreview, dicetak, dan diunduh.'],
                ],
            ],
            [
                'category_slug' => 'generator-dokumen',
                'title' => 'Generator SOP Gratis',
                'slug' => 'generator-sop',
                'short_description' => 'Buat SOP administrasi, HR, operasional, finance, customer service, IT helpdesk, dan gudang secara cepat dan rapi.',
                'tool_type' => 'generator',
                'meta_title' => 'Generator SOP Gratis untuk UMKM, HR, Operasional, dan Admin',
                'meta_description' => 'Buat SOP otomatis secara gratis untuk administrasi, HR, finance, customer service, IT helpdesk, gudang, dan operasional bisnis.',
                'published_at' => now()->subDays(43),
                'body' => '<p>Generator SOP ini dirancang untuk UMKM, admin kantor, HR, tim operasional, customer service, finance, dan organisasi kecil yang ingin menulis prosedur kerja lebih rapi tanpa harus memulai dari halaman kosong. Anda bisa menyusun tujuan, ruang lingkup, role, langkah prosedur, indikator keberhasilan, hingga approval dasar dalam satu alur yang sederhana. Hasilnya cocok dijadikan draft awal untuk didiskusikan, diperiksa atasan, lalu dirapikan menjadi dokumen resmi tim.</p><h2>Apa itu SOP?</h2><p>SOP atau Standard Operating Procedure adalah dokumen yang menjelaskan cara kerja standar agar suatu proses dilakukan secara konsisten, lebih mudah dipahami, dan mengurangi kesalahan berulang. SOP membantu tim baru maupun tim lama memiliki acuan kerja yang sama.</p><h2>Manfaat SOP untuk bisnis dan organisasi</h2><p>SOP membantu pekerjaan lebih terstruktur, mempercepat onboarding anggota tim baru, memudahkan kontrol kualitas, dan mengurangi kebingungan saat terjadi pergantian personel. Untuk UMKM atau organisasi kecil, SOP juga membantu proses terlihat lebih tertib walaupun tim masih ramping.</p><h2>Struktur SOP yang baik</h2><p>SOP yang baik memiliki judul yang jelas, informasi dokumen, tujuan, ruang lingkup, definisi istilah bila perlu, pembagian peran, langkah prosedur yang mudah diikuti, dokumen terkait, serta area approval. Struktur seperti ini membuat SOP lebih siap dipakai dalam operasional nyata.</p><h2>Contoh penggunaan SOP</h2><p>SOP dapat dipakai untuk penanganan keluhan pelanggan, approval reimbursement, proses onboarding karyawan, pencatatan stok gudang, penanganan tiket IT helpdesk, hingga proses administrasi surat masuk dan keluar. Jika Anda juga sedang menyusun struktur peran tim, lanjutkan ke <a href="'.route('tools.show', 'generator-job-description').'">Generator Job Description</a> agar SOP dan pembagian tanggung jawab saling melengkapi.</p><h2>Catatan penggunaan</h2><p>Dokumen SOP dari generator ini adalah draft awal. Sebaiknya tetap disesuaikan dengan kebijakan perusahaan, struktur approval, serta kebutuhan audit atau compliance yang berlaku di organisasi Anda.</p>',
                'faqs' => [
                    ['question' => 'Apakah SOP ini cocok untuk UMKM?', 'answer' => 'Ya. Generator ini dibuat agar SOP tetap bisa disusun dengan praktis meskipun tim masih kecil atau proses bisnis belum terlalu kompleks.'],
                    ['question' => 'Apa saja isi SOP yang baik?', 'answer' => 'Minimal ada tujuan, ruang lingkup, peran, langkah prosedur, dokumen terkait, dan catatan risiko agar SOP mudah dipakai di lapangan.'],
                    ['question' => 'Apakah SOP harus disetujui atasan?', 'answer' => 'Idealnya iya, terutama jika SOP akan dipakai lintas tim atau dijadikan acuan resmi operasional.'],
                    ['question' => 'Apakah bisa digunakan untuk HR, finance, atau operasional?', 'answer' => 'Bisa. Generator ini mendukung beberapa jenis SOP umum dan juga dapat disesuaikan secara manual.'],
                    ['question' => 'Apakah hasil SOP bisa diedit?', 'answer' => 'Bisa. Hasil generator sebaiknya dijadikan draft awal yang dapat dirapikan kembali sebelum final.'],
                ],
            ],
            [
                'category_slug' => 'generator-dokumen',
                'title' => 'Generator Job Description Gratis',
                'slug' => 'generator-job-description',
                'short_description' => 'Buat job description profesional untuk kebutuhan internal HR atau versi lowongan kerja yang lebih siap dipublikasikan.',
                'tool_type' => 'generator',
                'meta_title' => 'Generator Job Description Gratis untuk HR, Recruiter, dan UMKM',
                'meta_description' => 'Buat job description profesional untuk berbagai posisi kerja. Cocok untuk HR, recruiter, UMKM, startup, dan perusahaan.',
                'published_at' => now()->subDays(42),
                'body' => '<p>Generator job description ini membantu HR, recruiter, founder startup, pemilik UMKM, dan kepala divisi menyusun deskripsi pekerjaan yang lebih rapi, jelas, dan tidak membingungkan kandidat. Anda bisa membuat format internal HR maupun versi job posting dengan struktur yang tetap konsisten. Tool ini juga menyediakan preset untuk posisi umum seperti admin, customer service, sales, digital marketing, HR staff, finance, IT support, hingga developer agar user tidak perlu selalu mulai dari nol.</p><h2>Apa itu job description?</h2><p>Job description adalah dokumen yang menjelaskan peran suatu posisi, tanggung jawab utama, kualifikasi, skill yang dibutuhkan, hubungan pelaporan, serta indikator keberhasilan. Dokumen ini membantu perusahaan mendefinisikan ekspektasi kerja secara lebih jelas.</p><h2>Kenapa job description penting?</h2><p>Tanpa job description yang rapi, proses hiring sering kabur sejak awal. Kandidat jadi sulit memahami ekspektasi, tim internal sulit menyamakan persepsi, dan evaluasi performa menjadi tidak konsisten. Dengan dokumen yang jelas, proses rekrutmen dan manajemen peran biasanya jauh lebih efisien.</p><h2>Komponen job description yang baik</h2><p>Job description yang baik memuat ringkasan posisi, tujuan jabatan, struktur pelaporan, tanggung jawab utama, kualifikasi pendidikan, pengalaman minimal, skill teknis, soft skill, tools yang digunakan, dan KPI. Bila dipakai untuk job posting, dokumen juga perlu komunikatif dan mudah dipahami kandidat.</p><h2>Tips membuat job description yang menarik kandidat</h2><p>Gunakan bahasa yang jelas, hindari syarat yang terlalu kabur, dan fokus pada kebutuhan nyata pekerjaan. Hindari kalimat diskriminatif dan jangan mencampur tanggung jawab yang terlalu banyak dalam satu posisi jika memang scope-nya berbeda jauh. Setelah struktur peran rapi, Anda juga bisa melengkapinya dengan <a href="'.route('tools.show', 'kalkulator-gaji-bersih').'">Kalkulator Gaji Bersih</a> untuk simulasi kompensasi sederhana.</p><h2>Catatan penggunaan</h2><p>Generator ini menghasilkan draft yang bisa disesuaikan kembali dengan budaya kerja, level jabatan, dan kebutuhan tim. Untuk perusahaan yang sedang menata sistem people operations lebih luas, struktur job description yang rapi juga akan memudahkan penyusunan SOP dan evaluasi KPI.</p>',
                'faqs' => [
                    ['question' => 'Apa perbedaan job description dan job posting?', 'answer' => 'Job description lebih lengkap untuk acuan internal dan HR, sedangkan job posting biasanya versi yang lebih komunikatif untuk menarik kandidat.'],
                    ['question' => 'Apakah tools ini cocok untuk UMKM?', 'answer' => 'Cocok. UMKM sering butuh deskripsi peran yang jelas agar pembagian tugas tidak tumpang tindih.'],
                    ['question' => 'Apa saja isi job description?', 'answer' => 'Biasanya mencakup ringkasan posisi, tanggung jawab, kualifikasi, skill, struktur pelaporan, dan KPI.'],
                    ['question' => 'Apakah bisa digunakan untuk lowongan kerja?', 'answer' => 'Bisa. Tersedia pilihan jenis output yang lebih cocok untuk format lowongan kerja.'],
                    ['question' => 'Apakah hasilnya bisa diedit?', 'answer' => 'Bisa. Hasil generator ini sebaiknya tetap ditinjau dan disesuaikan dengan kebutuhan tim atau perusahaan Anda.'],
                ],
            ],
        ];

        foreach ($tools as $toolData) {
            $faqs = $toolData['faqs'];
            $categorySlug = $toolData['category_slug'];
            unset($toolData['faqs']);
            unset($toolData['category_slug']);

            $tool = Tool::updateOrCreate(
                ['slug' => $toolData['slug']],
                array_merge($toolData, [
                    'category_id' => $this->resolveCategoryId($categorySlug),
                    'is_featured' => true,
                    'is_published' => true,
                    'published_at' => $toolData['published_at'] ?? now()->subDays(15),
                ]),
            );

            foreach ($faqs as $index => $faq) {
                Faq::updateOrCreate(
                    [
                        'faqable_type' => Tool::class,
                        'faqable_id' => $tool->id,
                        'question' => $faq['question'],
                    ],
                    [
                        'answer' => $faq['answer'],
                        'sort_order' => $index + 1,
                        'is_active' => true,
                    ],
                );
            }
        }
    }
}
