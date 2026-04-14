<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::all();

        return view('admin.kelas', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:20|unique:kelas,nama_kelas',
        ]);

        Kelas::create(['nama_kelas' => $request->nama_kelas]);

        return back()->with('success', 'Kelas berhasil ditambahkan');
    }

    public function destroy($id)
    {
        Kelas::where('id_kelas', $id)->firstOrFail()->delete();

        return back()->with('success', 'Kelas berhasil dihapus');
    }
}
