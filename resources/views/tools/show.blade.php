@extends('layouts.app')

@php
    $result = session('tool_result');
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
                            <form method="post" action="{{ route('tools.invoice.calculate') }}" class="grid gap-4">
                                @csrf
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-slate-700">Nama bisnis</label>
                                        <input type="text" name="business_name" value="{{ old('business_name') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-slate-700">Nama pelanggan</label>
                                        <input type="text" name="customer_name" value="{{ old('customer_name') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-slate-700">Nomor invoice</label>
                                        <input type="text" name="invoice_number" value="{{ old('invoice_number') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-slate-700">Tanggal</label>
                                        <input type="date" name="invoice_date" value="{{ old('invoice_date') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                    </div>
                                </div>

                                <div class="rounded-2xl border border-slate-200 p-4">
                                    <p class="text-sm font-medium text-slate-900">Item invoice</p>
                                    <div class="mt-4 space-y-3">
                                        @for ($i = 0; $i < 3; $i++)
                                            <div class="grid gap-3 sm:grid-cols-[1fr,120px,160px]">
                                                <input type="text" name="items[{{ $i }}][name]" value="{{ old("items.$i.name") }}" placeholder="Nama item" class="h-12 rounded-2xl border border-slate-200 px-4">
                                                <input type="number" step="0.01" name="items[{{ $i }}][qty]" value="{{ old("items.$i.qty") }}" placeholder="Qty" class="h-12 rounded-2xl border border-slate-200 px-4">
                                                <input type="number" step="0.01" name="items[{{ $i }}][price]" value="{{ old("items.$i.price") }}" placeholder="Harga" class="h-12 rounded-2xl border border-slate-200 px-4">
                                            </div>
                                        @endfor
                                    </div>
                                </div>

                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-slate-700">Pajak (%)</label>
                                        <input type="number" step="0.01" name="tax" value="{{ old('tax') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-slate-700">Diskon (%)</label>
                                        <input type="number" step="0.01" name="discount" value="{{ old('discount') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-3">
                                    <button class="h-12 rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white">Preview invoice</button>
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
                                <div class="flex flex-wrap items-start justify-between gap-4">
                                    <div>
                                        <p class="text-sm text-slate-500">Invoice dari</p>
                                        <h3 class="text-xl font-semibold text-slate-900">{{ $result['data']['business_name'] }}</h3>
                                    </div>
                                    <div class="text-right text-sm text-slate-600">
                                        <p>No. {{ $result['data']['invoice_number'] }}</p>
                                        <p>{{ $result['data']['invoice_date'] }}</p>
                                    </div>
                                </div>
                                <p class="mt-4 text-sm text-slate-600">Pelanggan: {{ $result['data']['customer_name'] }}</p>

                                <div class="mt-6 overflow-x-auto">
                                    <table class="min-w-full text-sm">
                                        <thead class="text-left text-slate-500">
                                            <tr>
                                                <th class="pb-3">Item</th>
                                                <th class="pb-3">Qty</th>
                                                <th class="pb-3">Harga</th>
                                                <th class="pb-3">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            @foreach ($result['data']['items'] as $item)
                                                <tr>
                                                    <td class="py-3">{{ $item['name'] }}</td>
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

                                <form method="post" action="{{ route('tools.invoice.pdf') }}" class="mt-5">
                                    @csrf
                                    @foreach (['business_name', 'customer_name', 'invoice_number', 'invoice_date', 'tax', 'discount'] as $field)
                                        <input type="hidden" name="{{ $field }}" value="{{ $result['data'][$field] }}">
                                    @endforeach
                                    @foreach ($result['data']['items'] as $index => $item)
                                        <input type="hidden" name="items[{{ $index }}][name]" value="{{ $item['name'] }}">
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
                            <a href="{{ route('blog.show', $post->slug) }}" class="block text-sm leading-6 text-slate-700 hover:text-blue-700">{{ $post->title }}</a>
                        @endforeach
                    </div>
                </div>
                <div class="card-panel p-6">
                    <h2 class="text-lg font-semibold text-slate-900">Template terkait</h2>
                    <div class="mt-4 space-y-4">
                        @foreach ($relatedTemplates as $template)
                            <a href="{{ route('templates.show', $template->slug) }}" class="block text-sm leading-6 text-slate-700 hover:text-blue-700">{{ $template->title }}</a>
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
