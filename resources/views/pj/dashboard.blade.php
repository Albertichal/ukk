@extends('layouts.app')
@section('title','Tugas Saya')
@section('content')

{{-- Stats --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:20px;max-width:480px;">
    <div class="stat-card">
        <div class="stat-icon" style="background:#CFE2FF;">
            <span class="msym" style="color:#084298;">assignment</span>
        </div>
        <div>
            <div class="stat-num" style="color:#084298;">{{ $stats['assigned'] }}</div>
            <div class="stat-lbl">Tugas Aktif</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#CFF4FC;">
            <span class="msym" style="color:#055160;">construction</span>
        </div>
        <div>
            <div class="stat-num" style="color:#055160;">{{ $stats['proses'] }}</div>
            <div class="stat-lbl">Sedang Proses</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-hd">
        <span>Pengaduan yang Di-assign</span>
        <span style="font-size:12px;color:var(--muted);font-weight:500;">{{ $aspirasis->count() }} tugas</span>
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
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($aspirasis as $i => $asp)
                @php
                    $cls = $asp->status === 'Proses' ? 's-proses' : 's-diassign';
                @endphp
                <tr>
                    <td style="color:var(--muted);">{{ $i+1 }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div class="av av-sm av-siswa">{{ strtoupper(substr($asp->inputAspirasi?->user?->name ?? 'S',0,1)) }}</div>
                            <div>
                                <div style="font-weight:600;font-size:13px;">{{ $asp->inputAspirasi?->user?->name ?? '—' }}</div>
                                <div style="font-size:11px;color:var(--muted);">{{ $asp->inputAspirasi?->user?->kelas ?? '—' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $asp->kategori?->ket_kategori ?? '—' }}</td>
                    <td style="max-width:120px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $asp->inputAspirasi?->lokasi ?? '—' }}</td>
                    <td style="max-width:160px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:var(--muted);font-size:13px;">{{ $asp->inputAspirasi?->ket ?? '—' }}</td>
                    <td><span class="sbadge {{ $cls }}">{{ $asp->status }}</span></td>
                    <td style="color:var(--muted);font-size:12px;white-space:nowrap;">{{ $asp->created_at->format('d M Y') }}</td>
                    <td>
                        <div style="display:flex;gap:4px;flex-wrap:wrap;">
                            @if($asp->status === 'Diassign')
                                <form method="POST" action="{{ route('pj.proses',$asp->id_aspirasi) }}"
                                      onsubmit="return confirm('Tandai sebagai Proses?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm" style="background:#CFF4FC;color:#055160;gap:4px;">
                                        <span class="msym sz18">play_arrow</span> Proses
                                    </button>
                                </form>
                            @elseif($asp->status === 'Proses')
                                <form method="POST" action="{{ route('pj.selesai',$asp->id_aspirasi) }}"
                                      onsubmit="return confirm('Tandai sebagai Selesai?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" style="gap:4px;">
                                        <span class="msym sz18">check_circle</span> Selesai
                                    </button>
                                </form>
                            @endif
                            <button class="btn-icon btn-icon-red" title="Tolak"
                                    onclick="setTolakId('{{ route('pj.tolak',$asp->id_aspirasi) }}')">
                                <span class="msym">cancel</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:40px;color:var(--muted);">
                        <span class="msym sz28" style="display:block;margin-bottom:8px;opacity:.4;">assignment</span>
                        Belum ada tugas yang di-assign
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL Tolak PJ --}}
<div class="modal-backdrop" id="mTolakPJ">
    <div class="modal-box">
        <div class="modal-hd" style="color:#DC2626;">
            <span>Tolak Pengaduan</span>
            <button class="modal-close" onclick="closeModal('mTolakPJ')"><span class="msym">close</span></button>
        </div>
        <form method="POST" id="formTolakPJ">
            @csrf
            <div class="modal-bd">
                <div class="field">
                    <label>Alasan Penolakan <span class="req">*</span></label>
                    <textarea name="alasan_tolak" class="ta" rows="4" maxlength="500" required
                              placeholder="Wajib isi alasan penolakan — akan diteruskan ke admin"></textarea>
                    <div style="font-size:11px;color:var(--muted);margin-top:4px;">Alasan ini akan diteruskan ke admin untuk konfirmasi.</div>
                </div>
            </div>
            <div class="modal-ft">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal('mTolakPJ')">Batal</button>
                <button type="submit" class="btn btn-danger btn-sm"><span class="msym sz18">cancel</span> Tolak Pengaduan</button>
            </div>
        </form>
    </div>
</div>

@endsection
@push('scripts')
<script>
function setTolakId(url) {
    document.getElementById('formTolakPJ').action = url;
    openModal('mTolakPJ');
}
</script>
@endpush
