@php
    $selectedTemplateSlug = old('template_slug', $generatorPreview['template_slug'] ?? $generatorTemplates->first()?->slug);
@endphp

<div class="grid gap-6 xl:grid-cols-[minmax(0,0.95fr),minmax(320px,1fr)]">
    <div class="generator-section order-2 xl:order-1">
        <h2 class="text-lg font-semibold text-slate-900">Isi Data Kwitansi</h2>
        <p class="mt-2 text-sm leading-7 text-slate-600">Data pembayaran hanya dipakai untuk preview, print view, dan PDF pada sesi aktif. BantuKerja.online tidak menyimpannya secara permanen.</p>

        <form id="receipt-generator-form" method="post" action="{{ route('tools.receipt.preview') }}" class="mt-5 grid gap-4 sm:grid-cols-2">
            @csrf

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Nomor kwitansi</label>
                <input type="text" name="receipt_number" value="{{ old('receipt_number') }}" class="tool-input" placeholder="KW-2026-001">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Tanggal kwitansi</label>
                <input type="date" name="receipt_date" value="{{ old('receipt_date') }}" class="tool-input">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Sudah terima dari</label>
                <input type="text" name="payer_name" value="{{ old('payer_name') }}" class="tool-input" placeholder="Nama pelanggan / pihak pembayar">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Nama penerima</label>
                <input type="text" name="receiver_name" value="{{ old('receiver_name') }}" class="tool-input" placeholder="Nama bisnis / penerima">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Kota</label>
                <input type="text" name="city" value="{{ old('city') }}" class="tool-input" placeholder="Jakarta">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Metode pembayaran</label>
                <input type="text" name="payment_method" value="{{ old('payment_method') }}" class="tool-input" placeholder="Transfer bank / tunai">
            </div>
            <div class="sm:col-span-2">
                <label class="mb-2 block text-sm font-medium text-slate-700">Nominal pembayaran</label>
                <input type="text" inputmode="numeric" data-rupiah-input name="amount" value="{{ old('amount') }}" class="tool-input" placeholder="1.500.000">
            </div>
            <div class="sm:col-span-2">
                <label class="mb-2 block text-sm font-medium text-slate-700">Untuk pembayaran</label>
                <textarea name="description" rows="4" class="tool-textarea" placeholder="Contoh: Pelunasan jasa pembukuan bulan April 2026.">{{ old('description') }}</textarea>
            </div>
            <div class="sm:col-span-2">
                <label class="mb-2 block text-sm font-medium text-slate-700">Catatan tambahan</label>
                <textarea name="notes" rows="3" class="tool-textarea" placeholder="Opsional, misalnya pembayaran tahap kedua atau referensi transaksi.">{{ old('notes') }}</textarea>
            </div>

            <div class="generator-actions sm:col-span-2">
                <button class="h-12 rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white">Preview</button>
                <button formaction="{{ route('tools.receipt.download') }}" class="h-12 rounded-2xl bg-blue-700 px-5 text-sm font-semibold text-white">Download PDF</button>
                <button formaction="{{ route('tools.receipt.print') }}" formtarget="_blank" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Print</button>
                <button type="reset" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Reset</button>
            </div>
        </form>
    </div>

    <div class="generator-section order-1 self-start bg-slate-50/60 xl:order-2 xl:sticky xl:top-24">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Preview Kwitansi</h2>
                <p class="mt-1 text-sm text-slate-500">Pilih template lalu cek tata letaknya sebelum mengunduh.</p>
            </div>
            @if ($generatorPreview)
                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">{{ $generatorPreview['template']->name }}</span>
            @endif
        </div>

        <div class="mt-5 rounded-[1.75rem] border border-slate-200 bg-white p-4">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">Pilih Template Desain Kwitansi</h3>
                    <p class="mt-1 text-xs leading-6 text-slate-500">Gunakan template yang paling cocok untuk transaksi kas, invoice ringan, atau bukti pembayaran proyek.</p>
                </div>
                <a href="#receipt-generator-form" class="inline-flex h-10 items-center rounded-2xl border border-slate-200 px-4 text-xs font-semibold text-slate-700 xl:hidden">Isi data</a>
            </div>

            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                @foreach ($generatorTemplates as $templateOption)
                    <label class="cursor-pointer">
                        <input type="radio" name="template_slug" value="{{ $templateOption->slug }}" form="receipt-generator-form" class="peer sr-only" @checked($selectedTemplateSlug === $templateOption->slug)>
                        <span class="block rounded-3xl border border-slate-200 bg-white p-4 transition hover:border-blue-200 peer-checked:border-blue-300 peer-checked:bg-blue-50/70 peer-checked:ring-2 peer-checked:ring-blue-100">
                            <span class="flex items-start justify-between gap-3">
                                <span>
                                    <span class="block text-sm font-semibold text-slate-900">{{ $templateOption->name }}</span>
                                    <span class="mt-2 block text-xs leading-6 text-slate-500">{{ $templateOption->description }}</span>
                                </span>
                                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-semibold text-emerald-700">Gratis</span>
                            </span>
                        </span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="mt-5 overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white p-3 shadow-sm">
            @if ($generatorPreview)
                {!! $generatorPreview['html'] !!}
            @else
                <div class="flex min-h-[420px] items-center justify-center rounded-[1.25rem] border border-dashed border-slate-200 bg-slate-50 px-6 text-center text-sm leading-7 text-slate-500">
                    Isi detail kwitansi lalu klik Preview untuk melihat hasil dokumen sebelum diunduh.
                </div>
            @endif
        </div>
    </div>
</div>
