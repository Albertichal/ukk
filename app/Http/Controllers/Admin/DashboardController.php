<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $aspirasis = Aspirasi::with(['inputAspirasi.user', 'inputAspirasi.kategori', 'inputAspirasi.ruangan', 'assignedTo'])
            ->whereIn('status', ['Tidak_Mampu', 'Sedang_Dikerjakan'])
            ->latest()
            ->get();

        $ruangans = Ruangan::with('penanggungjawab')->get();

        $stats = [
            'eskalasi'   => Aspirasi::where('status', 'Tidak_Mampu')->count(),
            'dikerjakan' => Aspirasi::where('status', 'Sedang_Dikerjakan')->count(),
            'selesai'    => Aspirasi::where('status', 'Selesai')->count(),
            'ditolak'    => Aspirasi::where('status', 'Ditolak')->count(),
        ];

        return view('admin.dashboard', compact('aspirasis', 'ruangans', 'stats'));
    }

    public function sedangDikerjakan(Request $request, $id)
    {
        $aspirasi = Aspirasi::where('id_aspirasi', $id)
            ->where('status', 'Tidak_Mampu')
            ->firstOrFail();

        $aspirasi->update(['status' => 'Sedang_Dikerjakan']);

        return back()->with('success', 'Status diperbarui menjadi Sedang Dikerjakan');
    }

    public function tolak(Request $request, $id)
    {
        $request->validate([
            'alasan_tolak' => 'required|string|max:500',
        ]);

        $aspirasi = Aspirasi::findOrFail($id);
        $aspirasi->update([
            'status'       => 'Ditolak',
            'alasan_tolak' => $request->alasan_tolak,
        ]);

        return back()->with('success', 'Pengaduan ditolak');
    }

    public function selesai(Request $request, $id)
    {
        $aspirasi = Aspirasi::where('id_aspirasi', $id)
            ->where('status', 'Sedang_Dikerjakan')
            ->firstOrFail();

        $aspirasi->update(['status' => 'Selesai']);

        return back()->with('success', 'Pengaduan ditandai selesai');
    }

    public function histori()
    {
        $aspirasis = Aspirasi::with(['inputAspirasi.user', 'inputAspirasi.ruangan', 'inputAspirasi.kategori', 'assignedTo'])
            ->whereIn('status', ['Selesai', 'Ditolak'])
            ->latest()
            ->get();

        return view('admin.histori', compact('aspirasis'));
    }
}
