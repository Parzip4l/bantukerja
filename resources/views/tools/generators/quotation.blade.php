@php
    $selectedTemplateSlug = old('template_slug', $generatorPreview['template_slug'] ?? $generatorTemplates->first()?->slug);
    $temporaryVendorLogoPath = old('vendor_logo_path', $generatorPreview['payload']['vendor_logo_path'] ?? null);
    $quotationItems = old('items', [[
        'name' => '',
        'description' => '',
        'qty' => '',
        'unit' => '',
        'price' => '',
        'discount' => '',
    ]]);
@endphp

<div class="grid gap-6 xl:grid-cols-[minmax(0,1fr),minmax(340px,0.95fr)]">
    <div class="order-2 rounded-3xl border border-slate-200 p-5 xl:order-1">
        <h2 class="text-lg font-semibold text-slate-900">Isi Data Quotation</h2>
        <p class="mt-2 text-sm leading-7 text-slate-600">Tool ini cocok untuk penawaran jasa freelance, vendor, UMKM, sampai proposal pekerjaan proyek kecil yang ingin terlihat rapi dan profesional.</p>

        <form id="quotation-generator-form" method="post" action="{{ route('tools.quotation.preview') }}" enctype="multipart/form-data" class="mt-5 grid gap-6">
            @csrf
            @if ($temporaryVendorLogoPath)
                <input type="hidden" name="vendor_logo_path" value="{{ $temporaryVendorLogoPath }}">
            @endif

            <div class="rounded-3xl border border-slate-200 p-5">
                <h3 class="text-base font-semibold text-slate-900">Profil vendor</h3>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Logo vendor</label>
                        <input type="file" name="vendor_logo" accept="image/*" class="block w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm">
                        @if ($temporaryVendorLogoPath)
                            <p class="mt-2 text-xs text-slate-500">Logo sementara sudah tersimpan untuk sesi ini.</p>
                        @endif
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Nama perusahaan / vendor</label>
                        <input type="text" name="vendor_name" value="{{ old('vendor_name') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Studio Maju Digital">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700">Alamat vendor</label>
                        <textarea name="vendor_address" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Jl. Contoh No. 12, Bandung">{{ old('vendor_address') }}</textarea>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Email vendor</label>
                        <input type="email" name="vendor_email" value="{{ old('vendor_email') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="hello@studio.com">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Nomor HP / WhatsApp</label>
                        <input type="text" name="vendor_phone" value="{{ old('vendor_phone') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="0812xxxxxxx">
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 p-5">
                <h3 class="text-base font-semibold text-slate-900">Informasi client & quotation</h3>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Nama client / perusahaan</label>
                        <input type="text" name="client_name" value="{{ old('client_name') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="PT Mapan Sejahtera">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Nomor quotation</label>
                        <input type="text" name="quotation_number" value="{{ old('quotation_number', \App\Support\DocumentFormatter::generateDocumentNumber('QTN')) }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700">Alamat client</label>
                        <textarea name="client_address" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Opsional, isi jika ingin quotation lebih formal">{{ old('client_address') }}</textarea>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Tanggal quotation</label>
                        <input type="date" name="quotation_date" value="{{ old('quotation_date', now()->toDateString()) }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Masa berlaku penawaran</label>
                        <select name="validity_days" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                            <option value="7" @selected(old('validity_days') == 7)>7 hari</option>
                            <option value="14" @selected(old('validity_days', 14) == 14)>14 hari</option>
                            <option value="30" @selected(old('validity_days') == 30)>30 hari</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700">Judul penawaran</label>
                        <input type="text" name="quotation_title" value="{{ old('quotation_title') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Penawaran Jasa Pembuatan Website Company Profile">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700">Deskripsi singkat pekerjaan</label>
                        <textarea name="project_description" rows="4" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Jelaskan gambaran pekerjaan, deliverables, dan konteks project secara singkat.">{{ old('project_description') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 p-5">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <p class="text-base font-semibold text-slate-900">Item pekerjaan / penawaran</p>
                    <button type="button" data-repeater-add="quotation-items" class="inline-flex h-10 items-center rounded-2xl border border-slate-200 px-4 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:text-slate-900">Tambah item</button>
                </div>
                <div class="mt-4 space-y-4" data-repeater-list="quotation-items">
                    @foreach ($quotationItems as $index => $item)
                        <div class="rounded-2xl border border-slate-200 p-4" data-repeater-item>
                            <div class="mb-4 flex items-center justify-between gap-3">
                                <p class="text-sm font-semibold text-slate-900" data-repeater-title="Item">Item #{{ $loop->iteration }}</p>
                                <button type="button" data-repeater-remove class="inline-flex h-9 items-center rounded-2xl border border-rose-200 px-3 text-xs font-semibold text-rose-600 transition hover:bg-rose-50">Hapus</button>
                            </div>
                            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-6">
                                <input type="text" name="items[{{ $index }}][name]" value="{{ $item['name'] ?? '' }}" placeholder="Nama layanan / barang" class="h-12 rounded-2xl border border-slate-200 px-4 lg:col-span-3">
                                <input type="number" step="0.01" name="items[{{ $index }}][qty]" value="{{ $item['qty'] ?? '' }}" placeholder="Qty" class="h-12 rounded-2xl border border-slate-200 px-4">
                                <input type="text" name="items[{{ $index }}][unit]" value="{{ $item['unit'] ?? '' }}" placeholder="Satuan" class="h-12 rounded-2xl border border-slate-200 px-4">
                                <input type="text" inputmode="numeric" data-rupiah-input name="items[{{ $index }}][price]" value="{{ $item['price'] ?? '' }}" placeholder="Harga satuan" class="h-12 rounded-2xl border border-slate-200 px-4">
                                <textarea name="items[{{ $index }}][description]" rows="2" placeholder="Deskripsi item" class="w-full rounded-2xl border border-slate-200 px-4 py-3 lg:col-span-4">{{ $item['description'] ?? '' }}</textarea>
                                <input type="text" inputmode="numeric" data-rupiah-input name="items[{{ $index }}][discount]" value="{{ $item['discount'] ?? '' }}" placeholder="Diskon item (opsional)" class="h-12 rounded-2xl border border-slate-200 px-4 lg:col-span-2">
                            </div>
                        </div>
                    @endforeach
                </div>
                <template data-repeater-template="quotation-items">
                    <div class="rounded-2xl border border-slate-200 p-4" data-repeater-item>
                        <div class="mb-4 flex items-center justify-between gap-3">
                            <p class="text-sm font-semibold text-slate-900" data-repeater-title="Item">Item baru</p>
                            <button type="button" data-repeater-remove class="inline-flex h-9 items-center rounded-2xl border border-rose-200 px-3 text-xs font-semibold text-rose-600 transition hover:bg-rose-50">Hapus</button>
                        </div>
                        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-6">
                            <input type="text" name="items[__INDEX__][name]" placeholder="Nama layanan / barang" class="h-12 rounded-2xl border border-slate-200 px-4 lg:col-span-3">
                            <input type="number" step="0.01" name="items[__INDEX__][qty]" placeholder="Qty" class="h-12 rounded-2xl border border-slate-200 px-4">
                            <input type="text" name="items[__INDEX__][unit]" placeholder="Satuan" class="h-12 rounded-2xl border border-slate-200 px-4">
                            <input type="text" inputmode="numeric" data-rupiah-input name="items[__INDEX__][price]" placeholder="Harga satuan" class="h-12 rounded-2xl border border-slate-200 px-4">
                            <textarea name="items[__INDEX__][description]" rows="2" placeholder="Deskripsi item" class="w-full rounded-2xl border border-slate-200 px-4 py-3 lg:col-span-4"></textarea>
                            <input type="text" inputmode="numeric" data-rupiah-input name="items[__INDEX__][discount]" placeholder="Diskon item (opsional)" class="h-12 rounded-2xl border border-slate-200 px-4 lg:col-span-2">
                        </div>
                    </div>
                </template>
            </div>

            <div class="rounded-3xl border border-slate-200 p-5">
                <h3 class="text-base font-semibold text-slate-900">Pembayaran, terms, dan penanggung jawab</h3>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Pajak / PPN (%)</label>
                        <input type="number" step="0.01" name="tax_percentage" value="{{ old('tax_percentage') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="11">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Biaya tambahan</label>
                        <input type="text" inputmode="numeric" data-rupiah-input name="additional_fee" value="{{ old('additional_fee') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="0">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Termin pembayaran</label>
                        <select name="payment_terms" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                            <option value="100-awal" @selected(old('payment_terms') === '100-awal')>100% di awal</option>
                            <option value="dp50-pelunasan50" @selected(old('payment_terms', 'dp50-pelunasan50') === 'dp50-pelunasan50')>DP 50% / pelunasan 50%</option>
                            <option value="dp30-pelunasan70" @selected(old('payment_terms') === 'dp30-pelunasan70')>DP 30% / pelunasan 70%</option>
                            <option value="custom" @selected(old('payment_terms') === 'custom')>Custom</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Termin custom</label>
                        <input type="text" name="payment_terms_custom" value="{{ old('payment_terms_custom') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Contoh: DP 40%, tahap 2 30%, final 30%">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Nama penanggung jawab</label>
                        <input type="text" name="person_in_charge" value="{{ old('person_in_charge') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Raka Aditya">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Jabatan penanggung jawab</label>
                        <input type="text" name="person_in_charge_title" value="{{ old('person_in_charge_title') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Business Development Lead">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700">Catatan tambahan</label>
                        <textarea name="additional_notes" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Opsional, misalnya revisi 2x, support 30 hari, atau kebutuhan kickoff meeting.">{{ old('additional_notes') }}</textarea>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700">Syarat dan ketentuan</label>
                        <textarea name="terms_and_conditions" rows="4" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Contoh: Harga belum termasuk domain dan hosting. Revisi desain maksimal 2 kali. Timeline dimulai setelah DP diterima.">{{ old('terms_and_conditions') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <button class="h-12 rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white">Generate quotation</button>
                <button formaction="{{ route('tools.quotation.pdf') }}" class="h-12 rounded-2xl bg-blue-700 px-5 text-sm font-semibold text-white">Download PDF</button>
                <button formaction="{{ route('tools.quotation.print') }}" formtarget="_blank" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Print</button>
                <button formaction="{{ route('tools.quotation.download') }}" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Copy ringkasan TXT</button>
                <button type="reset" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Reset form</button>
            </div>
        </form>

        @if ($generatorPreview && $generatorPreview['copy_text'])
            <pre id="quotation-copy-content" class="sr-only">{{ $generatorPreview['copy_text'] }}</pre>
            <button type="button" data-copy-target="#quotation-copy-content" class="mt-4 inline-flex h-11 items-center rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Copy ringkasan quotation</button>
        @endif
    </div>

    <div id="quotation-preview-panel" class="order-1 self-start rounded-3xl border border-slate-200 bg-slate-50/60 p-5 xl:order-2 xl:sticky xl:top-24">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Preview Quotation</h2>
                <p class="mt-1 text-sm text-slate-500">Bandingkan template dan total penawaran di panel yang sama tanpa perlu scroll jauh.</p>
            </div>
            @if ($generatorPreview)
                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">{{ $generatorPreview['template']->name }}</span>
            @endif
        </div>

        <div class="mt-5 rounded-[1.75rem] border border-slate-200 bg-white p-4">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">Pilih Template Quotation</h3>
                    <p class="mt-1 text-xs leading-6 text-slate-500">Siapkan struktur multi-template sejak awal untuk kebutuhan jasa, vendor, maupun UMKM.</p>
                </div>
                <a href="#quotation-generator-form" class="inline-flex h-10 items-center rounded-2xl border border-slate-200 px-4 text-xs font-semibold text-slate-700 xl:hidden">Isi data</a>
            </div>

            <div class="mt-4 grid gap-3">
                @foreach ($generatorTemplates as $templateOption)
                    <label class="cursor-pointer">
                        <input type="radio" name="template_slug" value="{{ $templateOption->slug }}" form="quotation-generator-form" class="peer sr-only" @checked($selectedTemplateSlug === $templateOption->slug)>
                        <span class="block rounded-3xl border border-slate-200 bg-white p-4 transition hover:border-blue-200 peer-checked:border-blue-300 peer-checked:bg-blue-50/70 peer-checked:ring-2 peer-checked:ring-blue-100">
                            <span class="block text-sm font-semibold text-slate-900">{{ $templateOption->name }}</span>
                            <span class="mt-2 block text-xs leading-6 text-slate-500">{{ $templateOption->description }}</span>
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
                    Isi profil vendor, item pekerjaan, dan terms pembayaran untuk melihat quotation bisnis yang siap dibagikan.
                </div>
            @endif
        </div>

        <div class="mt-5 rounded-3xl border border-amber-100 bg-amber-50 p-4 text-sm leading-7 text-amber-900">
            Butuh alur quotation, invoice, dan aplikasi bisnis custom yang lebih rapi? Gunakan area ini sebagai CTA konsultasi bisnis di tahap berikutnya. Sementara itu, Anda juga bisa lanjut ke <a href="{{ route('tools.show', 'generator-invoice') }}" class="font-semibold underline decoration-amber-300 underline-offset-4">Generator Invoice</a> atau <a href="{{ route('tools.show', 'generator-kwitansi') }}" class="font-semibold underline decoration-amber-300 underline-offset-4">Generator Kwitansi</a>.
        </div>
    </div>
</div>
