<div style="margin:0 auto;max-width:820px;padding:30px;border:1px solid #dbe4f0;border-radius:24px;background:linear-gradient(180deg,#fff,#f8fafc);color:#0f172a;font-family:DejaVu Sans,sans-serif;">
    <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Job Posting</div>
    <div style="margin-top:8px;font-size:28px;font-weight:700;">{{ $document['position_name'] }}</div>
    <div style="margin-top:10px;font-size:12px;color:#475569;">{{ $document['department'] }} | {{ $document['employment_type_label'] }} | {{ $document['work_location'] }}</div>
    <div style="margin-top:18px;font-size:12px;line-height:1.9;color:#1e293b;">{{ $document['intro_paragraph'] }}</div>
    <div style="margin-top:18px;padding:18px;border-radius:18px;background:#fff;border:1px solid #e2e8f0;">
        <div style="font-size:12px;line-height:1.9;color:#1e293b;">{{ $document['position_summary'] }}</div>
    </div>
    <div style="display:table;width:100%;margin-top:22px;">
        <div style="display:table-cell;width:52%;vertical-align:top;padding-right:12px;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Tanggung jawab</div>
            <ul style="margin:10px 0 0;padding-left:18px;font-size:12px;line-height:1.9;">
                @foreach ($document['responsibilities'] as $item)
                    <li>{{ $item['text'] }}</li>
                @endforeach
            </ul>
            <div style="margin-top:14px;font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">KPI</div>
            <ul style="margin:10px 0 0;padding-left:18px;font-size:12px;line-height:1.9;">
                @foreach ($document['kpis'] as $item)
                    <li>{{ $item['text'] }}</li>
                @endforeach
            </ul>
        </div>
        <div style="display:table-cell;width:48%;vertical-align:top;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Kualifikasi</div>
            <div style="margin-top:10px;font-size:12px;line-height:1.9;color:#475569;">
                <div><strong style="color:#0f172a;">Pendidikan:</strong> {{ $document['education_qualification'] }}</div>
                <div><strong style="color:#0f172a;">Pengalaman:</strong> {{ $document['minimum_experience'] }}</div>
            </div>
            <div style="margin-top:14px;font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Skill teknis</div>
            <div style="margin-top:8px;font-size:12px;line-height:1.9;color:#475569;">{{ implode(' • ', $document['technical_skills_list']) }}</div>
            <div style="margin-top:14px;font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Soft skill</div>
            <div style="margin-top:8px;font-size:12px;line-height:1.9;color:#475569;">{{ implode(' • ', $document['soft_skills_list']) }}</div>
            @if (count($document['benefits_list']) || ! empty($document['salary_range']))
                <div style="margin-top:14px;font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Benefit & kompensasi</div>
                @if (! empty($document['salary_range']))
                    <div style="margin-top:8px;font-size:12px;color:#475569;"><strong style="color:#0f172a;">Range gaji:</strong> {{ $document['salary_range'] }}</div>
                @endif
                @if (count($document['benefits_list']))
                    <ul style="margin:8px 0 0;padding-left:18px;font-size:12px;line-height:1.9;color:#475569;">
                        @foreach ($document['benefits_list'] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                @endif
            @endif
        </div>
    </div>
</div>
