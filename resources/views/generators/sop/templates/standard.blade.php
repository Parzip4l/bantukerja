<div style="margin:0 auto;max-width:840px;padding:28px;border:1px solid #cbd5e1;background:#fff;color:#0f172a;font-family:DejaVu Sans,sans-serif;">
    <div style="display:table;width:100%;margin-bottom:18px;">
        <div style="display:table-cell;width:62%;vertical-align:top;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">{{ $document['sop_type_label'] }}</div>
            <div style="margin-top:8px;font-size:24px;font-weight:700;">{{ $document['sop_name'] }}</div>
            <div style="margin-top:10px;font-size:12px;color:#475569;">Departemen: {{ $document['department'] }}</div>
        </div>
        <div style="display:table-cell;width:38%;vertical-align:top;">
            <table style="width:100%;border-collapse:collapse;font-size:12px;">
                <tr><td style="padding:6px 0;color:#64748b;">No. Dokumen</td><td style="padding:6px 0;font-weight:700;">{{ $document['document_number'] }}</td></tr>
                <tr><td style="padding:6px 0;color:#64748b;">Versi</td><td style="padding:6px 0;font-weight:700;">{{ $document['document_version'] }}</td></tr>
                <tr><td style="padding:6px 0;color:#64748b;">Berlaku</td><td style="padding:6px 0;font-weight:700;">{{ $document['effective_date_label'] }}</td></tr>
            </table>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
        <div style="border:1px solid #e2e8f0;border-radius:18px;padding:16px;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Tujuan</div>
            <div style="margin-top:10px;font-size:12px;line-height:1.9;">{{ $document['objective'] }}</div>
        </div>
        <div style="border:1px solid #e2e8f0;border-radius:18px;padding:16px;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Ruang lingkup</div>
            <div style="margin-top:10px;font-size:12px;line-height:1.9;">{{ $document['scope'] }}</div>
        </div>
    </div>

    @if (count($document['definitions_lines']))
        <div style="margin-bottom:18px;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Definisi istilah</div>
            <ul style="margin:10px 0 0;padding-left:18px;font-size:12px;line-height:1.9;color:#475569;">
                @foreach ($document['definitions_lines'] as $line)
                    <li>{{ $line }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="margin-bottom:18px;">
        <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Peran & tanggung jawab</div>
        <table style="width:100%;border-collapse:collapse;margin-top:10px;font-size:12px;">
            <thead style="background:#f8fafc;">
                <tr>
                    <th style="padding:10px 8px;text-align:left;">Role</th>
                    <th style="padding:10px 8px;text-align:left;">Tanggung jawab</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($document['roles'] as $role)
                    <tr>
                        <td style="border-bottom:1px solid #e2e8f0;padding:10px 8px;font-weight:700;">{{ $role['role'] }}</td>
                        <td style="border-bottom:1px solid #e2e8f0;padding:10px 8px;">{{ $role['responsibility'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-bottom:18px;">
        <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Prosedur langkah demi langkah</div>
        <table style="width:100%;border-collapse:collapse;margin-top:10px;font-size:12px;">
            <thead style="background:#f8fafc;">
                <tr>
                    <th style="padding:10px 8px;text-align:left;width:42px;">No</th>
                    <th style="padding:10px 8px;text-align:left;">Langkah</th>
                    <th style="padding:10px 8px;text-align:left;">Deskripsi</th>
                    <th style="padding:10px 8px;text-align:left;">PIC</th>
                    <th style="padding:10px 8px;text-align:left;">Output</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($document['steps'] as $step)
                    <tr>
                        <td style="border-bottom:1px solid #e2e8f0;padding:10px 8px;font-weight:700;">{{ $step['number'] }}</td>
                        <td style="border-bottom:1px solid #e2e8f0;padding:10px 8px;font-weight:700;">{{ $step['name'] }}</td>
                        <td style="border-bottom:1px solid #e2e8f0;padding:10px 8px;">{{ $step['description'] }}</td>
                        <td style="border-bottom:1px solid #e2e8f0;padding:10px 8px;">{{ $step['pic'] ?: '-' }}</td>
                        <td style="border-bottom:1px solid #e2e8f0;padding:10px 8px;">{{ $step['output'] ?: '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if (count($document['related_documents_lines']) || count($document['risk_notes_lines']) || count($document['kpi_lines']))
        <div style="display:table;width:100%;">
            <div style="display:table-cell;width:50%;vertical-align:top;padding-right:12px;">
                @if (count($document['related_documents_lines']))
                    <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Dokumen terkait</div>
                    <ul style="margin:10px 0 18px;padding-left:18px;font-size:12px;line-height:1.9;color:#475569;">
                        @foreach ($document['related_documents_lines'] as $line)
                            <li>{{ $line }}</li>
                        @endforeach
                    </ul>
                @endif
                @if (count($document['kpi_lines']))
                    <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Indikator keberhasilan</div>
                    <ul style="margin:10px 0 0;padding-left:18px;font-size:12px;line-height:1.9;color:#475569;">
                        @foreach ($document['kpi_lines'] as $line)
                            <li>{{ $line }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div style="display:table-cell;width:50%;vertical-align:top;">
                @if (count($document['risk_notes_lines']))
                    <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Risiko / catatan penting</div>
                    <ul style="margin:10px 0 0;padding-left:18px;font-size:12px;line-height:1.9;color:#475569;">
                        @foreach ($document['risk_notes_lines'] as $line)
                            <li>{{ $line }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    @endif

    <div style="margin-top:24px;border-top:1px solid #e2e8f0;padding-top:18px;display:table;width:100%;font-size:12px;color:#475569;">
        <div style="display:table-cell;width:33%;vertical-align:top;">Dibuat oleh<br><div style="margin-top:48px;font-weight:700;color:#0f172a;">{{ $document['prepared_by'] }}</div></div>
        <div style="display:table-cell;width:33%;vertical-align:top;">Diperiksa oleh<br><div style="margin-top:48px;font-weight:700;color:#0f172a;">{{ $document['reviewed_by'] }}</div></div>
        <div style="display:table-cell;width:33%;vertical-align:top;">Disetujui oleh<br><div style="margin-top:48px;font-weight:700;color:#0f172a;">{{ $document['approved_by'] }}</div></div>
    </div>
</div>
