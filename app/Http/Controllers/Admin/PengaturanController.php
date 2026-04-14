<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        $pengaturan = Pengaturan::all();

        return view('admin.pengaturan', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings.batas_laporan_aktif' => 'required|integer|min:1|max:20',
        ]);
        foreach ($request->settings as $key => $value) {
            Pengaturan::where('key', $key)->update(['value' => $value]);
        }

        return back()->with('success', 'Pengaturan berhasil disimpan');
    }
}
