@extends('layouts.app')
@section('title','Histori')
@section('content')

<div class="card">
    <div class="card-hd">
        <span>Histori Pengaduan</span>
        <span style="font-size:12px;color:var(--muted);font-weight:500;">{{ $aspirasis->count() }} data</span>
    </div>
    <div class="tbl-wrap">
        <table class="tbl">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Siswa</th>
                    <th>Ruangan</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                    <th>PJ</th>
                    <th>Alasan Tolak</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($aspirasis as $i => $asp)
                @php
                    $cls = $asp->status === 'Selesai' ? 's-selesai' : 's-ditolak';
                @endphp
                <tr>
                    <td style="color:var(--muted);">{{ $i+1 }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div class="av av-sm av-siswa">{{ strtoupper(substr($asp->inputAspirasi?->user?->name ?? 'S',0,1)) }}</div>
                            <div>
                                <div style="font-weight:600;font-size:13px;">{{ $asp->inputAspirasi?->user?->name ?? '—' }}</div>
                                <div style="font-size:11px;color:var(--muted);">{{ $asp->inputAspirasi?->user?->kelas?->nama_kelas ?? '—' }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px;">{{ $asp->inputAspirasi?->ruangan?->nama_ruangan ?? '—' }}</td>
                    <td>{{ $asp->inputAspirasi?->kategori?->ket_kategori ?? '—' }}</td>
                    <td style="max-width:120px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $asp->inputAspirasi?->lokasi ?? '—' }}</td>
                    <td><span class="sbadge {{ $cls }}">{{ $asp->status }}</span></td>
                    <td style="font-size:13px;">{{ $asp->assignedTo?->name ?? '—' }}</td>
                    <td style="max-width:180px;font-size:13px;color:#DC2626;">
                        {{ $asp->alasan_tolak ?? '—' }}
                    </td>
                    <td style="color:var(--muted);font-size:12px;white-space:nowrap;">{{ $asp->updated_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center;padding:40px;color:var(--muted);">
                        <span class="msym sz28" style="display:block;margin-bottom:8px;opacity:.4;">history</span>
                        Belum ada riwayat pengaduan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
