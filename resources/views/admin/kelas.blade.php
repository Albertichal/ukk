@extends('layouts.app')
@section('title','Kelas')
@section('content')

<div style="display:grid;grid-template-columns:300px 1fr;gap:20px;align-items:start;">

    {{-- Form Tambah --}}
    <div class="card">
        <div class="card-hd">Tambah Kelas</div>
        <div class="card-bd">
            <form method="POST" action="{{ route('admin.kelas.store') }}">
                @csrf
                <div class="field">
                    <label>Nama Kelas <span class="req">*</span></label>
                    <input type="text" name="nama_kelas" class="inp"
                           value="{{ old('nama_kelas') }}"
                           maxlength="20" placeholder="Contoh: XII IPA 1" required>
                    @error('nama_kelas')
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
            <span>Daftar Kelas</span>
            <span style="font-size:12px;color:var(--muted);font-weight:500;">{{ $kelas->count() }} kelas</span>
        </div>
        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Kelas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelas as $i => $k)
                    <tr>
                        <td style="color:var(--muted);">{{ $i+1 }}</td>
                        <td style="font-weight:600;">{{ $k->nama_kelas }}</td>
                        <td>
                            <form method="POST"
                                  action="{{ route('admin.kelas.destroy',$k->id_kelas) }}"
                                  onsubmit="return confirm('Hapus kelas {{ addslashes($k->nama_kelas) }}?')">
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
                            <span class="msym sz28" style="display:block;margin-bottom:8px;opacity:.4;">class</span>
                            Belum ada kelas
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
