@extends('layouts.app')
@section('title', 'Detail Ruangan')
@php use Illuminate\Support\Facades\Storage; @endphp
@section('content')

    {{-- Breadcrumb + header --}}
    <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;flex-wrap:wrap;">
        <a href="{{ route('admin.pantau') }}" class="btn btn-secondary btn-sm" style="gap:4px;">
            <span class="msym sz18">arrow_back</span> Pantau Ruangan
        </a>
        <span style="color:var(--muted);">/</span>
        <span style="font-weight:700;color:var(--text);">{{ $ruangan->nama_ruangan }}</span>
        <span style="margin-left:auto;font-size:13px;color:var(--muted);">
            PJ: <strong>{{ $ruangan->penanggungjawab?->name ?? '—' }}</strong>
        </span>
    </div>

    <div class="card">
        <div class="card-hd">
            <span>Semua Pengaduan — {{ $ruangan->nama_ruangan }}</span>
            <span style="font-size:12px;color:var(--muted);font-weight:500;">{{ $aspirasis->count() }} pengaduan</span>
        </div>
        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Siswa</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Keterangan</th>
                        <th>Foto Laporan</th>
                        <th>Bukti Selesai</th>
                        <th>Status</th>
                        <th>PJ</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($aspirasis as $i => $asp)
                        @php
                            $s = $asp->status;
                            $badgeMap = [
                                'Menunggu' => 's-menunggu',
                                'Proses' => 's-proses',
                                'Tidak_Mampu' => 's-tidak-mampu',
                                'Sedang_Dikerjakan' => 's-sdikerjakan',
                                'Selesai' => 's-selesai',
                                'Ditolak' => 's-ditolak',
                            ];
                            $cls = $badgeMap[$s] ?? 's-menunggu';
                        @endphp
                        <tr>
                            <td style="color:var(--muted);">{{ $i + 1 }}</td>
                            <td>
                                <div style="font-weight:600;font-size:13px;">{{ $asp->inputAspirasi?->user?->name ?? '—' }}
                                </div>
                                <div style="font-size:11px;color:var(--muted);">
                                    {{ $asp->inputAspirasi?->user?->kelas?->nama_kelas ?? '—' }}</div>
                            </td>
                            <td style="font-size:13px;">{{ $asp->inputAspirasi?->kategori?->ket_kategori ?? '—' }}</td>
                            <td
                                style="max-width:110px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-size:13px;">
                                {{ $asp->inputAspirasi?->lokasi ?? '—' }}</td>
                            <td
                                style="max-width:160px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:var(--muted);font-size:12px;">
                                {{ $asp->inputAspirasi?->ket ?? '—' }}</td>
                            <td>
                                @if ($asp->foto_laporan)
                                    <a href="{{ Storage::url($asp->foto_laporan) }}" target="_blank">
                                        <img src="{{ Storage::url($asp->foto_laporan) }}" alt="foto"
                                            style="width:40px;height:40px;object-fit:cover;border-radius:4px;border:1px solid #e5e7eb;">
                                    </a>
                                @else
                                    <span style="color:var(--muted);font-size:12px;">—</span>
                                @endif
                            </td>
                            <td>
                                @if ($s === 'Selesai' && $asp->foto_selesai)
                                    <a href="{{ Storage::url($asp->foto_selesai) }}" target="_blank">
                                        <img src="{{ Storage::url($asp->foto_selesai) }}" alt="bukti selesai"
                                            style="width:40px;height:40px;object-fit:cover;border-radius:4px;border:1px solid #bbf7d0;">
                                    </a>
                                @else
                                    <span style="color:var(--muted);font-size:12px;">—</span>
                                @endif
                            </td>
                            <td><span class="sbadge {{ $cls }}">{{ str_replace('_', ' ', $s) }}</span></td>
                            <td style="font-size:13px;">{{ $asp->assignedTo?->name ?? '—' }}</td>
                            <td style="color:var(--muted);font-size:12px;white-space:nowrap;">
                                {{ $asp->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align:center;padding:40px;color:var(--muted);">
                                <span class="msym sz28" style="display:block;margin-bottom:8px;opacity:.4;">inbox</span>
                                Belum ada pengaduan di ruangan ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
