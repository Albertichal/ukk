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
                    <th>Kelas</th>
                    <th>Ruangan</th>
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
                    $cls = $asp->status === 'Proses' ? 's-proses' : 's-menunggu';
                @endphp
                <tr>
                    <td style="color:var(--muted);">{{ $i+1 }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div class="av av-sm av-siswa">{{ strtoupper(substr($asp->inputAspirasi?->user?->name ?? 'S',0,1)) }}</div>
                            <div style="font-weight:600;font-size:13px;">{{ $asp->inputAspirasi?->user?->name ?? '—' }}</div>
                        </div>
                    </td>
                    <td style="font-size:13px;color:var(--muted);">{{ $asp->inputAspirasi?->user?->kelas?->nama_kelas ?? '—' }}</td>
                    <td style="font-size:13px;">{{ $asp->inputAspirasi?->ruangan?->nama_ruangan ?? '—' }}</td>
                    <td>{{ $asp->inputAspirasi?->kategori?->ket_kategori ?? '—' }}</td>
                    <td style="max-width:110px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $asp->inputAspirasi?->lokasi ?? '—' }}</td>
                    <td style="max-width:150px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:var(--muted);font-size:13px;">{{ $asp->inputAspirasi?->ket ?? '—' }}</td>
                    <td><span class="sbadge {{ $cls }}">{{ $asp->status }}</span></td>
                    <td style="color:var(--muted);font-size:12px;white-space:nowrap;">{{ $asp->created_at->format('d M Y') }}</td>
                    <td>
                        <div style="display:flex;gap:4px;flex-wrap:wrap;">
                            @if($asp->status === 'Menunggu')
                                <form method="POST" action="{{ route('pj.terima',$asp->id_aspirasi) }}"
                                      onsubmit="return confirm('Terima pengaduan ini?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" style="gap:4px;">
                                        <span class="msym sz18">check</span> Terima
                                    </button>
                                </form>
                                <button class="btn-icon btn-icon-red" title="Tolak"
                                        data-action="{{ route('pj.tolak',$asp->id_aspirasi) }}"
                                        onclick="openTolak(this)">
                                    <span class="msym">cancel</span>
                                </button>
                            @elseif($asp->status === 'Proses')
                                <form method="POST" action="{{ route('pj.selesai',$asp->id_aspirasi) }}"
                                      onsubmit="return confirm('Tandai sebagai Selesai?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" style="gap:4px;">
                                        <span class="msym sz18">check_circle</span> Selesai
                                    </button>
                                </form>
                                <button class="btn-icon btn-icon-orange" title="Tidak Mampu"
                                        data-action="{{ route('pj.tidak_mampu',$asp->id_aspirasi) }}"
                                        onclick="openTidakMampu(this)">
                                    <span class="msym">report_problem</span>
                                </button>
                                <button class="btn-icon btn-icon-red" title="Tolak"
                                        data-action="{{ route('pj.tolak',$asp->id_aspirasi) }}"
                                        onclick="openTolak(this)">
                                    <span class="msym">cancel</span>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align:center;padding:40px;color:var(--muted);">
                        <span class="msym sz28" style="display:block;margin-bottom:8px;opacity:.4;">assignment</span>
                        Belum ada tugas yang di-assign
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL Tidak Mampu --}}
<div class="modal-backdrop" id="mTidakMampu">
    <div class="modal-box">
        <div class="modal-hd" style="color:#854D0E;">
            <span>Tidak Mampu Menangani</span>
            <button class="modal-close" onclick="closeModal('mTidakMampu')"><span class="msym">close</span></button>
        </div>
        <form method="POST" id="formTidakMampu">
            @csrf
            <div class="modal-bd">
                <div class="field">
                    <label>Alasan <span class="req">*</span></label>
                    <textarea name="alasan_tolak" class="ta" rows="4" maxlength="500" required
                              placeholder="Jelaskan mengapa tidak dapat menangani pengaduan ini..."></textarea>
                    <div style="font-size:11px;color:var(--muted);margin-top:4px;">Pengaduan akan dieskalasi ke admin untuk ditindaklanjuti.</div>
                </div>
            </div>
            <div class="modal-ft">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal('mTidakMampu')">Batal</button>
                <button type="submit" class="btn btn-sm" style="background:#FEF9C3;color:#854D0E;gap:4px;">
                    <span class="msym sz18">report_problem</span> Eskalasi ke Admin
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL Tolak --}}
<div class="modal-backdrop" id="mTolak">
    <div class="modal-box">
        <div class="modal-hd" style="color:#DC2626;">
            <span>Tolak Pengaduan</span>
            <button class="modal-close" onclick="closeModal('mTolak')"><span class="msym">close</span></button>
        </div>
        <form method="POST" id="formTolak">
            @csrf
            <div class="modal-bd">
                <div class="field">
                    <label>Alasan Penolakan <span class="req">*</span></label>
                    <textarea name="alasan_tolak" class="ta" rows="4" maxlength="500" required
                              placeholder="Jelaskan alasan penolakan..."></textarea>
                </div>
            </div>
            <div class="modal-ft">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal('mTolak')">Batal</button>
                <button type="submit" class="btn btn-danger btn-sm">
                    <span class="msym sz18">cancel</span> Tolak Pengaduan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
@push('scripts')
<script>
function openTidakMampu(btn) {
    document.getElementById('formTidakMampu').action = btn.dataset.action;
    openModal('mTidakMampu');
}
function openTolak(btn) {
    document.getElementById('formTolak').action = btn.dataset.action;
    openModal('mTolak');
}
</script>
@endpush
