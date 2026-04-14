@extends('layouts.app')
@section('title','Data User')
@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
    <div></div>
    <button class="btn btn-primary btn-sm" onclick="openModal('mAddUser')">
        <span class="msym sz18">person_add</span> Tambah User
    </button>
</div>

<div class="card">
    <div class="card-hd">
        <span>Daftar User</span>
        <span style="font-size:12px;color:var(--muted);font-weight:500;">{{ $users->count() }} user</span>
    </div>
    <div class="tbl-wrap">
        <table class="tbl">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Role</th>
                    <th>Username / NIS</th>
                    <th>Kelas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $i => $user)
                @php
                    $avCls = match($user->role) { 'admin'=>'av-admin','siswa'=>'av-siswa', default=>'av-pj' };
                    $badgeCls = match($user->role) {
                        'admin'  => 'background:#DBEAFE;color:#1D4ED8;',
                        'siswa'  => 'background:#DCFCE7;color:#15803D;',
                        default  => 'background:#FEF9C3;color:#854D0E;'
                    };
                    $badgeLabel = match($user->role) {
                        'admin'=>'Admin','siswa'=>'Siswa',default=>'PJ'
                    };
                @endphp
                <tr>
                    <td style="color:var(--muted);">{{ $i+1 }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="av av-sm {{ $avCls }}">{{ strtoupper(substr($user->name,0,1)) }}</div>
                            <span style="font-weight:600;font-size:13px;">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="sbadge" style="{{ $badgeCls }}">{{ $badgeLabel }}</span>
                    </td>
                    <td style="font-size:13px;color:var(--muted);">{{ $user->username ?? $user->nis ?? '—' }}</td>
                    <td style="font-size:13px;color:var(--muted);">{{ $user->kelas?->nama_kelas ?? '—' }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.users.destroy',$user->id) }}"
                              onsubmit="return confirm('Hapus user {{ addslashes($user->name) }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-icon btn-icon-red" title="Hapus">
                                <span class="msym">delete</span>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:40px;color:var(--muted);">
                        <span class="msym sz28" style="display:block;margin-bottom:8px;opacity:.4;">group</span>
                        Belum ada user
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL Add User --}}
<div class="modal-backdrop {{ $errors->any() ? 'show' : '' }}" id="mAddUser">
    <div class="modal-box" style="max-width:520px;">
        <div class="modal-hd">
            <span>Tambah User Baru</span>
            <button class="modal-close" onclick="closeModal('mAddUser')"><span class="msym">close</span></button>
        </div>
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <div class="modal-bd">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                    <div class="field" style="grid-column:1/-1;">
                        <label>Nama Lengkap <span class="req">*</span></label>
                        <input type="text" name="name" class="inp" value="{{ old('name') }}" maxlength="100" required>
                        @error('name')<div class="err-msg">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label>Role <span class="req">*</span></label>
                        <select name="role" id="roleSelect" class="sel" required>
                            <option value="">Pilih Role</option>
                            <option value="admin"            {{ old('role')==='admin'            ? 'selected':'' }}>Admin</option>
                            <option value="penanggung_jawab" {{ old('role')==='penanggung_jawab' ? 'selected':'' }}>Penanggung Jawab</option>
                            <option value="siswa"            {{ old('role')==='siswa'            ? 'selected':'' }}>Siswa</option>
                        </select>
                        @error('role')<div class="err-msg">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label>Password <span class="req">*</span></label>
                        <input type="password" name="password" class="inp" minlength="6" required>
                        @error('password')<div class="err-msg">{{ $message }}</div>@enderror
                    </div>
                    {{-- Username (admin/PJ) --}}
                    <div class="field field-username" style="display:none;grid-column:1/-1;">
                        <label>Username</label>
                        <input type="text" name="username" class="inp" value="{{ old('username') }}" maxlength="50">
                        @error('username')<div class="err-msg">{{ $message }}</div>@enderror
                    </div>
                    {{-- NIS + Kelas (siswa) --}}
                    <div class="field field-siswa" style="display:none;">
                        <label>NIS</label>
                        <input type="text" name="nis" class="inp" value="{{ old('nis') }}" maxlength="10">
                        @error('nis')<div class="err-msg">{{ $message }}</div>@enderror
                    </div>
                    <div class="field field-siswa" style="display:none;">
                        <label>Kelas</label>
                        <select name="kelas_id" class="sel">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id_kelas }}" {{ old('kelas_id') == $k->id_kelas ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                        @error('kelas_id')<div class="err-msg">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
            <div class="modal-ft">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal('mAddUser')">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm"><span class="msym sz18">save</span> Simpan</button>
            </div>
        </form>
    </div>
</div>

@endsection
@push('scripts')
<script>
    const roleSelect = document.getElementById('roleSelect');
    const fUsername  = document.querySelectorAll('.field-username');
    const fSiswa     = document.querySelectorAll('.field-siswa');
    function toggleRoleFields() {
        const v = roleSelect.value;
        fUsername.forEach(el => el.style.display = (v==='admin'||v==='penanggung_jawab') ? '' : 'none');
        fSiswa.forEach(el    => el.style.display = v==='siswa' ? '' : 'none');
    }
    roleSelect.addEventListener('change', toggleRoleFields);
    toggleRoleFields();
</script>
@endpush
