<div style="margin:0 auto; max-width:760px; border:1px solid #dbeafe; padding:30px; font-family:DejaVu Sans, sans-serif; color:#0f172a; line-height:1.8;">
    <div style="display:flex; justify-content:space-between; gap:24px; align-items:flex-start;">
        <div>
            <div style="font-size:26px; font-weight:700;">{{ $document['title'] }}</div>
            <div style="margin-top:8px; font-size:12px; color:#2563eb; text-transform:uppercase; letter-spacing:0.08em;">Berita acara</div>
        </div>
        <div style="min-width:170px; border-radius:18px; background:#eff6ff; padding:14px 16px; font-size:12px; color:#1e3a8a;">
            @if (! empty($document['document_number']))
                <div><strong>No:</strong> {{ $document['document_number'] }}</div>
            @endif
            <div style="margin-top:6px;"><strong>Tanggal:</strong> {{ $document['event_date_label'] ?? '-' }}</div>
            @if (! empty($document['location']))
                <div style="margin-top:6px;"><strong>Lokasi:</strong> {{ $document['location'] }}</div>
            @endif
        </div>
    </div>

    @if (! empty($document['opening']))
        <div style="margin-top:24px; border-left:4px solid #2563eb; padding-left:16px; font-size:14px; color:#334155;">
            {{ $document['opening'] }}
        </div>
    @endif

    @if (! empty($document['first_party_name']) || ! empty($document['second_party_name']))
        <div style="display:flex; gap:18px; margin-top:24px;">
            <div style="flex:1; border:1px solid #e2e8f0; border-radius:18px; padding:16px;">
                <div style="font-size:12px; color:#64748b; text-transform:uppercase; letter-spacing:0.08em;">Pihak Pertama</div>
                <div style="margin-top:8px; font-size:16px; font-weight:700;">{{ $document['first_party_name'] ?? '-' }}</div>
                @if (! empty($document['first_party_role']))
                    <div style="margin-top:4px; font-size:13px; color:#475569;">{{ $document['first_party_role'] }}</div>
                @endif
            </div>
            <div style="flex:1; border:1px solid #e2e8f0; border-radius:18px; padding:16px;">
                <div style="font-size:12px; color:#64748b; text-transform:uppercase; letter-spacing:0.08em;">Pihak Kedua</div>
                <div style="margin-top:8px; font-size:16px; font-weight:700;">{{ $document['second_party_name'] ?? '-' }}</div>
                @if (! empty($document['second_party_role']))
                    <div style="margin-top:4px; font-size:13px; color:#475569;">{{ $document['second_party_role'] }}</div>
                @endif
            </div>
        </div>
    @endif

    <div style="margin-top:24px; font-size:14px;">
        @foreach ($document['content_paragraphs'] ?? [] as $paragraph)
            <p style="margin:0 0 12px;">{{ $paragraph }}</p>
        @endforeach
    </div>

    @if (! empty($document['closing']))
        <div style="margin-top:20px; font-size:14px; color:#334155;">{{ $document['closing'] }}</div>
    @endif

    <div style="display:flex; justify-content:space-between; gap:24px; margin-top:40px;">
        <div style="flex:1; text-align:center;">
            <div style="font-size:13px; color:#475569;">Pihak Pertama</div>
            <div style="height:78px;"></div>
            <div style="border-top:1px solid #94a3b8; padding-top:10px; font-weight:700;">{{ $document['first_party_name'] ?? '................................' }}</div>
        </div>
        <div style="flex:1; text-align:center;">
            <div style="font-size:13px; color:#475569;">Pihak Kedua</div>
            <div style="height:78px;"></div>
            <div style="border-top:1px solid #94a3b8; padding-top:10px; font-weight:700;">{{ $document['second_party_name'] ?? '................................' }}</div>
        </div>
    </div>
</div>
