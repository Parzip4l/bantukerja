@php
    $selectedTemplateSlug = old('template_slug', $generatorPreview['template_slug'] ?? $generatorTemplates->first()?->slug ?? 'daily-work-report-standard');
    $tasks = old('tasks', [['task_name' => '', 'description' => '', 'status' => 'selesai', 'progress' => '', 'output' => '']]);
    $issues = old('issues', [['issue' => '', 'impact' => '', 'temporary_action' => '']]);
    $plans = old('plans', [['plan_task' => '', 'priority' => 'sedang']]);
@endphp

<div class="grid gap-6 xl:grid-cols-[minmax(0,0.96fr),minmax(320px,1fr)]">
    <div class="order-2 rounded-3xl border border-slate-200 p-5 xl:order-1">
        <h2 class="text-lg font-semibold text-slate-900">Susun Laporan Kerja Harian</h2>
        <p class="mt-2 text-sm leading-7 text-slate-600">Tool ini membantu karyawan, magang, freelancer, admin, hingga tim operasional membuat laporan kerja harian yang rapi untuk chat, email, atau dokumen formal.</p>

        <form id="daily-work-report-form" method="post" action="{{ route('tools.daily-work-report.preview') }}" class="mt-5 grid gap-5 sm:grid-cols-2" data-analytics-generate-form data-analytics-event="daily_report_generate" data-tool-name="daily_work_report">
            @csrf
            <input type="hidden" name="template_slug" value="{{ $selectedTemplateSlug }}">
            <div><label class="mb-2 block text-sm font-medium text-slate-700">Nama pembuat laporan</label><input type="text" name="author_name" value="{{ old('author_name') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Muhamad Sobirin"></div>
            <div><label class="mb-2 block text-sm font-medium text-slate-700">Jabatan / posisi</label><input type="text" name="position" value="{{ old('position') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Admin Operasional"></div>
            <div><label class="mb-2 block text-sm font-medium text-slate-700">Divisi / tim</label><input type="text" name="division" value="{{ old('division') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Operasional"></div>
            <div><label class="mb-2 block text-sm font-medium text-slate-700">Atasan / penerima laporan</label><input type="text" name="recipient_name" value="{{ old('recipient_name') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Ibu Rina"></div>
            <div><label class="mb-2 block text-sm font-medium text-slate-700">Tanggal laporan</label><input type="date" name="report_date" value="{{ old('report_date', now()->toDateString()) }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4"></div>
            <div><label class="mb-2 block text-sm font-medium text-slate-700">Tipe laporan</label><select name="report_type" class="h-12 w-full rounded-2xl border border-slate-200 px-4">@foreach (['Laporan Harian Karyawan', 'Laporan Harian Magang', 'Laporan Harian Freelancer', 'Laporan Harian Tim Operasional', 'Laporan Harian IT/Helpdesk', 'Laporan Harian Sales', 'Laporan Harian Customer Service'] as $option)<option value="{{ $option }}" @selected(old('report_type') === $option)>{{ $option }}</option>@endforeach</select></div>
            <div><label class="mb-2 block text-sm font-medium text-slate-700">Format output</label><select name="output_format" class="h-12 w-full rounded-2xl border border-slate-200 px-4">@foreach (['ringkas' => 'Ringkas', 'detail' => 'Detail', 'whatsapp-chat' => 'Format WhatsApp / Chat', 'email' => 'Format Email', 'dokumen-formal' => 'Format Dokumen Formal'] as $value => $label)<option value="{{ $value }}" @selected(old('output_format') === $value)>{{ $label }}</option>@endforeach</select></div>
            <div><label class="mb-2 block text-sm font-medium text-slate-700">Gaya bahasa</label><select name="language_style" class="h-12 w-full rounded-2xl border border-slate-200 px-4">@foreach (['formal' => 'Formal', 'natural-profesional' => 'Natural profesional', 'singkat' => 'Singkat'] as $value => $label)<option value="{{ $value }}" @selected(old('language_style') === $value)>{{ $label }}</option>@endforeach</select></div>

            <div class="sm:col-span-2 rounded-3xl border border-slate-200 p-5">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h3 class="text-base font-semibold text-slate-900">Daftar pekerjaan hari ini</h3>
                    <button type="button" data-repeater-add="daily-report-tasks" class="inline-flex h-10 items-center rounded-2xl border border-slate-200 px-4 text-sm font-semibold text-slate-700">Tambah tugas</button>
                </div>
                <div class="mt-4 space-y-4" data-repeater-list="daily-report-tasks">
                    @foreach ($tasks as $index => $task)
                        <div class="rounded-2xl border border-slate-200 p-4" data-repeater-item>
                            <div class="mb-4 flex items-center justify-between gap-3"><p class="text-sm font-semibold text-slate-900" data-repeater-title="Tugas">Tugas #{{ $loop->iteration }}</p><button type="button" data-repeater-remove class="inline-flex h-9 items-center rounded-2xl border border-rose-200 px-3 text-xs font-semibold text-rose-600">Hapus</button></div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div><input type="text" name="tasks[{{ $index }}][task_name]" value="{{ $task['task_name'] ?? '' }}" placeholder="Nama tugas" class="h-12 w-full rounded-2xl border border-slate-200 px-4"></div>
                                <div><select name="tasks[{{ $index }}][status]" class="h-12 w-full rounded-2xl border border-slate-200 px-4"><option value="selesai" @selected(($task['status'] ?? '') === 'selesai')>Selesai</option><option value="dalam-proses" @selected(($task['status'] ?? '') === 'dalam-proses')>Dalam proses</option><option value="tertunda" @selected(($task['status'] ?? '') === 'tertunda')>Tertunda</option></select></div>
                                <div class="sm:col-span-2"><textarea name="tasks[{{ $index }}][description]" rows="3" placeholder="Deskripsi singkat tugas" class="w-full rounded-2xl border border-slate-200 px-4 py-3">{{ $task['description'] ?? '' }}</textarea></div>
                                <div><input type="number" name="tasks[{{ $index }}][progress]" value="{{ $task['progress'] ?? '' }}" min="0" max="100" placeholder="Progress %" class="h-12 w-full rounded-2xl border border-slate-200 px-4"></div>
                                <div><input type="text" name="tasks[{{ $index }}][output]" value="{{ $task['output'] ?? '' }}" placeholder="Output / hasil pekerjaan" class="h-12 w-full rounded-2xl border border-slate-200 px-4"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <template data-repeater-template="daily-report-tasks">
                    <div class="rounded-2xl border border-slate-200 p-4" data-repeater-item>
                        <div class="mb-4 flex items-center justify-between gap-3"><p class="text-sm font-semibold text-slate-900" data-repeater-title="Tugas">Tugas baru</p><button type="button" data-repeater-remove class="inline-flex h-9 items-center rounded-2xl border border-rose-200 px-3 text-xs font-semibold text-rose-600">Hapus</button></div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div><input type="text" name="tasks[__INDEX__][task_name]" placeholder="Nama tugas" class="h-12 w-full rounded-2xl border border-slate-200 px-4"></div>
                            <div><select name="tasks[__INDEX__][status]" class="h-12 w-full rounded-2xl border border-slate-200 px-4"><option value="selesai">Selesai</option><option value="dalam-proses">Dalam proses</option><option value="tertunda">Tertunda</option></select></div>
                            <div class="sm:col-span-2"><textarea name="tasks[__INDEX__][description]" rows="3" placeholder="Deskripsi singkat tugas" class="w-full rounded-2xl border border-slate-200 px-4 py-3"></textarea></div>
                            <div><input type="number" name="tasks[__INDEX__][progress]" min="0" max="100" placeholder="Progress %" class="h-12 w-full rounded-2xl border border-slate-200 px-4"></div>
                            <div><input type="text" name="tasks[__INDEX__][output]" placeholder="Output / hasil pekerjaan" class="h-12 w-full rounded-2xl border border-slate-200 px-4"></div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="sm:col-span-2 rounded-3xl border border-slate-200 p-5">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h3 class="text-base font-semibold text-slate-900">Kendala / hambatan</h3>
                    <button type="button" data-repeater-add="daily-report-issues" class="inline-flex h-10 items-center rounded-2xl border border-slate-200 px-4 text-sm font-semibold text-slate-700">Tambah kendala</button>
                </div>
                <div class="mt-4 space-y-4" data-repeater-list="daily-report-issues">
                    @foreach ($issues as $index => $issue)
                        <div class="rounded-2xl border border-slate-200 p-4" data-repeater-item>
                            <div class="mb-4 flex items-center justify-between gap-3"><p class="text-sm font-semibold text-slate-900" data-repeater-title="Kendala">Kendala #{{ $loop->iteration }}</p><button type="button" data-repeater-remove class="inline-flex h-9 items-center rounded-2xl border border-rose-200 px-3 text-xs font-semibold text-rose-600">Hapus</button></div>
                            <div class="grid gap-4">
                                <input type="text" name="issues[{{ $index }}][issue]" value="{{ $issue['issue'] ?? '' }}" placeholder="Kendala" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                <input type="text" name="issues[{{ $index }}][impact]" value="{{ $issue['impact'] ?? '' }}" placeholder="Dampak" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                <input type="text" name="issues[{{ $index }}][temporary_action]" value="{{ $issue['temporary_action'] ?? '' }}" placeholder="Tindakan sementara" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                            </div>
                        </div>
                    @endforeach
                </div>
                <template data-repeater-template="daily-report-issues">
                    <div class="rounded-2xl border border-slate-200 p-4" data-repeater-item>
                        <div class="mb-4 flex items-center justify-between gap-3"><p class="text-sm font-semibold text-slate-900" data-repeater-title="Kendala">Kendala baru</p><button type="button" data-repeater-remove class="inline-flex h-9 items-center rounded-2xl border border-rose-200 px-3 text-xs font-semibold text-rose-600">Hapus</button></div>
                        <div class="grid gap-4">
                            <input type="text" name="issues[__INDEX__][issue]" placeholder="Kendala" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                            <input type="text" name="issues[__INDEX__][impact]" placeholder="Dampak" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                            <input type="text" name="issues[__INDEX__][temporary_action]" placeholder="Tindakan sementara" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                        </div>
                    </div>
                </template>
            </div>

            <div class="sm:col-span-2 rounded-3xl border border-slate-200 p-5">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h3 class="text-base font-semibold text-slate-900">Rencana kerja selanjutnya</h3>
                    <button type="button" data-repeater-add="daily-report-plans" class="inline-flex h-10 items-center rounded-2xl border border-slate-200 px-4 text-sm font-semibold text-slate-700">Tambah rencana</button>
                </div>
                <div class="mt-4 space-y-4" data-repeater-list="daily-report-plans">
                    @foreach ($plans as $index => $plan)
                        <div class="rounded-2xl border border-slate-200 p-4" data-repeater-item>
                            <div class="mb-4 flex items-center justify-between gap-3"><p class="text-sm font-semibold text-slate-900" data-repeater-title="Rencana">Rencana #{{ $loop->iteration }}</p><button type="button" data-repeater-remove class="inline-flex h-9 items-center rounded-2xl border border-rose-200 px-3 text-xs font-semibold text-rose-600">Hapus</button></div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <input type="text" name="plans[{{ $index }}][plan_task]" value="{{ $plan['plan_task'] ?? '' }}" placeholder="Rencana pekerjaan" class="h-12 w-full rounded-2xl border border-slate-200 px-4 sm:col-span-2">
                                <select name="plans[{{ $index }}][priority]" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                                    <option value="tinggi" @selected(($plan['priority'] ?? '') === 'tinggi')>Tinggi</option>
                                    <option value="sedang" @selected(($plan['priority'] ?? 'sedang') === 'sedang')>Sedang</option>
                                    <option value="rendah" @selected(($plan['priority'] ?? '') === 'rendah')>Rendah</option>
                                </select>
                            </div>
                        </div>
                    @endforeach
                </div>
                <template data-repeater-template="daily-report-plans">
                    <div class="rounded-2xl border border-slate-200 p-4" data-repeater-item>
                        <div class="mb-4 flex items-center justify-between gap-3"><p class="text-sm font-semibold text-slate-900" data-repeater-title="Rencana">Rencana baru</p><button type="button" data-repeater-remove class="inline-flex h-9 items-center rounded-2xl border border-rose-200 px-3 text-xs font-semibold text-rose-600">Hapus</button></div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <input type="text" name="plans[__INDEX__][plan_task]" placeholder="Rencana pekerjaan" class="h-12 w-full rounded-2xl border border-slate-200 px-4 sm:col-span-2">
                            <select name="plans[__INDEX__][priority]" class="h-12 w-full rounded-2xl border border-slate-200 px-4"><option value="tinggi">Tinggi</option><option value="sedang" selected>Sedang</option><option value="rendah">Rendah</option></select>
                        </div>
                    </div>
                </template>
            </div>

            <div class="sm:col-span-2"><label class="mb-2 block text-sm font-medium text-slate-700">Catatan tambahan</label><textarea name="additional_notes" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Catatan tambahan bila diperlukan...">{{ old('additional_notes') }}</textarea></div>

            <div class="sm:col-span-2 flex flex-wrap gap-3">
                <button class="h-12 rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white">Generate laporan</button>
                <button formaction="{{ route('tools.daily-work-report.pdf') }}" data-analytics-export data-analytics-event="career_tool_export" data-tool-name="daily_work_report" data-export-type="pdf" class="h-12 rounded-2xl bg-blue-700 px-5 text-sm font-semibold text-white">Download PDF</button>
                <button formaction="{{ route('tools.daily-work-report.word') }}" data-analytics-export data-analytics-event="career_tool_export" data-tool-name="daily_work_report" data-export-type="word" class="h-12 rounded-2xl bg-white border border-blue-200 px-5 text-sm font-semibold text-blue-700">Download Word</button>
                <button formaction="{{ route('tools.daily-work-report.print') }}" formtarget="_blank" data-analytics-export data-analytics-event="career_tool_export" data-tool-name="daily_work_report" data-export-type="print" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Print</button>
                <button formaction="{{ route('tools.daily-work-report.download') }}" data-analytics-export data-analytics-event="career_tool_export" data-tool-name="daily_work_report" data-export-type="txt" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Download TXT</button>
                <button type="reset" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Reset form</button>
            </div>
        </form>

        @if ($generatorPreview && $generatorPreview['copy_text'])
            <pre id="daily-work-report-copy-content" class="sr-only">{{ $generatorPreview['copy_text'] }}</pre>
            <button type="button" data-copy-target="#daily-work-report-copy-content" data-analytics-copy data-analytics-event="career_tool_copy" data-tool-name="daily_work_report" class="mt-4 inline-flex h-11 items-center rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Copy laporan</button>
        @endif
        <div class="mt-5 rounded-3xl border border-blue-100 bg-blue-50 p-4 text-sm leading-7 text-blue-900">
            Untuk kebutuhan administrasi tim yang lebih rapi, lanjutkan ke <a href="{{ route('tools.show', 'generator-sop') }}" class="font-semibold underline decoration-blue-300 underline-offset-4">Generator SOP</a> atau <a href="{{ route('tools.show', 'generator-job-description') }}" class="font-semibold underline decoration-blue-300 underline-offset-4">Generator Job Description</a>.
        </div>
    </div>
    <div class="order-1 self-start rounded-3xl border border-slate-200 bg-slate-50/60 p-5 xl:order-2 xl:sticky xl:top-24">
        <h2 class="text-lg font-semibold text-slate-900">Preview Laporan Harian</h2>
        <div class="mt-5 overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white p-3 shadow-sm">
            @if ($generatorPreview)
                {!! $generatorPreview['html'] !!}
            @else
                <div class="flex min-h-[420px] items-center justify-center rounded-[1.25rem] border border-dashed border-slate-200 bg-slate-50 px-6 text-center text-sm leading-7 text-slate-500">Lengkapi tugas, kendala, dan rencana kerja untuk melihat laporan dalam format chat, email, atau dokumen formal.</div>
            @endif
        </div>
    </div>
</div>
