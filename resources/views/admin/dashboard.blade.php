@extends('layouts.app')
@section('title','Dashboard Admin')
@section('content')

{{-- Stats --}}
<div class="stats-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;">
    <div class="stat-card">
        <div class="stat-icon" style="background:#FEE2E2;">
            <span class="msym" style="color:#DC2626;">escalator_warning</span>
        </div>
        <div>
            <div class="stat-num" style="color:#DC2626;">{{ $stats['eskalasi'] }}</div>
            <div class="stat-lbl">Eskalasi Baru</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#CFE2FF;">
            <span class="msym" style="color:#084298;">construction</span>
        </div>
        <div>
            <div class="stat-num" style="color:#084298;">{{ $stats['dikerjakan'] }}</div>
            <div class="stat-lbl">Sedang Dikerjakan</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#D1E7DD;">
            <span class="msym" style="color:#0A3622;">check_circle</span>
        </div>
        <div>
            <div class="stat-num" style="color:#0A3622;">{{ $stats['selesai'] }}</div>
            <div class="stat-lbl">Selesai</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#F8D7DA;">
            <span class="msym" style="color:#842029;">cancel</span>
        </div>
        <div>
            <div class="stat-num" style="color:#842029;">{{ $stats['ditolak'] }}</div>
            <div class="stat-lbl">Ditolak</div>
        </div>
    </div>
</div>

{{-- Tabel Eskalasi --}}
<div class="card">
    <div class="card-hd">
        <span>Eskalasi dari Penanggung Jawab</span>
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
                    <th>Keterangan</th>
                    <th>Status</th>
                    <th>PJ</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($aspirasis as $i => $asp)
                @php
                    $s   = $asp->status;
                    $cls = $s === 'Tidak_Mampu' ? 's-tidak-mampu' : 's-sdikerjakan';
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
                    <td style="max-width:110px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $asp->inputAspirasi?->lokasi ?? '—' }}</td>
                    <td style="max-width:150px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:var(--muted);font-size:13px;">{{ $asp->inputAspirasi?->ket ?? '—' }}</td>
                    <td><span class="sbadge {{ $cls }}">{{ str_replace('_',' ',$s) }}</span></td>
                    <td style="font-size:13px;">{{ $asp->assignedTo?->name ?? '—' }}</td>
                    <td>
                        <div style="display:flex;gap:4px;flex-wrap:wrap;">
                            @if($s === 'Tidak_Mampu')
                                <form method="POST" action="{{ route('admin.sedang_dikerjakan',$asp->id_aspirasi) }}"
                                      onsubmit="return confirm('Tandai Sedang Dikerjakan?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm" style="background:#CFE2FF;color:#084298;gap:4px;">
                                        <span class="msym sz18">engineering</span> Kerjakan
                                    </button>
                                </form>
                                <button class="btn-icon btn-icon-red" title="Tolak"
                                        onclick="setTolakAction('{{ route('admin.tolak',$asp->id_aspirasi) }}')">
                                    <span class="msym">cancel</span>
                                </button>
                            @elseif($s === 'Sedang_Dikerjakan')
                                <form method="POST" action="{{ route('admin.selesai',$asp->id_aspirasi) }}"
                                      onsubmit="return confirm('Tandai selesai?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" style="gap:4px;">
                                        <span class="msym sz18">check_circle</span> Selesai
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center;padding:40px;color:var(--muted);">
                        <span class="msym sz28" style="display:block;margin-bottom:8px;opacity:.4;">inbox</span>
                        Belum ada eskalasi masuk
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL Tolak --}}
<div class="modal-backdrop" id="mTolakAdmin">
    <div class="modal-box">
        <div class="modal-hd" style="color:#DC2626;">
            <span>Tolak Pengaduan</span>
            <button class="modal-close" onclick="closeModal('mTolakAdmin')"><span class="msym">close</span></button>
        </div>
        <form method="POST" id="formTolakAdmin">
            @csrf
            <div class="modal-bd">
                <div class="field">
                    <label>Alasan Penolakan <span class="req">*</span></label>
                    <textarea name="alasan_tolak" class="ta" rows="4" maxlength="500" required
                              placeholder="Jelaskan alasan penolakan..."></textarea>
                </div>
            </div>
            <div class="modal-ft">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal('mTolakAdmin')">Batal</button>
                <button type="submit" class="btn btn-danger btn-sm"><span class="msym sz18">cancel</span> Tolak</button>
            </div>
        </form>
    </div>
</div>

@endsection
@push('scripts')
<script>
function setTolakAction(url) {
    document.getElementById('formTolakAdmin').action = url;
    openModal('mTolakAdmin');
}
</script>
@endpush
