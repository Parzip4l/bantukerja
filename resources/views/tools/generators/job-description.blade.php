@php
    $selectedTemplateSlug = old('template_slug', $generatorPreview['template_slug'] ?? $generatorTemplates->first()?->slug);
    $responsibilities = old('responsibilities', [['text' => '']]);
    $kpis = old('kpis', [['text' => '']]);
    $jobPresets = [
        'Admin' => [
            'position_summary' => 'Posisi ini bertanggung jawab menjaga kelancaran administrasi operasional harian, dokumen, dan komunikasi internal yang rapi.',
            'position_objective' => 'Memastikan proses administrasi berjalan tertib, cepat, dan minim kesalahan.',
            'responsibilities' => ['Mengelola arsip dokumen dan surat masuk/keluar', 'Membuat rekap data operasional dan administrasi', 'Berkoordinasi dengan tim internal terkait kebutuhan administrasi', 'Membantu penjadwalan meeting dan follow-up dokumen'],
            'technical_skills' => 'Microsoft Excel, Microsoft Word, Google Workspace, kearsipan dokumen',
            'soft_skills' => 'Teliti, rapi, komunikasi, manajemen waktu',
            'kpis' => ['Tingkat ketepatan dokumen', 'Kecepatan penyelesaian administrasi', 'Kelengkapan arsip bulanan'],
        ],
        'Customer Service' => [
            'position_summary' => 'Posisi ini menjadi garda depan komunikasi dengan pelanggan dan menjaga pengalaman layanan tetap responsif dan solutif.',
            'position_objective' => 'Menangani kebutuhan pelanggan dengan cepat, akurat, dan ramah untuk menjaga kepuasan pelanggan.',
            'responsibilities' => ['Menjawab pertanyaan pelanggan melalui kanal resmi', 'Mencatat dan menindaklanjuti keluhan pelanggan', 'Berkoordinasi dengan tim terkait untuk penyelesaian kasus', 'Membuat laporan insight pelanggan secara berkala'],
            'technical_skills' => 'CRM, ticketing system, live chat, Google Sheets',
            'soft_skills' => 'Empati, komunikasi, problem solving, sabar',
            'kpis' => ['Response time awal', 'Tingkat penyelesaian tiket', 'Skor kepuasan pelanggan'],
        ],
        'Sales' => [
            'position_summary' => 'Posisi ini fokus pada pencapaian target penjualan, pengembangan pipeline prospek, dan membangun relasi yang sehat dengan calon pelanggan.',
            'position_objective' => 'Mendorong pertumbuhan pendapatan melalui aktivitas penjualan yang terstruktur dan konsisten.',
            'responsibilities' => ['Mencari prospek baru dan melakukan follow-up', 'Melakukan presentasi produk atau layanan', 'Menyusun penawaran dan negosiasi dengan calon klien', 'Menjaga relasi pelanggan untuk repeat order'],
            'technical_skills' => 'CRM, presentasi, negosiasi, pipeline management',
            'soft_skills' => 'Percaya diri, komunikasi persuasif, orientasi target, disiplin',
            'kpis' => ['Pencapaian target penjualan', 'Jumlah prospek aktif', 'Conversion rate'],
        ],
        'Digital Marketing' => [
            'position_summary' => 'Posisi ini bertanggung jawab merancang, menjalankan, dan mengoptimalkan aktivitas pemasaran digital lintas channel.',
            'position_objective' => 'Meningkatkan traffic, leads, dan kualitas campaign digital secara berkelanjutan.',
            'responsibilities' => ['Mengelola campaign digital berbayar dan organik', 'Membuat kalender konten dan evaluasi performa', 'Melakukan analisis data campaign', 'Berkoordinasi dengan tim desain dan sales'],
            'technical_skills' => 'Meta Ads, Google Ads, SEO, GA4, content planning',
            'soft_skills' => 'Analitis, kreatif, komunikasi, adaptif',
            'kpis' => ['Jumlah leads', 'Cost per lead', 'Traffic organik', 'ROAS campaign'],
        ],
        'HR Staff' => [
            'position_summary' => 'Posisi ini mendukung proses HR harian mulai dari administrasi karyawan, recruitment, hingga koordinasi kebijakan internal.',
            'position_objective' => 'Membantu tim HR menjaga proses people operations tetap tertib, cepat, dan sesuai kebijakan.',
            'responsibilities' => ['Mengelola administrasi data karyawan', 'Mendukung proses rekrutmen dan interview scheduling', 'Membantu onboarding dan dokumen karyawan baru', 'Menyusun laporan HR dasar secara berkala'],
            'technical_skills' => 'HRIS, Google Sheets, administrasi HR, recruitment support',
            'soft_skills' => 'Teliti, komunikasi, empati, confidentiality',
            'kpis' => ['Kelengkapan data karyawan', 'Kecepatan proses administrasi', 'Lead time recruitment support'],
        ],
        'Finance Staff' => [
            'position_summary' => 'Posisi ini menjaga kelancaran administrasi keuangan, pencatatan transaksi, dan dokumentasi pembayaran perusahaan.',
            'position_objective' => 'Membantu fungsi keuangan berjalan akurat, tepat waktu, dan terdokumentasi baik.',
            'responsibilities' => ['Mencatat transaksi keuangan harian', 'Menyiapkan dokumen pembayaran dan reimbursement', 'Melakukan rekonsiliasi dasar', 'Membantu penyusunan laporan keuangan operasional'],
            'technical_skills' => 'Excel, software akuntansi, rekonsiliasi, petty cash',
            'soft_skills' => 'Teliti, integritas, disiplin, numerik',
            'kpis' => ['Ketepatan pencatatan transaksi', 'On-time payment processing', 'Akurasi rekonsiliasi'],
        ],
        'IT Support' => [
            'position_summary' => 'Posisi ini menangani kebutuhan dukungan teknis harian pengguna, perangkat kerja, dan troubleshooting dasar infrastruktur IT.',
            'position_objective' => 'Menjaga produktivitas pengguna melalui layanan bantuan teknis yang cepat dan tepat.',
            'responsibilities' => ['Menangani tiket gangguan user', 'Setup perangkat kerja dan akun user', 'Melakukan troubleshooting hardware/software dasar', 'Mendokumentasikan penyelesaian masalah'],
            'technical_skills' => 'Helpdesk, troubleshooting, networking dasar, Windows/macOS',
            'soft_skills' => 'Problem solving, sabar, komunikasi, service mindset',
            'kpis' => ['SLA penyelesaian tiket', 'First response time', 'Persentase tiket selesai'],
        ],
        'Backend Developer' => [
            'position_summary' => 'Posisi ini membangun, memelihara, dan mengoptimalkan layanan backend yang stabil, aman, dan mudah dikembangkan.',
            'position_objective' => 'Menyediakan fondasi sistem backend yang mendukung fitur produk dan integrasi bisnis secara andal.',
            'responsibilities' => ['Mengembangkan API dan business logic', 'Menjaga kualitas database dan performa query', 'Berkoordinasi dengan frontend dan product team', 'Menulis testing dan dokumentasi teknis'],
            'technical_skills' => 'Laravel, PHP, MySQL, REST API, Git',
            'soft_skills' => 'Analitis, kolaboratif, problem solving, ownership',
            'kpis' => ['Stabilitas API', 'Kecepatan delivery fitur', 'Coverage testing', 'Jumlah bug produksi'],
        ],
        'Frontend Developer' => [
            'position_summary' => 'Posisi ini bertanggung jawab membangun antarmuka produk yang cepat, jelas, dan nyaman digunakan pada berbagai perangkat.',
            'position_objective' => 'Menghasilkan pengalaman pengguna yang baik melalui implementasi frontend yang rapi dan responsif.',
            'responsibilities' => ['Membangun UI berdasarkan kebutuhan produk', 'Menjaga konsistensi komponen frontend', 'Mengoptimalkan performa halaman', 'Berkoordinasi dengan designer dan backend'],
            'technical_skills' => 'HTML, CSS, JavaScript, responsive UI, component-based frontend',
            'soft_skills' => 'Detail-oriented, kolaboratif, problem solving, komunikasi',
            'kpis' => ['Kecepatan implementasi UI', 'Skor performa halaman', 'Jumlah issue UI/UX'],
        ],
        'Project Manager' => [
            'position_summary' => 'Posisi ini mengelola timeline, scope, koordinasi tim, dan risiko proyek agar deliverables tercapai sesuai target.',
            'position_objective' => 'Memastikan proyek berjalan terarah, komunikatif, dan selesai sesuai timeline serta prioritas bisnis.',
            'responsibilities' => ['Menyusun timeline dan milestone proyek', 'Mengelola komunikasi antar tim dan stakeholder', 'Memantau risiko dan hambatan delivery', 'Menjaga dokumentasi dan status proyek tetap rapi'],
            'technical_skills' => 'Project planning, task management tools, documentation, stakeholder management',
            'soft_skills' => 'Leadership, komunikasi, negosiasi, problem solving',
            'kpis' => ['On-time delivery', 'Keakuratan project status', 'Tingkat penyelesaian milestone'],
        ],
        'Operations Manager' => [
            'position_summary' => 'Posisi ini memimpin perbaikan proses operasional agar aktivitas harian perusahaan berjalan lebih efisien dan konsisten.',
            'position_objective' => 'Meningkatkan kualitas proses, koordinasi lintas tim, dan efisiensi operasional bisnis.',
            'responsibilities' => ['Mengawasi proses operasional harian', 'Membuat perbaikan SOP dan kontrol proses', 'Mengevaluasi bottleneck operasional', 'Mengelola koordinasi lintas divisi untuk pencapaian target'],
            'technical_skills' => 'Process improvement, reporting, KPI management, operations planning',
            'soft_skills' => 'Leadership, analitis, komunikasi, decision making',
            'kpis' => ['Efisiensi proses', 'Kepatuhan SOP', 'Produktivitas tim operasional'],
        ],
    ];
@endphp

<div class="grid gap-6 xl:grid-cols-[minmax(0,1fr),minmax(340px,0.95fr)]">
    <div class="order-2 rounded-3xl border border-slate-200 p-5 xl:order-1">
        <h2 class="text-lg font-semibold text-slate-900">Isi Data Job Description</h2>
        <p class="mt-2 text-sm leading-7 text-slate-600">Buat job description yang rapi untuk kebutuhan internal HR atau versi lowongan kerja yang lebih siap dipublikasikan.</p>

        <form id="job-description-generator-form" method="post" action="{{ route('tools.job-description.preview') }}" class="mt-5 grid gap-6">
            @csrf

            <div class="rounded-3xl border border-slate-200 p-5">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Preset posisi umum</label>
                        <select data-job-preset-select class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                            <option value="">Pilih preset posisi</option>
                            @foreach (array_keys($jobPresets) as $jobPresetName)
                                <option value="{{ $jobPresetName }}">{{ $jobPresetName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="button" data-job-preset-fill class="inline-flex h-12 items-center rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Isi contoh otomatis</button>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Jenis output</label>
                        <select name="output_type" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                            <option value="internal-hr" @selected(old('output_type', 'internal-hr') === 'internal-hr')>Format internal HR</option>
                            <option value="job-posting" @selected(old('output_type') === 'job-posting')>Format lowongan kerja / job posting</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Nama posisi</label>
                        <input type="text" name="position_name" value="{{ old('position_name') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Backend Developer">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Departemen / divisi</label>
                        <input type="text" name="department" value="{{ old('department') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Technology">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Level jabatan</label>
                        <select name="job_level" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                            @foreach (['intern' => 'Intern', 'staff' => 'Staff', 'senior-staff' => 'Senior Staff', 'supervisor' => 'Supervisor', 'manager' => 'Manager', 'head' => 'Head', 'director' => 'Director'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('job_level', 'staff') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Tipe pekerjaan</label>
                        <select name="employment_type" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                            @foreach (['full-time' => 'Full-time', 'part-time' => 'Part-time', 'internship' => 'Internship', 'contract' => 'Contract', 'freelance' => 'Freelance', 'remote' => 'Remote', 'hybrid' => 'Hybrid', 'on-site' => 'On-site'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('employment_type', 'full-time') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Lokasi kerja</label>
                        <input type="text" name="work_location" value="{{ old('work_location') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Jakarta / Hybrid">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Atasan langsung / reports to</label>
                        <input type="text" name="reports_to" value="{{ old('reports_to') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Engineering Manager">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Bawahan langsung</label>
                        <input type="text" name="direct_reports" value="{{ old('direct_reports') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Opsional">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Bahasa output</label>
                        <select name="language" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                            <option value="id" @selected(old('language', 'id') === 'id')>Indonesia</option>
                            <option value="en" @selected(old('language') === 'en')>English basic</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Gaya output</label>
                        <select name="output_style" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                            <option value="formal-hr" @selected(old('output_style', 'formal-hr') === 'formal-hr')>Formal HR</option>
                            <option value="friendly-startup" @selected(old('output_style') === 'friendly-startup')>Friendly startup</option>
                            <option value="singkat" @selected(old('output_style') === 'singkat')>Singkat</option>
                            <option value="detail-profesional" @selected(old('output_style') === 'detail-profesional')>Detail profesional</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700">Ringkasan posisi</label>
                        <textarea name="position_summary" rows="4" data-job-preset-field="position_summary" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Ringkasan singkat mengenai posisi dan fokus pekerjaan utamanya.">{{ old('position_summary') }}</textarea>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700">Tujuan posisi</label>
                        <textarea name="position_objective" rows="4" data-job-preset-field="position_objective" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Tujuan bisnis dari posisi ini bagi perusahaan atau tim.">{{ old('position_objective') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 p-5">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <p class="text-base font-semibold text-slate-900">Tanggung jawab utama</p>
                    <button type="button" data-repeater-add="job-responsibilities" class="inline-flex h-10 items-center rounded-2xl border border-slate-200 px-4 text-sm font-semibold text-slate-700">Tambah tanggung jawab</button>
                </div>
                <div class="mt-4 space-y-4" data-repeater-list="job-responsibilities" data-job-responsibility-list>
                    @foreach ($responsibilities as $index => $responsibility)
                        <div class="rounded-2xl border border-slate-200 p-4" data-repeater-item>
                            <div class="mb-4 flex items-center justify-between gap-3">
                                <p class="text-sm font-semibold text-slate-900" data-repeater-title="Tanggung jawab">Tanggung jawab #{{ $loop->iteration }}</p>
                                <button type="button" data-repeater-remove class="inline-flex h-9 items-center rounded-2xl border border-rose-200 px-3 text-xs font-semibold text-rose-600">Hapus</button>
                            </div>
                            <textarea name="responsibilities[{{ $index }}][text]" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Tuliskan tanggung jawab utama posisi ini.">{{ $responsibility['text'] ?? '' }}</textarea>
                        </div>
                    @endforeach
                </div>
                <template data-repeater-template="job-responsibilities">
                    <div class="rounded-2xl border border-slate-200 p-4" data-repeater-item>
                        <div class="mb-4 flex items-center justify-between gap-3">
                            <p class="text-sm font-semibold text-slate-900" data-repeater-title="Tanggung jawab">Tanggung jawab baru</p>
                            <button type="button" data-repeater-remove class="inline-flex h-9 items-center rounded-2xl border border-rose-200 px-3 text-xs font-semibold text-rose-600">Hapus</button>
                        </div>
                        <textarea name="responsibilities[__INDEX__][text]" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Tuliskan tanggung jawab utama posisi ini."></textarea>
                    </div>
                </template>
            </div>

            <div class="rounded-3xl border border-slate-200 p-5">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Kualifikasi pendidikan</label>
                        <input type="text" name="education_qualification" value="{{ old('education_qualification') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Minimal S1 Teknik Informatika atau relevan">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Pengalaman minimal</label>
                        <input type="text" name="minimum_experience" value="{{ old('minimum_experience') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Minimal 2 tahun di bidang terkait">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700">Skill teknis</label>
                        <textarea name="technical_skills" rows="3" data-job-preset-field="technical_skills" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Pisahkan dengan koma, misalnya Laravel, REST API, MySQL, Git">{{ old('technical_skills') }}</textarea>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700">Skill non-teknis / soft skill</label>
                        <textarea name="soft_skills" rows="3" data-job-preset-field="soft_skills" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Contoh: komunikasi, ownership, problem solving">{{ old('soft_skills') }}</textarea>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700">Tools / software yang digunakan</label>
                        <textarea name="tools_software" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Contoh: Slack, Notion, Jira, Google Workspace">{{ old('tools_software') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 p-5">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <p class="text-base font-semibold text-slate-900">KPI / indikator keberhasilan</p>
                    <button type="button" data-repeater-add="job-kpis" class="inline-flex h-10 items-center rounded-2xl border border-slate-200 px-4 text-sm font-semibold text-slate-700">Tambah KPI</button>
                </div>
                <div class="mt-4 space-y-4" data-repeater-list="job-kpis" data-job-kpi-list>
                    @foreach ($kpis as $index => $kpi)
                        <div class="rounded-2xl border border-slate-200 p-4" data-repeater-item>
                            <div class="mb-4 flex items-center justify-between gap-3">
                                <p class="text-sm font-semibold text-slate-900" data-repeater-title="KPI">KPI #{{ $loop->iteration }}</p>
                                <button type="button" data-repeater-remove class="inline-flex h-9 items-center rounded-2xl border border-rose-200 px-3 text-xs font-semibold text-rose-600">Hapus</button>
                            </div>
                            <textarea name="kpis[{{ $index }}][text]" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Tuliskan indikator keberhasilan posisi ini.">{{ $kpi['text'] ?? '' }}</textarea>
                        </div>
                    @endforeach
                </div>
                <template data-repeater-template="job-kpis">
                    <div class="rounded-2xl border border-slate-200 p-4" data-repeater-item>
                        <div class="mb-4 flex items-center justify-between gap-3">
                            <p class="text-sm font-semibold text-slate-900" data-repeater-title="KPI">KPI baru</p>
                            <button type="button" data-repeater-remove class="inline-flex h-9 items-center rounded-2xl border border-rose-200 px-3 text-xs font-semibold text-rose-600">Hapus</button>
                        </div>
                        <textarea name="kpis[__INDEX__][text]" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Tuliskan indikator keberhasilan posisi ini."></textarea>
                    </div>
                </template>
            </div>

            <div class="rounded-3xl border border-slate-200 p-5">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700">Benefit</label>
                        <textarea name="benefits" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Opsional, misalnya BPJS, bonus, laptop kerja, WFH flexibility.">{{ old('benefits') }}</textarea>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Range gaji</label>
                        <input type="text" name="salary_range" value="{{ old('salary_range') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Rp 7.000.000 - Rp 10.000.000">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Catatan tambahan</label>
                        <input type="text" name="additional_notes" value="{{ old('additional_notes') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Opsional">
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <button class="h-12 rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white">Generate job description</button>
                <button formaction="{{ route('tools.job-description.pdf') }}" class="h-12 rounded-2xl bg-blue-700 px-5 text-sm font-semibold text-white">Download PDF</button>
                <button formaction="{{ route('tools.job-description.print') }}" formtarget="_blank" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Print</button>
                <button formaction="{{ route('tools.job-description.download') }}" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Copy JD</button>
                <button type="reset" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Reset form</button>
            </div>
        </form>

        @if ($generatorPreview && $generatorPreview['copy_text'])
            <pre id="job-description-copy-content" class="sr-only">{{ $generatorPreview['copy_text'] }}</pre>
            <button type="button" data-copy-target="#job-description-copy-content" class="mt-4 inline-flex h-11 items-center rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Copy hasil JD</button>
        @endif
    </div>

    <div class="order-1 self-start rounded-3xl border border-slate-200 bg-slate-50/60 p-5 xl:order-2 xl:sticky xl:top-24">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Preview Job Description</h2>
                <p class="mt-1 text-sm text-slate-500">Pilih format internal HR atau job posting, lalu cek hasilnya langsung tanpa pindah halaman.</p>
            </div>
            @if ($generatorPreview)
                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">{{ $generatorPreview['template']->name }}</span>
            @endif
        </div>

        <div class="mt-5 rounded-[1.75rem] border border-slate-200 bg-white p-4">
            <h3 class="text-sm font-semibold text-slate-900">Template Output</h3>
            <div class="mt-4 grid gap-3">
                @foreach ($generatorTemplates as $templateOption)
                    <label class="cursor-pointer">
                        <input type="radio" name="template_slug" value="{{ $templateOption->slug }}" form="job-description-generator-form" class="peer sr-only" @checked($selectedTemplateSlug === $templateOption->slug)>
                        <span class="block rounded-3xl border border-slate-200 bg-white p-4 transition hover:border-blue-200 peer-checked:border-blue-300 peer-checked:bg-blue-50/70 peer-checked:ring-2 peer-checked:ring-blue-100">
                            <span class="block text-sm font-semibold text-slate-900">{{ $templateOption->name }}</span>
                            <span class="mt-2 block text-xs leading-6 text-slate-500">{{ $templateOption->description }}</span>
                        </span>
                    </label>
                @endforeach
            </div>
        </div>

        <script type="application/json" id="job-preset-data">@json($jobPresets)</script>

        <div class="mt-5 overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white p-3 shadow-sm">
            @if ($generatorPreview)
                {!! $generatorPreview['html'] !!}
            @else
                <div class="flex min-h-[420px] items-center justify-center rounded-[1.25rem] border border-dashed border-slate-200 bg-slate-50 px-6 text-center text-sm leading-7 text-slate-500">
                    Pilih posisi umum atau isi manual untuk menyusun job description yang siap dipakai tim HR maupun lowongan kerja.
                </div>
            @endif
        </div>

        <div class="mt-5 rounded-3xl border border-amber-100 bg-amber-50 p-4 text-sm leading-7 text-amber-900">
            Job description yang baik membantu hiring lebih terarah dan tidak diskriminatif. Butuh sistem HR, approval, absensi, dan payroll yang lebih rapi? Pelajari solusi SHIFT HRIS. Anda juga bisa lanjut ke <a href="{{ route('tools.show', 'kalkulator-gaji-bersih') }}" class="font-semibold underline decoration-amber-300 underline-offset-4">Kalkulator Gaji Bersih</a>.
        </div>
    </div>
</div>
