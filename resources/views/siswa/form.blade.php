@extends('layouts.app')
@section('title','Form Pengaduan')
@section('content')

<div style="max-width:640px;margin:0 auto;">

    {{-- Kuota info --}}
    @if($aktifCount >= 2)
        <div class="alert-bar" style="background:#FEF2F2;border:1px solid #FECACA;color:#DC2626;margin-bottom:16px;">
            <span class="msym filled">error</span>
            <div>
                <div style="font-weight:700;">Kuota laporan penuh</div>
                <div style="font-size:13px;margin-top:2px;">Anda sudah memiliki 2 laporan aktif. Silakan tunggu hingga laporan selesai atau ditolak.</div>
            </div>
        </div>
    @elseif($aktifCount === 1)
        <div class="alert-bar alert-warn" style="margin-bottom:16px;">
            <span class="msym filled">warning</span>
            <div>
                <div style="font-weight:700;">1 laporan aktif</div>
                <div style="font-size:13px;margin-top:2px;">Anda memiliki 1 laporan aktif. Sisa kuota: 1 laporan lagi.</div>
            </div>
        </div>
    @else
        <div class="alert-bar" style="background:#F0FDF4;border:1px solid #BBF7D0;color:#15803D;margin-bottom:16px;">
            <span class="msym filled">check_circle</span>
            <div style="font-size:13px;">Anda dapat mengirim hingga <strong>2 laporan</strong> secara bersamaan.</div>
        </div>
    @endif

    <div class="card">
        <div class="card-hd" style="flex-direction:column;align-items:flex-start;gap:2px;">
            <span>Form Pengaduan Sarana</span>
            <span style="font-size:12px;color:var(--muted);font-weight:400;">Laporkan kondisi sarana yang perlu diperbaiki</span>
        </div>
        <div class="card-bd">
    @if($aktifCount >= 2)
            <div style="text-align:center;padding:32px 0;color:var(--muted);">
                <span class="msym sz28" style="display:block;margin-bottom:10px;opacity:.4;">block</span>
                Form tidak tersedia saat kuota penuh.
                <div style="margin-top:12px;">
                    <a href="{{ route('siswa.riwayat') }}" class="btn btn-secondary btn-sm">
                        <span class="msym sz18">receipt_long</span> Lihat Riwayat Saya
                    </a>
                </div>
            </div>
    @else
            <form method="POST" action="{{ route('siswa.aspirasi.store') }}">
                @csrf

                <div class="field">
                    <label><span class="msym sz18" style="vertical-align:middle;margin-right:4px;">grid_view</span>Kategori <span class="req">*</span></label>
                    <select name="id_kategori" class="sel" required>
                        <option value="">-- Pilih Kategori Kerusakan --</option>
                        @foreach($kategoris as $k)
                            <option value="{{ $k->id_kategori }}" {{ old('id_kategori')==$k->id_kategori ? 'selected':'' }}>
                                {{ $k->ket_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_kategori')<div class="err-msg">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label><span class="msym sz18" style="vertical-align:middle;margin-right:4px;">meeting_room</span>Ruangan <span class="req">*</span></label>
                    <select name="ruangan_id" class="sel" required>
                        <option value="">-- Pilih Ruangan --</option>
                        @foreach($ruangans as $r)
                            <option value="{{ $r->id_ruangan }}" {{ old('ruangan_id')==$r->id_ruangan ? 'selected':'' }}>
                                {{ $r->nama_ruangan }}{{ $r->penanggungjawab ? ' (PJ: '.$r->penanggungjawab->name.')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    <div style="font-size:11px;color:var(--muted);margin-top:4px;">
                        <span class="msym sz18" style="vertical-align:middle;opacity:.6;">info</span>
                        Pengaduan akan langsung dikirim ke PJ ruangan ini
                    </div>
                    @error('ruangan_id')<div class="err-msg">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label><span class="msym sz18" style="vertical-align:middle;margin-right:4px;">location_on</span>Lokasi <span class="req">*</span></label>
                    <input type="text" name="lokasi" id="lokasiInp" class="inp"
                           value="{{ old('lokasi') }}"
                           maxlength="50"
                           placeholder="Contoh: Dekat pintu masuk, sudut kiri" required>
                    <div class="char-counter"><span id="lokasiCt">{{ strlen(old('lokasi','')) }}</span>/50</div>
                    @error('lokasi')<div class="err-msg">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label><span class="msym sz18" style="vertical-align:middle;margin-right:4px;">description</span>Keterangan <span class="req">*</span></label>
                    <textarea name="ket" id="ketInp" class="ta" rows="5"
                              maxlength="255"
                              placeholder="Jelaskan kondisi kerusakan/masalah secara detail..."
                              required>{{ old('ket') }}</textarea>
                    <div class="char-counter"><span id="ketCt">{{ strlen(old('ket','')) }}</span>/255</div>
                    @error('ket')<div class="err-msg">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:13px;">
                    <span class="msym sz18">send</span> Kirim Pengaduan
                </button>
            </form>
    @endif
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
    function counter(inputId, countId) {
        const el = document.getElementById(inputId);
        const ct = document.getElementById(countId);
        if (!el || !ct) return;
        ct.textContent = el.value.length;
        el.addEventListener('input', () => ct.textContent = el.value.length);
    }
    counter('lokasiInp','lokasiCt');
    counter('ketInp','ketCt');
</script>
@endpush
