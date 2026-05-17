<div style="margin:0 auto;max-width:820px;padding:28px;border:1px solid #cbd5e1;background:#fff;color:#0f172a;font-family:DejaVu Sans,sans-serif;">
    <div style="display:table;width:100%;margin-bottom:20px;">
        <div style="display:table-cell;width:62%;vertical-align:top;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">{{ $document['output_type_label'] }}</div>
            <div style="margin-top:8px;font-size:24px;font-weight:700;">{{ $document['position_name'] }}</div>
            <div style="margin-top:8px;font-size:12px;color:#475569;">{{ $document['department'] }} | {{ $document['job_level_label'] }} | {{ $document['employment_type_label'] }}</div>
        </div>
        <div style="display:table-cell;width:38%;vertical-align:top;">
            <div style="border-radius:18px;background:#f8fafc;padding:16px;font-size:12px;line-height:1.9;color:#475569;">
                <div>Lokasi: <strong style="color:#0f172a;">{{ $document['work_location'] }}</strong></div>
                <div>Reports to: <strong style="color:#0f172a;">{{ $document['reports_to'] }}</strong></div>
                <div>Direct reports: <strong style="color:#0f172a;">{{ $document['direct_reports'] ?: '-' }}</strong></div>
                <div>Bahasa: <strong style="color:#0f172a;">{{ $document['language_label'] }}</strong></div>
            </div>
        </div>
    </div>

    <div style="margin-bottom:18px;">
        <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Ringkasan posisi</div>
        <div style="margin-top:10px;font-size:12px;line-height:1.9;">{{ $document['position_summary'] }}</div>
    </div>
    <div style="margin-bottom:18px;">
        <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Tujuan posisi</div>
        <div style="margin-top:10px;font-size:12px;line-height:1.9;">{{ $document['position_objective'] }}</div>
    </div>
    <div style="margin-bottom:18px;">
        <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Tanggung jawab utama</div>
        <ul style="margin:10px 0 0;padding-left:18px;font-size:12px;line-height:1.9;">
            @foreach ($document['responsibilities'] as $item)
                <li>{{ $item['text'] }}</li>
            @endforeach
        </ul>
    </div>
    <div style="display:table;width:100%;margin-bottom:18px;">
        <div style="display:table-cell;width:50%;vertical-align:top;padding-right:12px;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Kualifikasi</div>
            <div style="margin-top:10px;font-size:12px;line-height:1.9;"><strong>Pendidikan:</strong> {{ $document['education_qualification'] }}</div>
            <div style="margin-top:8px;font-size:12px;line-height:1.9;"><strong>Pengalaman:</strong> {{ $document['minimum_experience'] }}</div>
            <div style="margin-top:12px;font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Skill teknis</div>
            <ul style="margin:10px 0 0;padding-left:18px;font-size:12px;line-height:1.9;color:#475569;">
                @foreach ($document['technical_skills_list'] as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        </div>
        <div style="display:table-cell;width:50%;vertical-align:top;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Soft skill</div>
            <ul style="margin:10px 0 0;padding-left:18px;font-size:12px;line-height:1.9;color:#475569;">
                @foreach ($document['soft_skills_list'] as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
            @if (count($document['tools_software_list']))
                <div style="margin-top:12px;font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Tools / software</div>
                <ul style="margin:10px 0 0;padding-left:18px;font-size:12px;line-height:1.9;color:#475569;">
                    @foreach ($document['tools_software_list'] as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
    <div style="margin-bottom:18px;">
        <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">KPI / indikator keberhasilan</div>
        <ul style="margin:10px 0 0;padding-left:18px;font-size:12px;line-height:1.9;color:#475569;">
            @foreach ($document['kpis'] as $item)
                <li>{{ $item['text'] }}</li>
            @endforeach
        </ul>
    </div>
    @if (! empty($document['salary_range']) || count($document['benefits_list']) || ! empty($document['additional_notes']))
        <div style="border-top:1px solid #e2e8f0;padding-top:18px;font-size:12px;line-height:1.9;color:#475569;">
            @if (! empty($document['salary_range']))
                <div><strong style="color:#0f172a;">Range gaji:</strong> {{ $document['salary_range'] }}</div>
            @endif
            @if (count($document['benefits_list']))
                <div style="margin-top:12px;font-weight:700;color:#0f172a;">Benefit</div>
                <ul style="margin:8px 0 0;padding-left:18px;">
                    @foreach ($document['benefits_list'] as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            @endif
            @if (! empty($document['additional_notes']))
                <div style="margin-top:12px;"><strong style="color:#0f172a;">Catatan:</strong> {{ $document['additional_notes'] }}</div>
            @endif
        </div>
    @endif
</div>
