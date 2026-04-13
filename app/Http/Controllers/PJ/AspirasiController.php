<?php

namespace App\Http\Controllers\PJ;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use Illuminate\Http\Request;

class AspirasiController extends Controller
{
    public function index()
    {
        $aspirasis = Aspirasi::with(['inputAspirasi.user', 'inputAspirasi.kategori', 'kategori'])
            ->where('assigned_to', auth()->id())
            ->whereIn('status', ['Diassign', 'Proses'])
            ->latest()
            ->get();

        $stats = [
            'assigned' => Aspirasi::where('assigned_to', auth()->id())->whereIn('status', ['Diassign', 'Proses'])->count(),
            'proses'   => Aspirasi::where('assigned_to', auth()->id())->where('status', 'Proses')->count(),
        ];

        return view('pj.dashboard', compact('aspirasis', 'stats'));
    }

    public function updateStatus(Request $request, $id)
    {
        $aspirasi = Aspirasi::where('id_aspirasi', $id)
            ->where('assigned_to', auth()->id())
            ->firstOrFail();

        $aspirasi->update(['status' => 'Proses']);

        return back()->with('success', 'Status diperbarui menjadi Proses');
    }

    public function selesai(Request $request, $id)
    {
        $aspirasi = Aspirasi::where('id_aspirasi', $id)
            ->where('assigned_to', auth()->id())
            ->firstOrFail();

        $aspirasi->update(['status' => 'Selesai']);

        return back()->with('success', 'Perbaikan dilaporkan selesai');
    }

    public function tolak(Request $request, $id)
    {
        $request->validate([
            'alasan_tolak' => 'required|string|max:500',
        ]);

        $aspirasi = Aspirasi::where('id_aspirasi', $id)
            ->where('assigned_to', auth()->id())
            ->whereIn('status', ['Diassign', 'Proses'])
            ->firstOrFail();

        $aspirasi->update([
            'status'       => 'Ditolak_PJ',
            'alasan_tolak' => $request->alasan_tolak,
        ]);

        return back()->with('success', 'Pengaduan ditolak, menunggu konfirmasi admin');
    }
}
