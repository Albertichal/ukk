@extends('layouts.app')
@section('title','Kategori')
@section('content')

<div style="display:grid;grid-template-columns:300px 1fr;gap:20px;align-items:start;">

    {{-- Form Tambah --}}
    <div class="card">
        <div class="card-hd">Tambah Kategori</div>
        <div class="card-bd">
            <form method="POST" action="{{ route('admin.kategori.store') }}">
                @csrf
                <div class="field">
                    <label>Nama Kategori <span class="req">*</span></label>
                    <input type="text" name="ket_kategori" class="inp"
                           value="{{ old('ket_kategori') }}"
                           maxlength="30" placeholder="Maks. 30 karakter" required>
                    @error('ket_kategori')
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
            <span>Daftar Kategori</span>
            <span style="font-size:12px;color:var(--muted);font-weight:500;">{{ $kategoris->count() }} kategori</span>
        </div>
        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategoris as $i => $k)
                    <tr>
                        <td style="color:var(--muted);">{{ $i+1 }}</td>
                        <td style="font-weight:600;">{{ $k->ket_kategori }}</td>
                        <td>
                            <form method="POST"
                                  action="{{ route('admin.kategori.destroy',$k->id_kategori) }}"
                                  onsubmit="return confirm('Hapus kategori {{ addslashes($k->ket_kategori) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon btn-icon-red" title="Hapus">
                                    <span class="msym">delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align:center;padding:40px;color:var(--muted);">
                            <span class="msym sz28" style="display:block;margin-bottom:8px;opacity:.4;">category</span>
                            Belum ada kategori
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@media (max-width: 640px) {
    /* single column on mobile — handled via inline style below */
}

@endsection
