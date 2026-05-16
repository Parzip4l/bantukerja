<div style="margin-top: 36px;">
    <div style="font-size: 12px; color: #475569;">Hormat saya,</div>
    <div style="height: 64px;"></div>
    <div style="font-size: 14px; font-weight: 700; color: #0f172a;">{{ $name }}</div>
    @if (! empty($position))
        <div style="margin-top: 4px; font-size: 12px; color: #475569;">{{ $position }}</div>
    @endif
    @if (! empty($company))
        <div style="margin-top: 2px; font-size: 12px; color: #475569;">{{ $company }}</div>
    @endif
</div>
