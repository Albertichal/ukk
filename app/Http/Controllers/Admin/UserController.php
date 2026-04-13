<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('role')->orderBy('name')->get();

        return view('admin.users', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'role'     => 'required|in:admin,siswa,penanggung_jawab',
            'password' => 'required|min:6',
            'username' => 'required_if:role,admin|required_if:role,penanggung_jawab|nullable|unique:users,username|max:50',
            'nis'      => 'required_if:role,siswa|nullable|unique:users,nis|max:10',
            'kelas'    => 'required_if:role,siswa|nullable|max:10',
        ]);

        User::create([
            'name'     => $request->name,
            'role'     => $request->role,
            'password' => bcrypt($request->password),
            'username' => $request->username,
            'nis'      => $request->nis,
            'kelas'    => $request->kelas,
        ]);

        return back()->with('success', 'User berhasil ditambahkan');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'User berhasil dihapus');
    }
}
