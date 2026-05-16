@extends('layouts.app')

@php
    use Illuminate\Support\Str;

    $result = session('tool_result');
    $invoiceItems = old('items', [['name' => '', 'unit' => '', 'qty' => '', 'price' => '', 'description' => '']]);
    $workExperiences = old('work_experiences', [[
        'job_title' => '',
        'company' => '',
        'location' => '',
        'start_date' => '',
        'end_date' => '',
        'is_current' => false,
        'description' => '',
    ]]);
    $educations = old('educations', [[
        'degree' => '',
        'institution' => '',
        'location' => '',
        'start_year' => '',
        'end_year' => '',
        'description' => '',
    ]]);
@endphp

@section('content')
    <section class="container-shell py-12">
        <x-breadcrumb :items="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Tools', 'url' => route('tools.index')],
            ['label' => $tool->title, 'url' => route('tools.show', $tool->slug)],
        ]" />

        <div class="grid gap-8 lg:grid-cols-[1.2fr,0.8fr]">
            <div>
                <div class="card-panel p-7">
                    <p class="eyebrow">{{ $tool->category?->name }}</p>
                    <h1 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">{{ $tool->title }}</h1>
                    <p class="mt-4 text-base leading-8 text-slate-600">{{ $tool->short_description }}</p>
                </div>

                <div class="mt-6">
                    <x-ad-slot slot-key="tool_top" label="Tool Top" />
                </div>

                <div class="mt-6 card-panel p-7">
                    @switch($tool->slug)
                        @case('kalkulator-thr')
                            <form method="post" action="{{ route('tools.thr.calculate') }}" class="grid gap-4 sm:grid-cols-2">
                                @csrf
                                <div class="sm:col-span-2">
                                    <label class="mb-2 block text-sm font-medium text-slate-700">Gaji bulanan</label>
                                    <input type="number" step="0.01" name="monthly_salary" value="{{ old('monthly_salary') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700">Lama bekerja (bulan)</label>
                                    <input type="number" name="months_worked" value="{{ old('months_worked') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700">Status karyawan</label>
                                    <input type="text" name="employee_status" value="{{ old('employee_status') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                </div>
                                <button class="h-12 rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white sm:col-span-2">Hitung THR</button>
                            </form>
                        @break

                        @case('kalkulator-gaji-bersih')
                            <form method="post" action="{{ route('tools.salary.calculate') }}" class="grid gap-4 sm:grid-cols-2">
                                @csrf
                                @foreach ([
                                    'base_salary' => 'Gaji pokok',
                                    'allowance' => 'Tunjangan',
                                    'deduction' => 'Potongan',
                                    'bpjs' => 'BPJS',
                                    'tax' => 'Pajak',
                                ] as $field => $label)
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-slate-700">{{ $label }}</label>
                                        <input type="number" step="0.01" name="{{ $field }}" value="{{ old($field) }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                    </div>
                                @endforeach
                                <button class="h-12 rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white sm:col-span-2">Hitung gaji bersih</button>
                            </form>
                        @break

                        @case('kalkulator-lembur')
                            <form method="post" action="{{ route('tools.overtime.calculate') }}" class="grid gap-4 sm:grid-cols-2">
                                @csrf
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700">Upah bulanan</label>
                                    <input type="number" step="0.01" name="monthly_wage" value="{{ old('monthly_wage') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700">Jumlah jam lembur</label>
                                    <input type="number" step="0.1" name="hours" value="{{ old('hours') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="mb-2 block text-sm font-medium text-slate-700">Tipe hari</label>
                                    <select name="day_type" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                        <option value="kerja" @selected(old('day_type') === 'kerja')>Hari kerja</option>
                                        <option value="libur" @selected(old('day_type') === 'libur')>Hari libur</option>
                                    </select>
                                </div>
                                <button class="h-12 rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white sm:col-span-2">Hitung lembur</button>
                            </form>
                        @break

                        @case('generator-invoice')
                            <form method="post" action="{{ route('tools.invoice.calculate') }}" enctype="multipart/form-data" class="grid gap-6">
                                @csrf
                                <div class="rounded-3xl border border-slate-200 p-5">
                                    <h3 class="text-lg font-semibold text-slate-900">Profil perusahaan</h3>
                                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Logo perusahaan</label>
                                            <input type="file" name="business_logo" accept="image/*" class="block w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm">
                                        </div>
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Nama bisnis</label>
                                            <input type="text" name="business_name" value="{{ old('business_name') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Alamat perusahaan</label>
                                            <textarea name="business_address" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-3">{{ old('business_address') }}</textarea>
                                        </div>
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Nomor telepon</label>
                                            <input type="text" name="business_phone" value="{{ old('business_phone') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                        </div>
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Email bisnis</label>
                                            <input type="email" name="business_email" value="{{ old('business_email') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                        </div>
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Website bisnis</label>
                                            <input type="url" name="business_website" value="{{ old('business_website') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                        </div>
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-700">NPWP / ID pajak</label>
                                            <input type="text" name="business_npwp" value="{{ old('business_npwp') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded-3xl border border-slate-200 p-5">
                                    <h3 class="text-lg font-semibold text-slate-900">Data pelanggan</h3>
                                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Nama pelanggan</label>
                                            <input type="text" name="customer_name" value="{{ old('customer_name') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                        </div>
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Nama perusahaan pelanggan</label>
                                            <input type="text" name="customer_company" value="{{ old('customer_company') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Alamat pelanggan</label>
                                            <textarea name="customer_address" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-3">{{ old('customer_address') }}</textarea>
                                        </div>
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Nomor telepon pelanggan</label>
                                            <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                        </div>
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Email pelanggan</label>
                                            <input type="email" name="customer_email" value="{{ old('customer_email') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded-3xl border border-slate-200 p-5">
                                    <h3 class="text-lg font-semibold text-slate-900">Informasi invoice</h3>
                                    <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Nomor invoice</label>
                                            <input type="text" name="invoice_number" value="{{ old('invoice_number') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                        </div>
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Tanggal invoice</label>
                                            <input type="date" name="invoice_date" value="{{ old('invoice_date') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                        </div>
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Tanggal jatuh tempo</label>
                                            <input type="date" name="due_date" value="{{ old('due_date') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                        </div>
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Nomor PO / referensi</label>
                                            <input type="text" name="po_number" value="{{ old('po_number') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                        </div>
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Mata uang</label>
                                            <input type="text" name="currency" value="{{ old('currency', 'IDR') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                        </div>
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Syarat pembayaran</label>
                                            <input type="text" name="payment_terms" value="{{ old('payment_terms') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded-3xl border border-slate-200 p-5">
                                    <div class="flex flex-wrap items-center justify-between gap-3">
                                        <p class="text-lg font-semibold text-slate-900">Item invoice</p>
                                        <button type="button" data-repeater-add="invoice-items" class="inline-flex h-10 items-center rounded-2xl border border-slate-200 px-4 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:text-slate-900">Tambah item</button>
                                    </div>
                                    <div class="mt-4 space-y-4" data-repeater-list="invoice-items">
                                        @foreach ($invoiceItems as $index => $item)
                                            <div class="rounded-2xl border border-slate-200 p-4" data-repeater-item>
                                                <div class="mb-4 flex items-center justify-between gap-3">
                                                    <p class="text-sm font-semibold text-slate-900" data-repeater-title="Item">Item #{{ $loop->iteration }}</p>
                                                    <button type="button" data-repeater-remove class="inline-flex h-9 items-center rounded-2xl border border-rose-200 px-3 text-xs font-semibold text-rose-600 transition hover:bg-rose-50">Hapus</button>
                                                </div>
                                                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                                                    <input type="text" name="items[{{ $index }}][name]" value="{{ $item['name'] ?? '' }}" placeholder="Nama item / layanan" class="h-12 rounded-2xl border border-slate-200 px-4 lg:col-span-2">
                                                    <input type="text" name="items[{{ $index }}][unit]" value="{{ $item['unit'] ?? '' }}" placeholder="Unit" class="h-12 rounded-2xl border border-slate-200 px-4">
                                                    <input type="number" step="0.01" name="items[{{ $index }}][qty]" value="{{ $item['qty'] ?? '' }}" placeholder="Qty" class="h-12 rounded-2xl border border-slate-200 px-4">
                                                    <input type="number" step="0.01" name="items[{{ $index }}][price]" value="{{ $item['price'] ?? '' }}" placeholder="Harga satuan" class="h-12 rounded-2xl border border-slate-200 px-4 lg:col-span-2">
                                                    <textarea name="items[{{ $index }}][description]" rows="2" placeholder="Deskripsi item (opsional)" class="w-full rounded-2xl border border-slate-200 px-4 py-3 lg:col-span-4">{{ $item['description'] ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <template data-repeater-template="invoice-items">
                                        <div class="rounded-2xl border border-slate-200 p-4" data-repeater-item>
                                            <div class="mb-4 flex items-center justify-between gap-3">
                                                <p class="text-sm font-semibold text-slate-900" data-repeater-title="Item">Item baru</p>
                                                <button type="button" data-repeater-remove class="inline-flex h-9 items-center rounded-2xl border border-rose-200 px-3 text-xs font-semibold text-rose-600 transition hover:bg-rose-50">Hapus</button>
                                            </div>
                                            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                                                <input type="text" name="items[__INDEX__][name]" placeholder="Nama item / layanan" class="h-12 rounded-2xl border border-slate-200 px-4 lg:col-span-2">
                                                <input type="text" name="items[__INDEX__][unit]" placeholder="Unit" class="h-12 rounded-2xl border border-slate-200 px-4">
                                                <input type="number" step="0.01" name="items[__INDEX__][qty]" placeholder="Qty" class="h-12 rounded-2xl border border-slate-200 px-4">
                                                <input type="number" step="0.01" name="items[__INDEX__][price]" placeholder="Harga satuan" class="h-12 rounded-2xl border border-slate-200 px-4 lg:col-span-2">
                                                <textarea name="items[__INDEX__][description]" rows="2" placeholder="Deskripsi item (opsional)" class="w-full rounded-2xl border border-slate-200 px-4 py-3 lg:col-span-4"></textarea>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <div class="rounded-3xl border border-slate-200 p-5">
                                    <h3 class="text-lg font-semibold text-slate-900">Pembayaran & catatan</h3>
                                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Nama bank</label>
                                            <input type="text" name="bank_name" value="{{ old('bank_name') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                        </div>
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Nama pemilik rekening</label>
                                            <input type="text" name="bank_account_name" value="{{ old('bank_account_name') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                        </div>
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Nomor rekening</label>
                                            <input type="text" name="bank_account_number" value="{{ old('bank_account_number') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                        </div>
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Pajak (%)</label>
                                            <input type="number" step="0.01" name="tax" value="{{ old('tax') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                        </div>
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Diskon (%)</label>
                                            <input type="number" step="0.01" name="discount" value="{{ old('discount') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Catatan invoice</label>
                                            <textarea name="notes" rows="4" class="w-full rounded-2xl border border-slate-200 px-4 py-3">{{ old('notes') }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-3">
                                    <button class="h-12 rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white">Preview invoice</button>
                                </div>
                            </form>
                        @break

                        @case('generator-cv-ats')
                            <form method="post" action="{{ route('tools.cv-ats.calculate') }}" class="grid gap-6">
                                @csrf
                                <div class="rounded-3xl border border-slate-200 p-5">
                                    <h3 class="text-lg font-semibold text-slate-900">Profil utama</h3>
                                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                                        <div><label class="mb-2 block text-sm font-medium text-slate-700">Nama lengkap</label><input type="text" name="full_name" value="{{ old('full_name') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4"></div>
                                        <div><label class="mb-2 block text-sm font-medium text-slate-700">Judul profesional</label><input type="text" name="professional_title" value="{{ old('professional_title') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4"></div>
                                        <div><label class="mb-2 block text-sm font-medium text-slate-700">Email</label><input type="email" name="email" value="{{ old('email') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4"></div>
                                        <div><label class="mb-2 block text-sm font-medium text-slate-700">Nomor telepon</label><input type="text" name="phone" value="{{ old('phone') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4"></div>
                                        <div><label class="mb-2 block text-sm font-medium text-slate-700">Kota / domisili</label><input type="text" name="city" value="{{ old('city') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4"></div>
                                        <div><label class="mb-2 block text-sm font-medium text-slate-700">LinkedIn</label><input type="url" name="linkedin" value="{{ old('linkedin') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4"></div>
                                        <div class="sm:col-span-2"><label class="mb-2 block text-sm font-medium text-slate-700">Portfolio / website</label><input type="url" name="portfolio" value="{{ old('portfolio') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4"></div>
                                        <div class="sm:col-span-2"><label class="mb-2 block text-sm font-medium text-slate-700">Ringkasan profesional</label><textarea name="summary" rows="5" class="w-full rounded-2xl border border-slate-200 px-4 py-3">{{ old('summary') }}</textarea></div>
                                    </div>
                                </div>

                                <div class="rounded-3xl border border-slate-200 p-5">
                                    <h3 class="text-lg font-semibold text-slate-900">Skill & kredensial ATS</h3>
                                    <div class="mt-4 grid gap-4">
                                        <div><label class="mb-2 block text-sm font-medium text-slate-700">Keahlian inti</label><textarea name="skills" rows="4" class="w-full rounded-2xl border border-slate-200 px-4 py-3">{{ old('skills') }}</textarea><p class="mt-2 text-xs text-slate-500">Pisahkan dengan koma atau baris baru. Contoh: SQL, Laravel, Project Management.</p></div>
                                        <div><label class="mb-2 block text-sm font-medium text-slate-700">Bahasa</label><textarea name="languages" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-3">{{ old('languages') }}</textarea></div>
                                        <div><label class="mb-2 block text-sm font-medium text-slate-700">Sertifikasi</label><textarea name="certifications" rows="4" class="w-full rounded-2xl border border-slate-200 px-4 py-3">{{ old('certifications') }}</textarea></div>
                                        <div><label class="mb-2 block text-sm font-medium text-slate-700">Pencapaian</label><textarea name="achievements" rows="4" class="w-full rounded-2xl border border-slate-200 px-4 py-3">{{ old('achievements') }}</textarea></div>
                                    </div>
                                </div>

                                <div class="rounded-3xl border border-slate-200 p-5">
                                    <div class="flex flex-wrap items-center justify-between gap-3">
                                        <h3 class="text-lg font-semibold text-slate-900">Pengalaman kerja</h3>
                                        <button type="button" data-repeater-add="work-experiences" class="inline-flex h-10 items-center rounded-2xl border border-slate-200 px-4 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:text-slate-900">Tambah pengalaman</button>
                                    </div>
                                    <div class="mt-4 space-y-4" data-repeater-list="work-experiences">
                                        @foreach ($workExperiences as $index => $experience)
                                            <div class="rounded-2xl border border-slate-200 p-4" data-repeater-item>
                                                <div class="mb-4 flex items-center justify-between gap-3">
                                                    <p class="text-sm font-semibold text-slate-900" data-repeater-title="Pengalaman">Pengalaman #{{ $loop->iteration }}</p>
                                                    <button type="button" data-repeater-remove class="inline-flex h-9 items-center rounded-2xl border border-rose-200 px-3 text-xs font-semibold text-rose-600 transition hover:bg-rose-50">Hapus</button>
                                                </div>
                                                <div class="grid gap-4 sm:grid-cols-2">
                                                    <div><input type="text" name="work_experiences[{{ $index }}][job_title]" value="{{ $experience['job_title'] ?? '' }}" placeholder="Jabatan" class="h-12 w-full rounded-2xl border border-slate-200 px-4"></div>
                                                    <div><input type="text" name="work_experiences[{{ $index }}][company]" value="{{ $experience['company'] ?? '' }}" placeholder="Nama perusahaan" class="h-12 w-full rounded-2xl border border-slate-200 px-4"></div>
                                                    <div><input type="text" name="work_experiences[{{ $index }}][location]" value="{{ $experience['location'] ?? '' }}" placeholder="Lokasi" class="h-12 w-full rounded-2xl border border-slate-200 px-4"></div>
                                                    <div class="grid gap-3 sm:grid-cols-2">
                                                        <input type="date" name="work_experiences[{{ $index }}][start_date]" value="{{ $experience['start_date'] ?? '' }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                                        <input type="date" name="work_experiences[{{ $index }}][end_date]" value="{{ $experience['end_date'] ?? '' }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                                    </div>
                                                    <label class="flex items-center gap-3 text-sm text-slate-700 sm:col-span-2">
                                                        <input type="checkbox" name="work_experiences[{{ $index }}][is_current]" value="1" @checked(! empty($experience['is_current'])) class="rounded border-slate-300">
                                                        Masih bekerja di posisi ini
                                                    </label>
                                                    <div class="sm:col-span-2">
                                                        <textarea name="work_experiences[{{ $index }}][description]" rows="4" placeholder="Tulis poin tanggung jawab dan pencapaian, satu baris satu poin." class="w-full rounded-2xl border border-slate-200 px-4 py-3">{{ $experience['description'] ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <template data-repeater-template="work-experiences">
                                        <div class="rounded-2xl border border-slate-200 p-4" data-repeater-item>
                                            <div class="mb-4 flex items-center justify-between gap-3">
                                                <p class="text-sm font-semibold text-slate-900" data-repeater-title="Pengalaman">Pengalaman baru</p>
                                                <button type="button" data-repeater-remove class="inline-flex h-9 items-center rounded-2xl border border-rose-200 px-3 text-xs font-semibold text-rose-600 transition hover:bg-rose-50">Hapus</button>
                                            </div>
                                            <div class="grid gap-4 sm:grid-cols-2">
                                                <div><input type="text" name="work_experiences[__INDEX__][job_title]" placeholder="Jabatan" class="h-12 w-full rounded-2xl border border-slate-200 px-4"></div>
                                                <div><input type="text" name="work_experiences[__INDEX__][company]" placeholder="Nama perusahaan" class="h-12 w-full rounded-2xl border border-slate-200 px-4"></div>
                                                <div><input type="text" name="work_experiences[__INDEX__][location]" placeholder="Lokasi" class="h-12 w-full rounded-2xl border border-slate-200 px-4"></div>
                                                <div class="grid gap-3 sm:grid-cols-2">
                                                    <input type="date" name="work_experiences[__INDEX__][start_date]" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                                    <input type="date" name="work_experiences[__INDEX__][end_date]" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                                </div>
                                                <label class="flex items-center gap-3 text-sm text-slate-700 sm:col-span-2">
                                                    <input type="checkbox" name="work_experiences[__INDEX__][is_current]" value="1" class="rounded border-slate-300">
                                                    Masih bekerja di posisi ini
                                                </label>
                                                <div class="sm:col-span-2">
                                                    <textarea name="work_experiences[__INDEX__][description]" rows="4" placeholder="Tulis poin tanggung jawab dan pencapaian, satu baris satu poin." class="w-full rounded-2xl border border-slate-200 px-4 py-3"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <div class="rounded-3xl border border-slate-200 p-5">
                                    <div class="flex flex-wrap items-center justify-between gap-3">
                                        <h3 class="text-lg font-semibold text-slate-900">Pendidikan</h3>
                                        <button type="button" data-repeater-add="educations" class="inline-flex h-10 items-center rounded-2xl border border-slate-200 px-4 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:text-slate-900">Tambah pendidikan</button>
                                    </div>
                                    <div class="mt-4 space-y-4" data-repeater-list="educations">
                                        @foreach ($educations as $index => $education)
                                            <div class="rounded-2xl border border-slate-200 p-4" data-repeater-item>
                                                <div class="mb-4 flex items-center justify-between gap-3">
                                                    <p class="text-sm font-semibold text-slate-900" data-repeater-title="Pendidikan">Pendidikan #{{ $loop->iteration }}</p>
                                                    <button type="button" data-repeater-remove class="inline-flex h-9 items-center rounded-2xl border border-rose-200 px-3 text-xs font-semibold text-rose-600 transition hover:bg-rose-50">Hapus</button>
                                                </div>
                                                <div class="grid gap-4 sm:grid-cols-2">
                                                    <div><input type="text" name="educations[{{ $index }}][degree]" value="{{ $education['degree'] ?? '' }}" placeholder="Gelar / jurusan" class="h-12 w-full rounded-2xl border border-slate-200 px-4"></div>
                                                    <div><input type="text" name="educations[{{ $index }}][institution]" value="{{ $education['institution'] ?? '' }}" placeholder="Institusi" class="h-12 w-full rounded-2xl border border-slate-200 px-4"></div>
                                                    <div><input type="text" name="educations[{{ $index }}][location]" value="{{ $education['location'] ?? '' }}" placeholder="Lokasi" class="h-12 w-full rounded-2xl border border-slate-200 px-4"></div>
                                                    <div class="grid gap-3 sm:grid-cols-2">
                                                        <input type="number" name="educations[{{ $index }}][start_year]" value="{{ $education['start_year'] ?? '' }}" placeholder="Tahun mulai" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                                        <input type="number" name="educations[{{ $index }}][end_year]" value="{{ $education['end_year'] ?? '' }}" placeholder="Tahun selesai" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                                    </div>
                                                    <div class="sm:col-span-2">
                                                        <textarea name="educations[{{ $index }}][description]" rows="3" placeholder="Aktivitas, fokus studi, atau penghargaan akademik." class="w-full rounded-2xl border border-slate-200 px-4 py-3">{{ $education['description'] ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <template data-repeater-template="educations">
                                        <div class="rounded-2xl border border-slate-200 p-4" data-repeater-item>
                                            <div class="mb-4 flex items-center justify-between gap-3">
                                                <p class="text-sm font-semibold text-slate-900" data-repeater-title="Pendidikan">Pendidikan baru</p>
                                                <button type="button" data-repeater-remove class="inline-flex h-9 items-center rounded-2xl border border-rose-200 px-3 text-xs font-semibold text-rose-600 transition hover:bg-rose-50">Hapus</button>
                                            </div>
                                            <div class="grid gap-4 sm:grid-cols-2">
                                                <div><input type="text" name="educations[__INDEX__][degree]" placeholder="Gelar / jurusan" class="h-12 w-full rounded-2xl border border-slate-200 px-4"></div>
                                                <div><input type="text" name="educations[__INDEX__][institution]" placeholder="Institusi" class="h-12 w-full rounded-2xl border border-slate-200 px-4"></div>
                                                <div><input type="text" name="educations[__INDEX__][location]" placeholder="Lokasi" class="h-12 w-full rounded-2xl border border-slate-200 px-4"></div>
                                                <div class="grid gap-3 sm:grid-cols-2">
                                                    <input type="number" name="educations[__INDEX__][start_year]" placeholder="Tahun mulai" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                                    <input type="number" name="educations[__INDEX__][end_year]" placeholder="Tahun selesai" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                                </div>
                                                <div class="sm:col-span-2">
                                                    <textarea name="educations[__INDEX__][description]" rows="3" placeholder="Aktivitas, fokus studi, atau penghargaan akademik." class="w-full rounded-2xl border border-slate-200 px-4 py-3"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <div class="flex flex-wrap gap-3">
                                    <button class="h-12 rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white">Preview CV ATS</button>
                                </div>
                            </form>
                        @break

                        @case('generator-surat-izin')
                            <form method="post" action="{{ route('tools.leave-letter.calculate') }}" class="grid gap-4 sm:grid-cols-2">
                                @csrf
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700">Nama</label>
                                    <input type="text" name="name" value="{{ old('name') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700">Jabatan</label>
                                    <input type="text" name="position" value="{{ old('position') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700">Perusahaan</label>
                                    <input type="text" name="company" value="{{ old('company') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700">Tanggal izin</label>
                                    <input type="date" name="leave_date" value="{{ old('leave_date') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="mb-2 block text-sm font-medium text-slate-700">Alasan izin</label>
                                    <textarea name="reason" rows="4" class="w-full rounded-2xl border border-slate-200 px-4 py-3">{{ old('reason') }}</textarea>
                                </div>
                                <button class="h-12 rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white sm:col-span-2">Buat surat izin</button>
                            </form>
                        @break
                    @endswitch

                    @if ($errors->any())
                        <div class="mt-5 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                @if ($result)
                    <div class="mt-6 card-panel p-7" id="hasil">
                        <h2 class="text-xl font-semibold text-slate-900">Hasil</h2>

                        @if ($result['type'] === 'thr')
                            <p class="mt-4 text-sm leading-7 text-slate-600">{{ $result['data']['explanation'] }}</p>
                            <div class="mt-4 rounded-2xl bg-blue-50 p-5">
                                <p class="text-sm text-blue-700">Estimasi THR</p>
                                <p class="mt-2 text-3xl font-semibold text-slate-900">Rp {{ number_format($result['data']['thr_amount'], 0, ',', '.') }}</p>
                            </div>
                        @elseif ($result['type'] === 'salary')
                            <div class="mt-4 grid gap-4 sm:grid-cols-3">
                                <div class="rounded-2xl bg-slate-100 p-4"><p class="text-sm text-slate-500">Pendapatan</p><p class="mt-2 font-semibold">Rp {{ number_format($result['data']['total_income'], 0, ',', '.') }}</p></div>
                                <div class="rounded-2xl bg-slate-100 p-4"><p class="text-sm text-slate-500">Potongan</p><p class="mt-2 font-semibold">Rp {{ number_format($result['data']['total_deductions'], 0, ',', '.') }}</p></div>
                                <div class="rounded-2xl bg-blue-50 p-4"><p class="text-sm text-blue-700">Gaji bersih</p><p class="mt-2 font-semibold">Rp {{ number_format($result['data']['net_salary'], 0, ',', '.') }}</p></div>
                            </div>
                        @elseif ($result['type'] === 'overtime')
                            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                                <div class="rounded-2xl bg-slate-100 p-4"><p class="text-sm text-slate-500">Upah per jam</p><p class="mt-2 font-semibold">Rp {{ number_format($result['data']['hourly_wage'], 0, ',', '.') }}</p></div>
                                <div class="rounded-2xl bg-blue-50 p-4"><p class="text-sm text-blue-700">Total lembur</p><p class="mt-2 font-semibold">Rp {{ number_format($result['data']['overtime_total'], 0, ',', '.') }}</p></div>
                            </div>
                            <p class="mt-4 text-sm leading-7 text-slate-600">{{ $result['data']['note'] }}</p>
                        @elseif ($result['type'] === 'invoice')
                            <div class="mt-5 rounded-3xl border border-slate-200 p-6">
                                <div class="flex flex-wrap items-start justify-between gap-6">
                                    <div>
                                        @if (! empty($result['data']['business_logo_url']))
                                            <img src="{{ $result['data']['business_logo_url'] }}" alt="{{ $result['data']['business_name'] }}" class="mb-4 h-16 w-auto object-contain">
                                        @endif
                                        <p class="text-sm text-slate-500">Invoice dari</p>
                                        <h3 class="text-xl font-semibold text-slate-900">{{ $result['data']['business_name'] }}</h3>
                                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ $result['data']['business_address'] }}</p>
                                        <p class="mt-1 text-sm text-slate-600">{{ $result['data']['business_phone'] }} @if(!empty($result['data']['business_email'])) | {{ $result['data']['business_email'] }} @endif</p>
                                        @if (!empty($result['data']['business_website']) || !empty($result['data']['business_npwp']))
                                            <p class="mt-1 text-sm text-slate-600">
                                                @if (!empty($result['data']['business_website'])) {{ $result['data']['business_website'] }} @endif
                                                @if (!empty($result['data']['business_website']) && !empty($result['data']['business_npwp'])) | @endif
                                                @if (!empty($result['data']['business_npwp'])) NPWP: {{ $result['data']['business_npwp'] }} @endif
                                            </p>
                                        @endif
                                    </div>
                                    <div class="text-right text-sm text-slate-600">
                                        <p>No. {{ $result['data']['invoice_number'] }}</p>
                                        <p>Tanggal: {{ $result['data']['invoice_date'] }}</p>
                                        @if (! empty($result['data']['due_date']))
                                            <p>Jatuh tempo: {{ $result['data']['due_date'] }}</p>
                                        @endif
                                        @if (! empty($result['data']['po_number']))
                                            <p>PO / Ref: {{ $result['data']['po_number'] }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-6 grid gap-4 rounded-2xl bg-slate-50 p-5 md:grid-cols-2">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Tagihan untuk</p>
                                        <p class="mt-2 text-base font-semibold text-slate-900">{{ $result['data']['customer_name'] }}</p>
                                        @if (! empty($result['data']['customer_company']))
                                            <p class="text-sm text-slate-600">{{ $result['data']['customer_company'] }}</p>
                                        @endif
                                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ $result['data']['customer_address'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Kontak pelanggan</p>
                                        <p class="mt-2 text-sm text-slate-600">{{ $result['data']['customer_phone'] ?: '-' }}</p>
                                        <p class="mt-1 text-sm text-slate-600">{{ $result['data']['customer_email'] ?: '-' }}</p>
                                        @if (! empty($result['data']['payment_terms']))
                                            <p class="mt-3 text-sm text-slate-600">Syarat pembayaran: {{ $result['data']['payment_terms'] }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-6 overflow-x-auto">
                                    <table class="min-w-full text-sm">
                                        <thead class="text-left text-slate-500">
                                            <tr>
                                                <th class="pb-3">Item</th>
                                                <th class="pb-3">Unit</th>
                                                <th class="pb-3">Qty</th>
                                                <th class="pb-3">Harga</th>
                                                <th class="pb-3">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            @foreach ($result['data']['items'] as $item)
                                                <tr>
                                                    <td class="py-3">
                                                        <p class="font-medium text-slate-900">{{ $item['name'] }}</p>
                                                        @if (! empty($item['description']))
                                                            <p class="mt-1 text-xs leading-5 text-slate-500">{{ $item['description'] }}</p>
                                                        @endif
                                                    </td>
                                                    <td class="py-3">{{ $item['unit'] ?: '-' }}</td>
                                                    <td class="py-3">{{ $item['qty'] }}</td>
                                                    <td class="py-3">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                                    <td class="py-3">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-6 space-y-2 text-sm text-slate-700">
                                    <p>Subtotal: Rp {{ number_format($result['data']['subtotal'], 0, ',', '.') }}</p>
                                    <p>Pajak: Rp {{ number_format($result['data']['tax_amount'], 0, ',', '.') }}</p>
                                    <p>Diskon: Rp {{ number_format($result['data']['discount_amount'], 0, ',', '.') }}</p>
                                    <p class="text-lg font-semibold text-slate-900">Total: Rp {{ number_format($result['data']['grand_total'], 0, ',', '.') }}</p>
                                </div>

                                @if (! empty($result['data']['bank_name']) || ! empty($result['data']['bank_account_number']) || ! empty($result['data']['notes']))
                                    <div class="mt-6 grid gap-4 rounded-2xl border border-slate-200 p-5 md:grid-cols-2">
                                        <div>
                                            <p class="text-sm font-semibold text-slate-900">Informasi pembayaran</p>
                                            <div class="mt-3 space-y-1 text-sm text-slate-600">
                                                @if (! empty($result['data']['bank_name'])) <p>Bank: {{ $result['data']['bank_name'] }}</p> @endif
                                                @if (! empty($result['data']['bank_account_name'])) <p>Atas nama: {{ $result['data']['bank_account_name'] }}</p> @endif
                                                @if (! empty($result['data']['bank_account_number'])) <p>No. rekening: {{ $result['data']['bank_account_number'] }}</p> @endif
                                            </div>
                                        </div>
                                        @if (! empty($result['data']['notes']))
                                            <div>
                                                <p class="text-sm font-semibold text-slate-900">Catatan</p>
                                                <p class="mt-3 text-sm leading-6 text-slate-600">{{ $result['data']['notes'] }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <form method="post" action="{{ route('tools.invoice.pdf') }}" class="mt-5" enctype="multipart/form-data">
                                    @csrf
                                    @foreach (['business_logo_path', 'business_name', 'business_address', 'business_phone', 'business_email', 'business_website', 'business_npwp', 'customer_name', 'customer_company', 'customer_address', 'customer_phone', 'customer_email', 'invoice_number', 'invoice_date', 'due_date', 'po_number', 'currency', 'tax', 'discount', 'bank_name', 'bank_account_name', 'bank_account_number', 'payment_terms', 'notes'] as $field)
                                        <input type="hidden" name="{{ $field }}" value="{{ $result['data'][$field] }}">
                                    @endforeach
                                    @foreach ($result['data']['items'] as $index => $item)
                                        <input type="hidden" name="items[{{ $index }}][name]" value="{{ $item['name'] }}">
                                        <input type="hidden" name="items[{{ $index }}][description]" value="{{ $item['description'] }}">
                                        <input type="hidden" name="items[{{ $index }}][unit]" value="{{ $item['unit'] }}">
                                        <input type="hidden" name="items[{{ $index }}][qty]" value="{{ $item['qty'] }}">
                                        <input type="hidden" name="items[{{ $index }}][price]" value="{{ $item['price'] }}">
                                    @endforeach
                                    <div class="flex flex-wrap gap-3">
                                        <button class="h-11 rounded-2xl bg-blue-700 px-5 text-sm font-semibold text-white">Download PDF</button>
                                        <button type="button" onclick="window.print()" class="h-11 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Print</button>
                                    </div>
                                </form>
                            </div>
                        @elseif ($result['type'] === 'leave-letter')
                            <div class="mt-5 rounded-3xl border border-slate-200 p-6">
                                <pre id="leave-letter-content" class="whitespace-pre-wrap font-sans text-sm leading-7 text-slate-700">{{ $result['data']['content'] }}</pre>
                            </div>
                            <form method="post" action="{{ route('tools.leave-letter.download') }}" class="mt-5 flex flex-wrap gap-3">
                                @csrf
                                @foreach ($result['data']['payload'] as $field => $value)
                                    <input type="hidden" name="{{ $field }}" value="{{ $value }}">
                                @endforeach
                                <button type="button" data-copy-target="#leave-letter-content" class="h-11 rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white">Copy surat</button>
                                <button class="h-11 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Download TXT</button>
                            </form>
                        @elseif ($result['type'] === 'cv-ats')
                            <div class="mt-5 rounded-3xl border border-slate-200 p-6">
                                <div class="border-b border-slate-200 pb-5">
                                    <h3 class="text-2xl font-semibold text-slate-900">{{ $result['data']['full_name'] }}</h3>
                                    <p class="mt-1 text-base text-blue-700">{{ $result['data']['professional_title'] }}</p>
                                    <p class="mt-3 text-sm text-slate-600">
                                        {{ $result['data']['email'] }} | {{ $result['data']['phone'] }} | {{ $result['data']['city'] }}
                                    </p>
                                    @if (! empty($result['data']['linkedin']) || ! empty($result['data']['portfolio']))
                                        <p class="mt-1 text-sm text-slate-500">
                                            @if (! empty($result['data']['linkedin'])) LinkedIn: {{ $result['data']['linkedin'] }} @endif
                                            @if (! empty($result['data']['linkedin']) && ! empty($result['data']['portfolio'])) | @endif
                                            @if (! empty($result['data']['portfolio'])) Portfolio: {{ $result['data']['portfolio'] }} @endif
                                        </p>
                                    @endif
                                </div>

                                <div class="mt-6 space-y-6">
                                    <div>
                                        <h4 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Ringkasan profesional</h4>
                                        <p class="mt-3 text-sm leading-7 text-slate-700">{{ $result['data']['summary'] }}</p>
                                    </div>

                                    <div>
                                        <h4 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Keahlian inti</h4>
                                        <div class="mt-3 flex flex-wrap gap-2">
                                            @foreach ($result['data']['skills_list'] as $skill)
                                                <span class="rounded-full bg-slate-100 px-3 py-2 text-xs font-medium text-slate-700">{{ $skill }}</span>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div>
                                        <h4 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Pengalaman kerja</h4>
                                        <div class="mt-4 space-y-4">
                                            @foreach ($result['data']['work_experiences_prepared'] as $experience)
                                                <div class="rounded-2xl border border-slate-200 p-4">
                                                    <div class="flex flex-wrap items-start justify-between gap-2">
                                                        <div>
                                                            <p class="font-semibold text-slate-900">{{ $experience['job_title'] }}</p>
                                                            <p class="text-sm text-slate-600">{{ $experience['company'] }}</p>
                                                        </div>
                                                        <p class="text-sm text-slate-500">{{ $experience['period'] }}</p>
                                                    </div>
                                                    @if ($experience['location'])
                                                        <p class="mt-2 text-sm text-slate-500">{{ $experience['location'] }}</p>
                                                    @endif
                                                    <ul class="mt-3 list-disc space-y-2 pl-5 text-sm leading-6 text-slate-700">
                                                        @foreach ($experience['description_points'] as $point)
                                                            <li>{{ $point }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div>
                                        <h4 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Pendidikan</h4>
                                        <div class="mt-4 space-y-4">
                                            @foreach ($result['data']['educations_prepared'] as $education)
                                                <div class="rounded-2xl border border-slate-200 p-4">
                                                    <div class="flex flex-wrap items-start justify-between gap-2">
                                                        <div>
                                                            <p class="font-semibold text-slate-900">{{ $education['degree'] }}</p>
                                                            <p class="text-sm text-slate-600">{{ $education['institution'] }}</p>
                                                        </div>
                                                        <p class="text-sm text-slate-500">{{ $education['period'] }}</p>
                                                    </div>
                                                    @if ($education['location'])
                                                        <p class="mt-2 text-sm text-slate-500">{{ $education['location'] }}</p>
                                                    @endif
                                                    @if ($education['description'])
                                                        <p class="mt-3 text-sm leading-6 text-slate-700">{{ $education['description'] }}</p>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    @if (count($result['data']['certifications_list']))
                                        <div>
                                            <h4 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Sertifikasi</h4>
                                            <ul class="mt-3 list-disc space-y-2 pl-5 text-sm leading-6 text-slate-700">
                                                @foreach ($result['data']['certifications_list'] as $item)
                                                    <li>{{ $item }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    @if (count($result['data']['achievements_list']))
                                        <div>
                                            <h4 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Pencapaian</h4>
                                            <ul class="mt-3 list-disc space-y-2 pl-5 text-sm leading-6 text-slate-700">
                                                @foreach ($result['data']['achievements_list'] as $item)
                                                    <li>{{ $item }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    @if (count($result['data']['languages_list']))
                                        <div>
                                            <h4 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Bahasa</h4>
                                            <div class="mt-3 flex flex-wrap gap-2">
                                                @foreach ($result['data']['languages_list'] as $item)
                                                    <span class="rounded-full bg-blue-50 px-3 py-2 text-xs font-medium text-blue-700">{{ $item }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-6 flex flex-wrap gap-3">
                                    <form method="post" action="{{ route('tools.cv-ats.pdf') }}">
                                        @csrf
                                        @foreach (['full_name', 'professional_title', 'email', 'phone', 'city', 'linkedin', 'portfolio', 'summary', 'skills', 'languages', 'certifications', 'achievements'] as $field)
                                            <input type="hidden" name="{{ $field }}" value="{{ $result['data'][$field] }}">
                                        @endforeach
                                        @foreach ($result['data']['work_experiences'] as $index => $experience)
                                            @foreach ($experience as $field => $value)
                                                <input type="hidden" name="work_experiences[{{ $index }}][{{ $field }}]" value="{{ is_bool($value) ? (int) $value : $value }}">
                                            @endforeach
                                        @endforeach
                                        @foreach ($result['data']['educations'] as $index => $education)
                                            @foreach ($education as $field => $value)
                                                <input type="hidden" name="educations[{{ $index }}][{{ $field }}]" value="{{ $value }}">
                                            @endforeach
                                        @endforeach
                                        <button class="h-11 rounded-2xl bg-blue-700 px-5 text-sm font-semibold text-white">Download PDF</button>
                                    </form>

                                    <form method="post" action="{{ route('tools.cv-ats.word') }}">
                                        @csrf
                                        @foreach (['full_name', 'professional_title', 'email', 'phone', 'city', 'linkedin', 'portfolio', 'summary', 'skills', 'languages', 'certifications', 'achievements'] as $field)
                                            <input type="hidden" name="{{ $field }}" value="{{ $result['data'][$field] }}">
                                        @endforeach
                                        @foreach ($result['data']['work_experiences'] as $index => $experience)
                                            @foreach ($experience as $field => $value)
                                                <input type="hidden" name="work_experiences[{{ $index }}][{{ $field }}]" value="{{ is_bool($value) ? (int) $value : $value }}">
                                            @endforeach
                                        @endforeach
                                        @foreach ($result['data']['educations'] as $index => $education)
                                            @foreach ($education as $field => $value)
                                                <input type="hidden" name="educations[{{ $index }}][{{ $field }}]" value="{{ $value }}">
                                            @endforeach
                                        @endforeach
                                        <button class="h-11 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Download Word</button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="mt-6">
                    <x-ad-slot slot-key="tool_middle" label="Tool Middle" />
                </div>

                <article class="prose-content mt-6 card-panel p-7">
                    {!! $tool->body !!}
                </article>

                @if ($tool->faqs->count())
                    <section class="mt-6 card-panel p-7">
                        <h2 class="text-2xl font-semibold text-slate-900">FAQ</h2>
                        <div class="mt-5 space-y-4">
                            @foreach ($tool->faqs as $faq)
                                <div class="rounded-2xl border border-slate-200 p-5">
                                    <h3 class="text-base font-semibold text-slate-900">{{ $faq->question }}</h3>
                                    <p class="mt-2 text-sm leading-7 text-slate-600">{{ $faq->answer }}</p>
                                </div>
                            @endforeach
                        </div>
                        <x-faq-schema :faqs="$tool->faqs" />
                    </section>
                @endif
            </div>

            <aside class="space-y-6">
                <x-ad-slot slot-key="sidebar" label="Sidebar" />
                <div class="card-panel p-6">
                    <h2 class="text-lg font-semibold text-slate-900">Artikel terkait</h2>
                    <div class="mt-4 space-y-4">
                        @foreach ($relatedPosts as $post)
                            @if (filled($post->slug))
                                <a href="{{ route('blog.show', $post->slug) }}" class="block text-sm leading-6 text-slate-700 hover:text-blue-700">{{ $post->title }}</a>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="card-panel p-6">
                    <h2 class="text-lg font-semibold text-slate-900">Template terkait</h2>
                    <div class="mt-4 space-y-4">
                        @foreach ($relatedTemplates as $template)
                            @if (filled($template->slug))
                                <a href="{{ route('templates.show', $template->slug) }}" class="block text-sm leading-6 text-slate-700 hover:text-blue-700">{{ $template->title }}</a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </aside>
        </div>

        <div class="mt-8">
            <x-ad-slot slot-key="tool_bottom" label="Tool Bottom" />
        </div>
    </section>
@endsection
