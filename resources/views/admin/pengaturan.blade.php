@extends('layouts.app')
@section('title', 'Pengaturan')
@section('content')

    <div style="max-width:520px;">
        <div class="card">
            <div class="card-hd">
                <span>Pengaturan Aplikasi</span>
            </div>
            <div class="card-bd">
                @if (session('success'))
                    <div class="flash flash-ok" style="margin-bottom:16px;">
                        <span class="msym filled">check_circle</span>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.pengaturan.update') }}">
                    @csrf
                    @foreach ($pengaturan as $p)
                        <div class="field">
                            <label>{{ $p->keterangan ?? $p->key }}</label>
                            @if ($p->key === 'batas_laporan_aktif')
                                <input type="number" name="settings[{{ $p->key }}]" value="{{ $p->value }}"
                                    min="1" max="20" class="inp" style="max-width:120px;">
                                <div style="font-size:12px;color:var(--muted);margin-top:4px;">
                                    Siswa tidak bisa kirim pengaduan baru jika sudah mencapai batas ini (1–20)
                                </div>
                                @error('settings.batas_laporan_aktif')
                                    <div class="err-msg">{{ $message }}</div>
                                @enderror
                            @endif
                        </div>
                    @endforeach

                    <button type="submit" class="btn btn-primary" style="margin-top:8px;">
                        <span class="msym sz18">save</span> Simpan Pengaturan
                    </button>
                </form>
            </div>
        </div>
    </div>

@endsection
