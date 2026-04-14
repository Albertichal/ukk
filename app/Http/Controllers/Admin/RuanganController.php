<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use App\Models\User;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function index()
    {
        $ruangans = Ruangan::with('penanggungjawab')->get();
        $pjs      = User::where('role', 'penanggung_jawab')->get();

        return view('admin.ruangan', compact('ruangans', 'pjs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_ruangan'        => 'required|string|max:50|unique:ruangan,nama_ruangan',
            'penanggung_jawab_id' => 'required|exists:users,id',
        ]);

        Ruangan::create([
            'nama_ruangan'        => $request->nama_ruangan,
            'penanggung_jawab_id' => $request->penanggung_jawab_id,
        ]);

        return back()->with('success', 'Ruangan berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'penanggung_jawab_id' => 'required|exists:users,id',
        ]);

        $ruangan = Ruangan::where('id_ruangan', $id)->firstOrFail();
        $ruangan->update(['penanggung_jawab_id' => $request->penanggung_jawab_id]);

        return back()->with('success', 'PJ ruangan berhasil diubah');
    }

    public function destroy($id)
    {
        Ruangan::where('id_ruangan', $id)->firstOrFail()->delete();

        return back()->with('success', 'Ruangan berhasil dihapus');
    }
}
