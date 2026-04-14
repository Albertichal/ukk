<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('kelas')->orderBy('role')->orderBy('name')->get();
        $kelas = Kelas::all();

        return view('admin.users', compact('users', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'role'     => 'required|in:admin,siswa,penanggung_jawab',
            'password' => 'required|min:6',
            'username' => 'required_if:role,admin|required_if:role,penanggung_jawab|nullable|unique:users,username|max:50',
            'nis'      => 'required_if:role,siswa|nullable|unique:users,nis|max:10',
            'kelas_id' => 'required_if:role,siswa|nullable|exists:kelas,id_kelas',
        ]);

        User::create([
            'name'     => $request->name,
            'role'     => $request->role,
            'password' => bcrypt($request->password),
            'username' => $request->username,
            'nis'      => $request->nis,
            'kelas_id' => $request->kelas_id,
        ]);

        return back()->with('success', 'User berhasil ditambahkan');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return back()->with('success', 'User berhasil dihapus');
    }
}
