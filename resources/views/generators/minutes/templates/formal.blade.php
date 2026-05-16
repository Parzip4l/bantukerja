<div style="margin:0 auto; max-width:760px; font-family:DejaVu Sans, sans-serif; color:#0f172a; line-height:1.8;">
    <div style="text-align:center; border-bottom:1px solid #94a3b8; padding-bottom:18px;">
        <div style="font-size:24px; font-weight:700; text-transform:uppercase;">{{ $document['title'] }}</div>
        @if (! empty($document['document_number']))
            <div style="margin-top:8px; font-size:13px;">Nomor: {{ $document['document_number'] }}</div>
        @endif
    </div>

    <div style="margin-top:24px; font-size:14px;">
        <div><strong>Tanggal:</strong> {{ $document['event_date_label'] ?? '-' }}</div>
        @if (! empty($document['location']))
            <div style="margin-top:6px;"><strong>Lokasi:</strong> {{ $document['location'] }}</div>
        @endif
    </div>

    @if (! empty($document['opening']))
        <p style="margin-top:20px; font-size:14px;">{{ $document['opening'] }}</p>
    @endif

    @if (! empty($document['first_party_name']) || ! empty($document['second_party_name']))
        <table style="width:100%; border-collapse:collapse; margin-top:18px; font-size:14px;">
            <tr>
                <td style="width:50%; vertical-align:top; padding-right:18px;">
                    <div style="font-weight:700;">Pihak Pertama</div>
                    <div style="margin-top:6px;">{{ $document['first_party_name'] ?? '-' }}</div>
                    @if (! empty($document['first_party_role']))
                        <div style="font-size:13px; color:#475569;">{{ $document['first_party_role'] }}</div>
                    @endif
                </td>
                <td style="width:50%; vertical-align:top; padding-left:18px;">
                    <div style="font-weight:700;">Pihak Kedua</div>
                    <div style="margin-top:6px;">{{ $document['second_party_name'] ?? '-' }}</div>
                    @if (! empty($document['second_party_role']))
                        <div style="font-size:13px; color:#475569;">{{ $document['second_party_role'] }}</div>
                    @endif
                </td>
            </tr>
        </table>
    @endif

    <div style="margin-top:20px; font-size:14px;">
        @foreach ($document['content_paragraphs'] ?? [] as $paragraph)
            <p style="margin:0 0 12px;">{{ $paragraph }}</p>
        @endforeach
    </div>

    @if (! empty($document['closing']))
        <p style="margin-top:20px; font-size:14px;">{{ $document['closing'] }}</p>
    @endif

    <table style="width:100%; border-collapse:collapse; margin-top:36px; font-size:14px;">
        <tr>
            <td style="width:50%; vertical-align:top; text-align:center; padding-right:18px;">
                <div>Pihak Pertama,</div>
                <div style="height:78px;"></div>
                <div style="font-weight:700;">{{ $document['first_party_name'] ?? '................................' }}</div>
                @if (! empty($document['first_party_role']))
                    <div style="font-size:13px; color:#475569;">{{ $document['first_party_role'] }}</div>
                @endif
            </td>
            <td style="width:50%; vertical-align:top; text-align:center; padding-left:18px;">
                <div>Pihak Kedua,</div>
                <div style="height:78px;"></div>
                <div style="font-weight:700;">{{ $document['second_party_name'] ?? '................................' }}</div>
                @if (! empty($document['second_party_role']))
                    <div style="font-size:13px; color:#475569;">{{ $document['second_party_role'] }}</div>
                @endif
            </td>
        </tr>
    </table>
</div>
