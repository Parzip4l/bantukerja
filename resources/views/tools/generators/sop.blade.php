@php
    $selectedTemplateSlug = old('template_slug', $generatorPreview['template_slug'] ?? $generatorTemplates->first()?->slug);
    $sopRoles = old('roles', [['role' => '', 'responsibility' => '']]);
    $sopSteps = old('steps', [['name' => '', 'description' => '', 'pic' => '', 'output' => '']]);
    $sopPresets = [
        'customer-service' => [
            ['name' => 'Menerima keluhan pelanggan', 'description' => 'Tim menerima keluhan melalui kanal resmi dan memastikan informasi awal lengkap.', 'pic' => 'Customer Service', 'output' => 'Tiket / catatan keluhan'],
            ['name' => 'Mencatat detail keluhan', 'description' => 'Catat nama pelanggan, kronologi, nomor pesanan, dan bukti pendukung secara rapi.', 'pic' => 'Customer Service', 'output' => 'Form keluhan terisi'],
            ['name' => 'Mengklasifikasi prioritas', 'description' => 'Tentukan tingkat urgensi berdasarkan dampak dan SLA layanan.', 'pic' => 'Supervisor CS', 'output' => 'Kategori prioritas'],
            ['name' => 'Menindaklanjuti ke tim terkait', 'description' => 'Teruskan ke tim operasional atau teknis yang relevan dengan detail yang cukup.', 'pic' => 'Customer Service', 'output' => 'Tiket eskalasi'],
            ['name' => 'Memberikan update ke pelanggan', 'description' => 'Sampaikan status tindak lanjut secara proaktif dan sopan.', 'pic' => 'Customer Service', 'output' => 'Update status'],
            ['name' => 'Menutup tiket / keluhan', 'description' => 'Tutup laporan setelah solusi diterima atau kasus selesai ditangani.', 'pic' => 'Customer Service', 'output' => 'Tiket closed'],
        ],
        'it-helpdesk' => [
            ['name' => 'Menerima laporan gangguan', 'description' => 'Terima laporan melalui helpdesk, chat internal, atau email resmi.', 'pic' => 'IT Helpdesk', 'output' => 'Tiket gangguan'],
            ['name' => 'Melakukan verifikasi awal', 'description' => 'Pastikan gangguan benar terjadi dan kumpulkan informasi perangkat, user, dan gejala.', 'pic' => 'IT Helpdesk', 'output' => 'Data verifikasi'],
            ['name' => 'Menentukan kategori dan prioritas', 'description' => 'Kelompokkan isu berdasarkan jenis dan dampak bisnis.', 'pic' => 'IT Helpdesk', 'output' => 'Kategori insiden'],
            ['name' => 'Melakukan troubleshooting', 'description' => 'Lakukan penanganan awal sesuai checklist troubleshooting yang berlaku.', 'pic' => 'IT Support', 'output' => 'Hasil troubleshooting'],
            ['name' => 'Eskalasi jika diperlukan', 'description' => 'Eskalasi ke engineer atau vendor apabila gangguan belum terselesaikan.', 'pic' => 'IT Supervisor', 'output' => 'Tiket eskalasi'],
            ['name' => 'Dokumentasi penyelesaian', 'description' => 'Catat akar masalah, solusi, dan langkah pencegahan jika ada.', 'pic' => 'IT Helpdesk', 'output' => 'Knowledge note'],
            ['name' => 'Closing laporan', 'description' => 'Konfirmasi ke user bahwa layanan telah normal dan tiket dapat ditutup.', 'pic' => 'IT Helpdesk', 'output' => 'Tiket closed'],
        ],
    ];
@endphp

<div class="grid gap-6 xl:grid-cols-[minmax(0,1fr),minmax(340px,0.92fr)]">
    <div class="order-2 rounded-3xl border border-slate-200 p-5 xl:order-1">
        <h2 class="text-lg font-semibold text-slate-900">Isi Data SOP</h2>
        <p class="mt-2 text-sm leading-7 text-slate-600">SOP di sini disusun untuk kebutuhan operasional harian yang jelas, mudah dipahami tim, dan tetap siap dirapikan lagi sesuai approval internal.</p>

        <form id="sop-generator-form" method="post" action="{{ route('tools.sop.preview') }}" class="mt-5 grid gap-6">
            @csrf

            <div class="rounded-3xl border border-slate-200 p-5">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Jenis SOP</label>
                        <select name="sop_type" data-sop-preset-select class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                            <option value="administrasi" @selected(old('sop_type') === 'administrasi')>SOP Administrasi</option>
                            <option value="hr" @selected(old('sop_type') === 'hr')>SOP HR</option>
                            <option value="finance" @selected(old('sop_type') === 'finance')>SOP Finance</option>
                            <option value="customer-service" @selected(old('sop_type', 'customer-service') === 'customer-service')>SOP Customer Service</option>
                            <option value="operasional" @selected(old('sop_type') === 'operasional')>SOP Operasional</option>
                            <option value="it-helpdesk" @selected(old('sop_type') === 'it-helpdesk')>SOP IT Helpdesk</option>
                            <option value="gudang-inventory" @selected(old('sop_type') === 'gudang-inventory')>SOP Gudang / Inventory</option>
                            <option value="custom" @selected(old('sop_type') === 'custom')>Custom</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="button" data-sop-preset-fill class="inline-flex h-12 items-center rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Isi contoh otomatis</button>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Nama SOP</label>
                        <input type="text" name="sop_name" value="{{ old('sop_name') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="SOP Penanganan Keluhan Pelanggan">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Nomor dokumen SOP</label>
                        <input type="text" name="document_number" value="{{ old('document_number', \App\Support\DocumentFormatter::generateDocumentNumber('SOP')) }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Versi dokumen</label>
                        <input type="text" name="document_version" value="{{ old('document_version', '1.0') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Tanggal berlaku</label>
                        <input type="date" name="effective_date" value="{{ old('effective_date', now()->toDateString()) }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Departemen / divisi</label>
                        <input type="text" name="department" value="{{ old('department') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Customer Service">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Dibuat oleh</label>
                        <input type="text" name="prepared_by" value="{{ old('prepared_by') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Nama pembuat">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Diperiksa oleh</label>
                        <input type="text" name="reviewed_by" value="{{ old('reviewed_by') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Nama reviewer">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Disetujui oleh</label>
                        <input type="text" name="approved_by" value="{{ old('approved_by') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Nama approver">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700">Tujuan SOP</label>
                        <textarea name="objective" rows="4" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Jelaskan kenapa SOP ini dibuat dan hasil apa yang ingin dijaga.">{{ old('objective') }}</textarea>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700">Ruang lingkup</label>
                        <textarea name="scope" rows="4" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Contoh: SOP ini berlaku untuk seluruh tim customer service yang menangani komplain via WhatsApp, email, dan telepon.">{{ old('scope') }}</textarea>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700">Definisi istilah</label>
                        <textarea name="definitions" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Opsional, isi istilah yang perlu dipahami tim.">{{ old('definitions') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 p-5">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <p class="text-base font-semibold text-slate-900">Peran & tanggung jawab</p>
                    <button type="button" data-repeater-add="sop-roles" class="inline-flex h-10 items-center rounded-2xl border border-slate-200 px-4 text-sm font-semibold text-slate-700">Tambah role</button>
                </div>
                <div class="mt-4 space-y-4" data-repeater-list="sop-roles">
                    @foreach ($sopRoles as $index => $role)
                        <div class="rounded-2xl border border-slate-200 p-4" data-repeater-item>
                            <div class="mb-4 flex items-center justify-between gap-3">
                                <p class="text-sm font-semibold text-slate-900" data-repeater-title="Role">Role #{{ $loop->iteration }}</p>
                                <button type="button" data-repeater-remove class="inline-flex h-9 items-center rounded-2xl border border-rose-200 px-3 text-xs font-semibold text-rose-600">Hapus</button>
                            </div>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <input type="text" name="roles[{{ $index }}][role]" value="{{ $role['role'] ?? '' }}" class="h-12 rounded-2xl border border-slate-200 px-4" placeholder="Contoh: Customer Service">
                                <input type="text" name="roles[{{ $index }}][responsibility]" value="{{ $role['responsibility'] ?? '' }}" class="h-12 rounded-2xl border border-slate-200 px-4" placeholder="Menangani keluhan awal dan update pelanggan">
                            </div>
                        </div>
                    @endforeach
                </div>
                <template data-repeater-template="sop-roles">
                    <div class="rounded-2xl border border-slate-200 p-4" data-repeater-item>
                        <div class="mb-4 flex items-center justify-between gap-3">
                            <p class="text-sm font-semibold text-slate-900" data-repeater-title="Role">Role baru</p>
                            <button type="button" data-repeater-remove class="inline-flex h-9 items-center rounded-2xl border border-rose-200 px-3 text-xs font-semibold text-rose-600">Hapus</button>
                        </div>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <input type="text" name="roles[__INDEX__][role]" class="h-12 rounded-2xl border border-slate-200 px-4" placeholder="Nama role">
                            <input type="text" name="roles[__INDEX__][responsibility]" class="h-12 rounded-2xl border border-slate-200 px-4" placeholder="Tanggung jawab utama">
                        </div>
                    </div>
                </template>
            </div>

            <div class="rounded-3xl border border-slate-200 p-5">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <p class="text-base font-semibold text-slate-900">Langkah prosedur</p>
                    <button type="button" data-repeater-add="sop-steps" class="inline-flex h-10 items-center rounded-2xl border border-slate-200 px-4 text-sm font-semibold text-slate-700">Tambah langkah</button>
                </div>
                <div class="mt-4 space-y-4" data-repeater-list="sop-steps" data-sop-step-list>
                    @foreach ($sopSteps as $index => $step)
                        <div class="rounded-2xl border border-slate-200 p-4" data-repeater-item>
                            <div class="mb-4 flex items-center justify-between gap-3">
                                <p class="text-sm font-semibold text-slate-900" data-repeater-title="Langkah">Langkah #{{ $loop->iteration }}</p>
                                <button type="button" data-repeater-remove class="inline-flex h-9 items-center rounded-2xl border border-rose-200 px-3 text-xs font-semibold text-rose-600">Hapus</button>
                            </div>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <input type="text" name="steps[{{ $index }}][name]" value="{{ $step['name'] ?? '' }}" class="h-12 rounded-2xl border border-slate-200 px-4" placeholder="Nama langkah">
                                <input type="text" name="steps[{{ $index }}][pic]" value="{{ $step['pic'] ?? '' }}" class="h-12 rounded-2xl border border-slate-200 px-4" placeholder="PIC">
                                <textarea name="steps[{{ $index }}][description]" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-3 sm:col-span-2" placeholder="Deskripsi langkah prosedur">{{ $step['description'] ?? '' }}</textarea>
                                <input type="text" name="steps[{{ $index }}][output]" value="{{ $step['output'] ?? '' }}" class="h-12 rounded-2xl border border-slate-200 px-4 sm:col-span-2" placeholder="Output / dokumen yang dihasilkan">
                            </div>
                        </div>
                    @endforeach
                </div>
                <template data-repeater-template="sop-steps">
                    <div class="rounded-2xl border border-slate-200 p-4" data-repeater-item>
                        <div class="mb-4 flex items-center justify-between gap-3">
                            <p class="text-sm font-semibold text-slate-900" data-repeater-title="Langkah">Langkah baru</p>
                            <button type="button" data-repeater-remove class="inline-flex h-9 items-center rounded-2xl border border-rose-200 px-3 text-xs font-semibold text-rose-600">Hapus</button>
                        </div>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <input type="text" name="steps[__INDEX__][name]" class="h-12 rounded-2xl border border-slate-200 px-4" placeholder="Nama langkah">
                            <input type="text" name="steps[__INDEX__][pic]" class="h-12 rounded-2xl border border-slate-200 px-4" placeholder="PIC">
                            <textarea name="steps[__INDEX__][description]" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-3 sm:col-span-2" placeholder="Deskripsi langkah prosedur"></textarea>
                            <input type="text" name="steps[__INDEX__][output]" class="h-12 rounded-2xl border border-slate-200 px-4 sm:col-span-2" placeholder="Output / dokumen yang dihasilkan">
                        </div>
                    </div>
                </template>
            </div>

            <div class="rounded-3xl border border-slate-200 p-5">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700">Dokumen / form terkait</label>
                        <textarea name="related_documents" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Contoh: Form keluhan pelanggan, template email update, log eskalasi.">{{ old('related_documents') }}</textarea>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700">Risiko / catatan penting</label>
                        <textarea name="risk_notes" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Tuliskan hal-hal yang perlu diwaspadai saat SOP dijalankan.">{{ old('risk_notes') }}</textarea>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700">Indikator keberhasilan / KPI</label>
                        <textarea name="kpi" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Contoh: Waktu respon awal maksimal 15 menit, tingkat penyelesaian 95%.">{{ old('kpi') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <button class="h-12 rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white">Generate SOP</button>
                <button formaction="{{ route('tools.sop.pdf') }}" class="h-12 rounded-2xl bg-blue-700 px-5 text-sm font-semibold text-white">Download PDF</button>
                <button formaction="{{ route('tools.sop.print') }}" formtarget="_blank" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Print</button>
                <button formaction="{{ route('tools.sop.download') }}" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Copy hasil TXT</button>
                <button type="reset" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Reset form</button>
            </div>
        </form>

        @if ($generatorPreview && $generatorPreview['copy_text'])
            <pre id="sop-copy-content" class="sr-only">{{ $generatorPreview['copy_text'] }}</pre>
            <button type="button" data-copy-target="#sop-copy-content" class="mt-4 inline-flex h-11 items-center rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Copy hasil SOP</button>
        @endif
    </div>

    <div class="order-1 self-start rounded-3xl border border-slate-200 bg-slate-50/60 p-5 xl:order-2 xl:sticky xl:top-24">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Preview SOP</h2>
                <p class="mt-1 text-sm text-slate-500">Format SOP disusun agar langsung nyaman dibaca tim dan tetap mudah disesuaikan saat approval.</p>
            </div>
            @if ($generatorPreview)
                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">{{ $generatorPreview['template']->name }}</span>
            @endif
        </div>

        <div class="mt-5 rounded-[1.75rem] border border-slate-200 bg-white p-4">
            <h3 class="text-sm font-semibold text-slate-900">Template SOP</h3>
            <div class="mt-4 grid gap-3">
                @foreach ($generatorTemplates as $templateOption)
                    <label class="cursor-pointer">
                        <input type="radio" name="template_slug" value="{{ $templateOption->slug }}" form="sop-generator-form" class="peer sr-only" @checked($selectedTemplateSlug === $templateOption->slug)>
                        <span class="block rounded-3xl border border-slate-200 bg-white p-4 transition hover:border-blue-200 peer-checked:border-blue-300 peer-checked:bg-blue-50/70 peer-checked:ring-2 peer-checked:ring-blue-100">
                            <span class="block text-sm font-semibold text-slate-900">{{ $templateOption->name }}</span>
                            <span class="mt-2 block text-xs leading-6 text-slate-500">{{ $templateOption->description }}</span>
                        </span>
                    </label>
                @endforeach
            </div>
        </div>

        <script type="application/json" id="sop-preset-data">@json($sopPresets)</script>

        <div class="mt-5 overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white p-3 shadow-sm">
            @if ($generatorPreview)
                {!! $generatorPreview['html'] !!}
            @else
                <div class="flex min-h-[420px] items-center justify-center rounded-[1.25rem] border border-dashed border-slate-200 bg-slate-50 px-6 text-center text-sm leading-7 text-slate-500">
                    Pilih jenis SOP, isi struktur dokumen, lalu generate untuk mendapatkan SOP yang siap dikembangkan lebih lanjut.
                </div>
            @endif
        </div>

        <div class="mt-5 rounded-3xl border border-amber-100 bg-amber-50 p-4 text-sm leading-7 text-amber-900">
            Dokumen SOP ini adalah draf kerja awal. Sesuaikan lagi dengan kebijakan perusahaan, alur approval, dan kebutuhan audit internal masing-masing. Butuh sistem HR, approval, absensi, dan payroll yang lebih rapi? Pelajari solusi SHIFT HRIS.
        </div>
    </div>
</div>
