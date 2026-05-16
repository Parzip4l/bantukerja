@php
    $selectedTemplateSlug = old('template_slug', $generatorPreview['template_slug'] ?? $generatorTemplates->first()?->slug);
    $temporaryBusinessLogoPath = old('business_logo_path', $generatorPreview['payload']['business_logo_path'] ?? null);
@endphp

<div class="grid gap-6">
    <div class="rounded-3xl border border-slate-200 p-5">
        <h2 class="text-lg font-semibold text-slate-900">Pilih Template Desain Invoice</h2>
        <p class="mt-2 text-sm leading-7 text-slate-600">Pilih desain invoice yang paling cocok untuk kebutuhan bisnis Anda. Preview akan menyesuaikan template yang dipilih.</p>

        <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($generatorTemplates as $templateOption)
                <label class="cursor-pointer">
                    <input type="radio" name="template_slug" value="{{ $templateOption->slug }}" form="invoice-generator-form" class="peer sr-only" @checked($selectedTemplateSlug === $templateOption->slug)>
                    <span class="block h-full rounded-3xl border border-slate-200 bg-white p-4 transition hover:border-blue-200 peer-checked:border-blue-300 peer-checked:bg-blue-50/70 peer-checked:ring-2 peer-checked:ring-blue-100">
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

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr),minmax(320px,0.92fr)]">
        <div class="rounded-3xl border border-slate-200 p-5">
            <h2 class="text-lg font-semibold text-slate-900">Isi Data Invoice</h2>
            <p class="mt-2 text-sm leading-7 text-slate-600">Data yang Anda masukkan hanya digunakan untuk membuat preview dan file unduhan, lalu tidak disimpan permanen oleh BantuKerja.online.</p>

            <form id="invoice-generator-form" method="post" action="{{ route('tools.invoice.preview') }}" enctype="multipart/form-data" class="mt-5 grid gap-6">
                @csrf
                @if ($temporaryBusinessLogoPath)
                    <input type="hidden" name="business_logo_path" value="{{ $temporaryBusinessLogoPath }}">
                @endif

                <div class="rounded-3xl border border-slate-200 p-5">
                    <h3 class="text-base font-semibold text-slate-900">Profil perusahaan</h3>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700">Logo perusahaan</label>
                            <input type="file" name="business_logo" accept="image/*" class="block w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm">
                            @if ($temporaryBusinessLogoPath)
                                <p class="mt-2 text-xs text-slate-500">Logo sementara sudah tersimpan untuk preview dan unduhan sesi ini. Upload ulang hanya jika ingin mengganti.</p>
                            @endif
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
                    <h3 class="text-base font-semibold text-slate-900">Data pelanggan</h3>
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
                    <h3 class="text-base font-semibold text-slate-900">Informasi invoice</h3>
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
                        <p class="text-base font-semibold text-slate-900">Item invoice</p>
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
                                    <input type="text" inputmode="numeric" data-rupiah-input name="items[{{ $index }}][price]" value="{{ $item['price'] ?? '' }}" placeholder="Harga satuan" class="h-12 rounded-2xl border border-slate-200 px-4 lg:col-span-2">
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
                                <input type="text" inputmode="numeric" data-rupiah-input name="items[__INDEX__][price]" placeholder="Harga satuan" class="h-12 rounded-2xl border border-slate-200 px-4 lg:col-span-2">
                                <textarea name="items[__INDEX__][description]" rows="2" placeholder="Deskripsi item (opsional)" class="w-full rounded-2xl border border-slate-200 px-4 py-3 lg:col-span-4"></textarea>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="rounded-3xl border border-slate-200 p-5">
                    <h3 class="text-base font-semibold text-slate-900">Pembayaran & catatan</h3>
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
                    <button class="h-12 rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white">Preview</button>
                    <button formaction="{{ route('tools.invoice.download') }}" class="h-12 rounded-2xl bg-blue-700 px-5 text-sm font-semibold text-white">Download PDF</button>
                    <button formaction="{{ route('tools.invoice.print') }}" formtarget="_blank" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Print</button>
                    <button type="reset" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Reset</button>
                </div>
            </form>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-slate-50/60 p-5">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Preview Invoice</h2>
                    <p class="mt-1 text-sm text-slate-500">Preview berubah sesuai template yang Anda pilih.</p>
                </div>
                @if ($generatorPreview)
                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">{{ $generatorPreview['template']->name }}</span>
                @endif
            </div>

            <div class="mt-5 overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white p-3 shadow-sm">
                @if ($generatorPreview)
                    {!! $generatorPreview['html'] !!}
                @else
                    <div class="flex min-h-[420px] items-center justify-center rounded-[1.25rem] border border-dashed border-slate-200 bg-slate-50 px-6 text-center text-sm leading-7 text-slate-500">
                        Isi data invoice lalu klik Preview untuk melihat hasil dokumen sebelum diunduh.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
