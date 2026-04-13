@extends('layouts.app')
@section('title','Riwayat Pengaduan')
@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
    <div></div>
    <a href="{{ route('siswa.dashboard') }}" class="btn btn-primary btn-sm">
        <span class="msym sz18">add_circle</span> Pengaduan Baru
    </a>
</div>

<div class="card">
    <div class="card-hd">
        <span>Riwayat Pengaduan Saya</span>
        <span style="font-size:12px;color:var(--muted);font-weight:500;">{{ $riwayats->count() }} pengaduan</span>
    </div>
    <div class="tbl-wrap">
        <table class="tbl">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th>Keterangan</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Umpan Balik</th>
                    <th>PJ</th>
                    <th>Alasan Tolak</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayats as $i => $item)
                @php
                    $asp    = $item->aspirasi;
                    $status = $asp?->status ?? 'Menunggu';
                    // Sembunyikan status internal dari siswa
                    $displayMap = [
                        'Menunggu'   => ['label'=>'Menunggu',  'cls'=>'s-menunggu'],
                        'Diassign'   => ['label'=>'Diproses',  'cls'=>'s-diproses'],
                        'Proses'     => ['label'=>'Diproses',  'cls'=>'s-diproses'],
                        'Ditolak_PJ' => ['label'=>'Diproses',  'cls'=>'s-diproses'],
                        'Selesai'    => ['label'=>'Selesai',   'cls'=>'s-selesai'],
                        'Ditolak'    => ['label'=>'Ditolak',   'cls'=>'s-ditolak'],
                    ];
                    $display = $displayMap[$status] ?? ['label'=>$status,'cls'=>'s-menunggu'];
                @endphp
                <tr>
                    <td style="color:var(--muted);">{{ $i+1 }}</td>
                    <td style="font-weight:600;">{{ $item->kategori?->ket_kategori ?? '—' }}</td>
                    <td style="max-width:120px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $item->lokasi }}</td>
                    <td style="max-width:160px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:var(--muted);font-size:13px;">{{ $item->ket }}</td>
                    <td><span class="sbadge {{ $display['cls'] }}">{{ $display['label'] }}</span></td>
                    <td style="color:var(--muted);font-size:12px;white-space:nowrap;">{{ $item->created_at->format('d M Y') }}</td>
                    <td style="max-width:160px;font-size:13px;">{{ $asp?->feedback ?? '—' }}</td>
                    <td style="font-size:13px;">{{ $asp?->assignedTo?->name ?? '—' }}</td>
                    <td style="max-width:160px;font-size:13px;color:#DC2626;">
                        {{ $status === 'Ditolak' ? ($asp?->alasan_tolak ?? '—') : '—' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center;padding:40px;color:var(--muted);">
                        <span class="msym sz28" style="display:block;margin-bottom:8px;opacity:.4;">receipt_long</span>
                        Belum ada pengaduan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
