@extends('layouts.app')
@section('title','Form Pengaduan')
@section('content')

<div style="max-width:640px;margin:0 auto;">
    <div class="card">
        <div class="card-hd" style="flex-direction:column;align-items:flex-start;gap:2px;">
            <span>Form Pengaduan Sarana</span>
            <span style="font-size:12px;color:var(--muted);font-weight:400;">Laporkan kondisi sarana yang perlu diperbaiki</span>
        </div>
        <div class="card-bd">
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
                    <label><span class="msym sz18" style="vertical-align:middle;margin-right:4px;">location_on</span>Lokasi <span class="req">*</span></label>
                    <input type="text" name="lokasi" id="lokasiInp" class="inp"
                           value="{{ old('lokasi') }}"
                           maxlength="50"
                           placeholder="Contoh: Kelas XII IPA 1, Toilet Lantai 2" required>
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
