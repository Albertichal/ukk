@extends('layouts.app')
@section('title','Dashboard')
@section('content')

{{-- Stats --}}
<div class="stats-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;">
    <div class="stat-card">
        <div class="stat-icon" style="background:#EFF6FF;">
            <span class="msym" style="color:#1D4ED8;">inbox</span>
        </div>
        <div>
            <div class="stat-num" style="color:#1D4ED8;">{{ $stats['total'] }}</div>
            <div class="stat-lbl">Total Aspirasi</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#FFF3CD;">
            <span class="msym" style="color:#856404;">hourglass_empty</span>
        </div>
        <div>
            <div class="stat-num" style="color:#856404;">{{ $stats['menunggu'] }}</div>
            <div class="stat-lbl">Menunggu</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#CFF4FC;">
            <span class="msym" style="color:#055160;">construction</span>
        </div>
        <div>
            <div class="stat-num" style="color:#055160;">{{ $stats['diproses'] }}</div>
            <div class="stat-lbl">Diproses</div>
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
</div>

{{-- Alert Ditolak PJ --}}
@if($tolakPJs->count() > 0)
<div class="alert-bar alert-warn" style="margin-bottom:16px;">
    <span class="msym filled">warning</span>
    <strong>{{ $tolakPJs->count() }} pengaduan</strong>&nbsp;ditolak oleh PJ, menunggu konfirmasi Anda.
</div>
@endif

{{-- Filter --}}
<div class="card" style="margin-bottom:16px;">
    <div class="card-bd" style="padding:14px 20px;">
        <form method="GET" action="{{ route('admin.dashboard') }}">
            <div style="display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end;">
                <div style="flex:1;min-width:120px;">
                    <label style="display:block;font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Status</label>
                    <select name="status" class="sel" style="height:38px;padding:0 10px;">
                        <option value="">Semua Status</option>
                        @foreach(['Menunggu','Diassign','Proses','Ditolak_PJ'] as $s)
                            <option value="{{ $s }}" {{ $filters['status'] === $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="flex:1;min-width:130px;">
                    <label style="display:block;font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Kategori</label>
                    <select name="kategori" class="sel" style="height:38px;padding:0 10px;">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $k)
                            <option value="{{ $k->id_kategori }}" {{ $filters['kategori'] == $k->id_kategori ? 'selected' : '' }}>{{ $k->ket_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="flex:1;min-width:130px;">
                    <label style="display:block;font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Tanggal</label>
                    <input type="date" name="tanggal" class="inp" style="height:38px;padding:0 10px;" value="{{ $filters['tanggal'] }}">
                </div>
                <div style="flex:1;min-width:150px;">
                    <label style="display:block;font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Siswa</label>
                    <select name="user_id" class="sel" style="height:38px;padding:0 10px;">
                        <option value="">Semua Siswa</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ $filters['user_id'] == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->nis }})</option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex;gap:8px;flex-shrink:0;">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <span class="msym sz18">filter_alt</span> Filter
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-hd">
        <span>Aspirasi Masuk</span>
        <span style="font-size:12px;color:var(--muted);font-weight:500;">{{ $aspirasis->count() }} data</span>
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
                    $s = $asp->status;
                    $cls = match($s) {
                        'Menunggu'   => 's-menunggu',
                        'Diassign'   => 's-diassign',
                        'Proses'     => 's-proses',
                        'Ditolak_PJ' => 's-ditolakpj',
                        default      => 's-menunggu',
                    };
                @endphp
                <tr>
                    <td style="color:var(--muted);">{{ $i+1 }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div class="av av-sm av-siswa">{{ strtoupper(substr($asp->inputAspirasi?->user?->name ?? 'S',0,1)) }}</div>
                            <div>
                                <div style="font-weight:600;font-size:13px;">{{ $asp->inputAspirasi?->user?->name ?? '-' }}</div>
                                <div style="font-size:11px;color:var(--muted);">{{ $asp->inputAspirasi?->user?->kelas ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $asp->kategori?->ket_kategori ?? '-' }}</td>
                    <td style="max-width:130px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $asp->inputAspirasi?->lokasi ?? '-' }}</td>
                    <td style="max-width:160px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $asp->inputAspirasi?->ket ?? '-' }}</td>
                    <td><span class="sbadge {{ $cls }}">{{ $s }}</span></td>
                    <td style="color:var(--muted);font-size:12px;white-space:nowrap;">{{ $asp->created_at->format('d M Y') }}</td>
                    <td>
                        <div style="display:flex;gap:4px;">
                            @if($s === 'Menunggu')
                                <button class="btn-icon btn-icon-blue"
                                        title="Assign PJ"
                                        onclick="setModalAction('formAssign','{{ route('admin.assign',$asp->id_aspirasi) }}');openModal('mAssign')">
                                    <span class="msym">person_add</span>
                                </button>
                                <button class="btn-icon btn-icon-red"
                                        title="Tolak"
                                        onclick="setModalAction('formTolakAdmin','{{ route('admin.tolak',$asp->id_aspirasi) }}');openModal('mTolakAdmin')">
                                    <span class="msym">cancel</span>
                                </button>
                            @elseif(in_array($s,['Diassign','Proses']))
                                <button class="btn-icon btn-icon-green"
                                        title="Kirim Feedback"
                                        onclick="setModalAction('formFeedback','{{ route('admin.feedback',$asp->id_aspirasi) }}');openModal('mFeedback')">
                                    <span class="msym">rate_review</span>
                                </button>
                            @elseif($s === 'Ditolak_PJ')
                                <button class="btn-icon btn-icon-red"
                                        title="Konfirmasi Tolak"
                                        onclick="setModalAction('formKonfirmasiTolak','{{ route('admin.konfirmasi_tolak',$asp->id_aspirasi) }}');document.getElementById('alasanPJSpan').textContent='{{ addslashes($asp->alasan_tolak ?? '-') }}';openModal('mKonfirmasiTolak')">
                                    <span class="msym">gavel</span>
                                </button>
                            @else
                                <span style="color:var(--muted);font-size:12px;">—</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:40px;color:var(--muted);">
                        <span class="msym sz28" style="display:block;margin-bottom:8px;opacity:.4;">inbox</span>
                        Belum ada pengaduan masuk
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL: Assign --}}
<div class="modal-backdrop" id="mAssign">
    <div class="modal-box">
        <div class="modal-hd">
            <span>Assign Penanggung Jawab</span>
            <button class="modal-close" onclick="closeModal('mAssign')"><span class="msym">close</span></button>
        </div>
        <form method="POST" id="formAssign">
            @csrf
            <div class="modal-bd">
                <div class="field">
                    <label>Pilih Penanggung Jawab <span class="req">*</span></label>
                    <select name="assigned_to" class="sel" required>
                        <option value="">-- Pilih PJ --</option>
                        @foreach($pjs as $pj)
                            <option value="{{ $pj->id }}">{{ $pj->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-ft">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal('mAssign')">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm"><span class="msym sz18">person_add</span> Assign</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL: Feedback --}}
<div class="modal-backdrop" id="mFeedback">
    <div class="modal-box">
        <div class="modal-hd">
            <span>Kirim Umpan Balik</span>
            <button class="modal-close" onclick="closeModal('mFeedback')"><span class="msym">close</span></button>
        </div>
        <form method="POST" id="formFeedback">
            @csrf
            <div class="modal-bd">
                <div class="field">
                    <label>Umpan Balik <span class="req">*</span></label>
                    <textarea name="feedback" class="ta" rows="4" maxlength="500" required
                              placeholder="Tuliskan hasil penanganan / umpan balik..."></textarea>
                </div>
            </div>
            <div class="modal-ft">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal('mFeedback')">Batal</button>
                <button type="submit" class="btn btn-success btn-sm"><span class="msym sz18">rate_review</span> Kirim</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL: Tolak Admin --}}
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
                    <label>Alasan Penolakan</label>
                    <textarea name="alasan_tolak" class="ta" rows="3" maxlength="500"
                              placeholder="Opsional — kosongkan jika tidak perlu alasan"></textarea>
                </div>
            </div>
            <div class="modal-ft">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal('mTolakAdmin')">Batal</button>
                <button type="submit" class="btn btn-danger btn-sm"><span class="msym sz18">cancel</span> Tolak</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL: Konfirmasi Tolak PJ --}}
<div class="modal-backdrop" id="mKonfirmasiTolak">
    <div class="modal-box">
        <div class="modal-hd" style="color:#DC2626;">
            <span>Konfirmasi Tolak dari PJ</span>
            <button class="modal-close" onclick="closeModal('mKonfirmasiTolak')"><span class="msym">close</span></button>
        </div>
        <form method="POST" id="formKonfirmasiTolak">
            @csrf
            <div class="modal-bd">
                <div style="background:#FFF3CD;border:1px solid #FFC107;border-radius:8px;padding:12px;margin-bottom:16px;">
                    <div style="font-size:11px;font-weight:700;color:#856404;text-transform:uppercase;margin-bottom:4px;">Alasan PJ</div>
                    <div style="font-size:13px;color:#856404;" id="alasanPJSpan">-</div>
                </div>
                <div class="field">
                    <label>Tambah Alasan untuk Siswa</label>
                    <textarea name="alasan_tolak" class="ta" rows="3" maxlength="500"
                              placeholder="Tambah alasan opsional untuk siswa"></textarea>
                </div>
            </div>
            <div class="modal-ft">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal('mKonfirmasiTolak')">Batal</button>
                <button type="submit" class="btn btn-danger btn-sm"><span class="msym sz18">gavel</span> Konfirmasi Tolak</button>
            </div>
        </form>
    </div>
</div>

@endsection
@push('scripts')
<script>
function setModalAction(formId, url) {
    document.getElementById(formId).action = url;
}
</script>
@endpush
