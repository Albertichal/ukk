<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'status'   => $request->status,
            'kategori' => $request->kategori,
            'tanggal'  => $request->tanggal,
            'user_id'  => $request->user_id,
        ];

        $aspirasis = Aspirasi::with(['inputAspirasi.user', 'inputAspirasi.kategori', 'kategori', 'assignedTo'])
            ->whereNotIn('status', ['Selesai', 'Ditolak'])
            ->when($filters['status'],   fn($q, $v) => $q->where('status', $v))
            ->when($filters['kategori'], fn($q, $v) => $q->where('id_kategori', $v))
            ->when($filters['tanggal'],  fn($q, $v) => $q->whereDate('created_at', $v))
            ->when($filters['user_id'],  fn($q, $v) => $q->whereHas('inputAspirasi', fn($q2) => $q2->where('user_id', $v)))
            ->latest()
            ->get();

        $tolakPJs = Aspirasi::with(['inputAspirasi.user', 'inputAspirasi.kategori', 'kategori'])
            ->where('status', 'Ditolak_PJ')
            ->latest()
            ->get();

        $kategoris = Kategori::all();
        $users     = User::where('role', 'siswa')->get();
        $pjs       = User::where('role', 'penanggung_jawab')->get();

        $stats = [
            'total'    => Aspirasi::count(),
            'menunggu' => Aspirasi::where('status', 'Menunggu')->count(),
            'diproses' => Aspirasi::whereIn('status', ['Diassign', 'Proses'])->count(),
            'selesai'  => Aspirasi::where('status', 'Selesai')->count(),
        ];

        return view('admin.dashboard', compact('aspirasis', 'tolakPJs', 'kategoris', 'users', 'pjs', 'filters', 'stats'));
    }

    public function assign(Request $request, $id)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $aspirasi = Aspirasi::findOrFail($id);
        $aspirasi->update([
            'assigned_to' => $request->assigned_to,
            'status'      => 'Diassign',
        ]);

        return back()->with('success', 'Pengaduan berhasil di-assign');
    }

    public function feedback(Request $request, $id)
    {
        $request->validate([
            'feedback' => 'required|string|max:500',
        ]);

        $aspirasi = Aspirasi::findOrFail($id);
        $aspirasi->update([
            'feedback' => $request->feedback,
            'status'   => 'Selesai',
        ]);

        return back()->with('success', 'Umpan balik berhasil dikirim');
    }

    public function tolak(Request $request, $id)
    {
        $request->validate([
            'alasan_tolak' => 'nullable|string|max:500',
        ]);

        $aspirasi = Aspirasi::findOrFail($id);
        $aspirasi->update([
            'status'       => 'Ditolak',
            'alasan_tolak' => $request->alasan_tolak,
        ]);

        return back()->with('success', 'Pengaduan ditolak');
    }

    public function konfirmasiTolak(Request $request, $id)
    {
        $request->validate([
            'alasan_tolak' => 'nullable|string|max:500',
        ]);

        $aspirasi = Aspirasi::where('id_aspirasi', $id)
            ->where('status', 'Ditolak_PJ')
            ->firstOrFail();

        $aspirasi->update([
            'status'       => 'Ditolak',
            'alasan_tolak' => $request->alasan_tolak ?? $aspirasi->alasan_tolak,
        ]);

        return back()->with('success', 'Pengaduan ditolak dan siswa telah dinotifikasi');
    }

    public function histori()
    {
        $aspirasis = Aspirasi::with(['inputAspirasi.user', 'inputAspirasi.kategori', 'kategori', 'assignedTo'])
            ->whereIn('status', ['Selesai', 'Ditolak'])
            ->latest()
            ->get();

        return view('admin.histori', compact('aspirasis'));
    }
}
