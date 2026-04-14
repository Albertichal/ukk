@extends('layouts.app')
@section('title','Ruangan')
@section('content')

<div style="display:grid;grid-template-columns:300px 1fr;gap:20px;align-items:start;">

    {{-- Form Tambah --}}
    <div class="card">
        <div class="card-hd">Tambah Ruangan</div>
        <div class="card-bd">
            <form method="POST" action="{{ route('admin.ruangan.store') }}">
                @csrf
                <div class="field">
                    <label>Nama Ruangan <span class="req">*</span></label>
                    <input type="text" name="nama_ruangan" class="inp"
                           value="{{ old('nama_ruangan') }}"
                           maxlength="50" placeholder="Maks. 50 karakter" required>
                    @error('nama_ruangan')
                        <div class="err-msg">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label>Penanggung Jawab <span class="req">*</span></label>
                    <select name="penanggung_jawab_id" class="sel" required>
                        <option value="">-- Pilih PJ --</option>
                        @foreach($pjs as $pj)
                            <option value="{{ $pj->id }}" {{ old('penanggung_jawab_id') == $pj->id ? 'selected' : '' }}>
                                {{ $pj->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('penanggung_jawab_id')
                        <div class="err-msg">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;">
                    <span class="msym sz18">add</span> Tambah
                </button>
            </form>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="card">
        <div class="card-hd">
            <span>Daftar Ruangan</span>
            <span style="font-size:12px;color:var(--muted);font-weight:500;">{{ $ruangans->count() }} ruangan</span>
        </div>
        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Ruangan</th>
                        <th>Penanggung Jawab</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ruangans as $i => $r)
                    <tr>
                        <td style="color:var(--muted);">{{ $i+1 }}</td>
                        <td style="font-weight:600;">{{ $r->nama_ruangan }}</td>
                        <td>
                            @if($r->penanggungjawab)
                                <div style="display:flex;align-items:center;gap:6px;">
                                    <div class="av av-sm av-pj">{{ strtoupper(substr($r->penanggungjawab->name,0,1)) }}</div>
                                    <span style="font-size:13px;">{{ $r->penanggungjawab->name }}</span>
                                </div>
                            @else
                                <span style="color:var(--muted);">—</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;gap:4px;">
                                <button class="btn-icon btn-icon-blue" title="Ganti PJ"
                                        onclick="setGantiPJ('{{ route('admin.ruangan.update',$r->id_ruangan) }}','{{ $r->id_ruangan }}')">
                                    <span class="msym">manage_accounts</span>
                                </button>
                                <form method="POST"
                                      action="{{ route('admin.ruangan.destroy',$r->id_ruangan) }}"
                                      onsubmit="return confirm('Hapus ruangan {{ addslashes($r->nama_ruangan) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon btn-icon-red" title="Hapus">
                                        <span class="msym">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center;padding:40px;color:var(--muted);">
                            <span class="msym sz28" style="display:block;margin-bottom:8px;opacity:.4;">meeting_room</span>
                            Belum ada ruangan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL Ganti PJ --}}
<div class="modal-backdrop" id="mGantiPJ">
    <div class="modal-box">
        <div class="modal-hd">
            <span>Ganti Penanggung Jawab</span>
            <button class="modal-close" onclick="closeModal('mGantiPJ')"><span class="msym">close</span></button>
        </div>
        <form method="POST" id="formGantiPJ">
            @csrf
            <div class="modal-bd">
                <div class="field">
                    <label>Penanggung Jawab Baru <span class="req">*</span></label>
                    <select name="penanggung_jawab_id" class="sel" required>
                        <option value="">-- Pilih PJ --</option>
                        @foreach($pjs as $pj)
                            <option value="{{ $pj->id }}">{{ $pj->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-ft">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal('mGantiPJ')">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm"><span class="msym sz18">save</span> Simpan</button>
            </div>
        </form>
    </div>
</div>

@endsection
@push('scripts')
<script>
function setGantiPJ(url) {
    document.getElementById('formGantiPJ').action = url;
    openModal('mGantiPJ');
}
</script>
@endpush
