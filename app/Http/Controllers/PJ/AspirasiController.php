<?php

namespace App\Http\Controllers\PJ;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AspirasiController extends Controller
{
    public function index()
    {
        $aspirasis = Aspirasi::with(['inputAspirasi.user', 'inputAspirasi.kategori', 'inputAspirasi.ruangan'])
            ->where('assigned_to', auth()->id())
            ->whereIn('status', ['Menunggu', 'Proses'])
            ->latest()
            ->get();

        $stats = [
            'assigned' => Aspirasi::where('assigned_to', auth()->id())->whereIn('status', ['Menunggu', 'Proses'])->count(),
            'proses'   => Aspirasi::where('assigned_to', auth()->id())->where('status', 'Proses')->count(),
        ];

        return view('pj.dashboard', compact('aspirasis', 'stats'));
    }

    public function terima(Request $request, $id)
    {
        $aspirasi = Aspirasi::where('id_aspirasi', $id)
            ->where('assigned_to', auth()->id())
            ->where('status', 'Menunggu')
            ->firstOrFail();

        $aspirasi->update(['status' => 'Proses']);

        return back()->with('success', 'Pengaduan diterima dan sedang diproses');
    }

    public function tidakMampu(Request $request, $id)
    {
        $request->validate([
            'alasan_tolak' => 'required|string|max:500',
        ]);

        $aspirasi = Aspirasi::where('id_aspirasi', $id)
            ->where('assigned_to', auth()->id())
            ->whereIn('status', ['Menunggu', 'Proses'])
            ->firstOrFail();

        $aspirasi->update([
            'status'       => 'Tidak_Mampu',
            'alasan_tolak' => $request->alasan_tolak,
        ]);

        return back()->with('success', 'Eskalasi ke admin berhasil');
    }

    public function selesai(Request $request, $id)
    {
        $request->validate([
            'foto_selesai' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $aspirasi = Aspirasi::where('id_aspirasi', $id)
            ->where('assigned_to', auth()->id())
            ->where('status', 'Proses')
            ->firstOrFail();

        $fotoPath = $request->file('foto_selesai')->store('aspirasi/selesai', 'public');

        $aspirasi->update([
            'status'      => 'Selesai',
            'foto_selesai' => $fotoPath,
        ]);

        return back()->with('success', 'Perbaikan selesai');
    }

    public function tolak(Request $request, $id)
    {
        $request->validate([
            'alasan_tolak' => 'required|string|max:500',
        ]);

        $aspirasi = Aspirasi::where('id_aspirasi', $id)
            ->where('assigned_to', auth()->id())
            ->whereIn('status', ['Menunggu', 'Proses'])
            ->firstOrFail();

        $aspirasi->update([
            'status'       => 'Ditolak',
            'alasan_tolak' => $request->alasan_tolak,
        ]);

        return back()->with('success', 'Pengaduan ditolak');
    }
}
