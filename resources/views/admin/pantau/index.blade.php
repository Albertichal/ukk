@extends('layouts.app')
@section('title','Pantau Ruangan')
@section('content')

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:16px;">
    @forelse($ruangans as $ruangan)
    @php
        $aktif  = $ruangan->aktif_count;
        $border = $aktif > 3 ? '2px solid #DC2626' : '1px solid var(--border)';
    @endphp
    <a href="{{ route('admin.pantau.show', $ruangan->id_ruangan) }}"
       style="display:block;text-decoration:none;border-radius:12px;border:{{ $border }};background:#fff;padding:20px;transition:box-shadow .15s;box-shadow:0 1px 4px rgba(0,0,0,.06);"
       onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,.12)'"
       onmouseout="this.style.boxShadow='0 1px 4px rgba(0,0,0,.06)'">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;">
            <div>
                <div style="font-weight:700;font-size:15px;color:var(--text);margin-bottom:4px;">{{ $ruangan->nama_ruangan }}</div>
                <div style="font-size:12px;color:var(--muted);display:flex;align-items:center;gap:4px;">
                    <span class="msym sz18" style="opacity:.6;">person</span>
                    {{ $ruangan->penanggungjawab?->name ?? 'Belum ada PJ' }}
                </div>
            </div>
            <div style="text-align:center;min-width:48px;">
                <div style="font-size:24px;font-weight:800;color:{{ $aktif > 3 ? '#DC2626' : ($aktif > 0 ? '#084298' : '#6b7280') }};">
                    {{ $aktif }}
                </div>
                <div style="font-size:10px;color:var(--muted);line-height:1.2;">tugas<br>aktif</div>
            </div>
        </div>
        @if($aktif > 3)
        <div style="margin-top:12px;padding:6px 10px;background:#FEF2F2;border-radius:6px;font-size:11px;color:#DC2626;display:flex;align-items:center;gap:4px;">
            <span class="msym sz16">warning</span> Beban tinggi — lebih dari 3 tugas aktif
        </div>
        @endif
        <div style="margin-top:14px;font-size:12px;color:var(--primary);font-weight:600;display:flex;align-items:center;gap:4px;">
            <span class="msym sz16">visibility</span> Lihat Detail
        </div>
    </a>
    @empty
    <div style="grid-column:1/-1;text-align:center;padding:60px;color:var(--muted);">
        <span class="msym sz28" style="display:block;margin-bottom:8px;opacity:.4;">meeting_room</span>
        Belum ada ruangan terdaftar
    </div>
    @endforelse
</div>

@endsection
