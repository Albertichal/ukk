<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class PantauController extends Controller
{
    public function index()
    {
        $ruangans = Ruangan::with(['penanggungjawab', 'inputAspirasi.aspirasi'])
            ->get()
            ->map(function ($ruangan) {
                $ruangan->aktif_count = $ruangan->inputAspirasi->filter(function ($input) {
                    return $input->aspirasi && !in_array($input->aspirasi->status, ['Selesai', 'Ditolak']);
                })->count();
                return $ruangan;
            });

        return view('admin.pantau.index', compact('ruangans'));
    }

    public function show($id)
    {
        $ruangan  = Ruangan::with('penanggungjawab')->findOrFail($id);
        $aspirasis = Aspirasi::with(['inputAspirasi.user.kelas', 'inputAspirasi.kategori', 'assignedTo'])
            ->whereHas('inputAspirasi', fn($q) => $q->where('ruangan_id', $id))
            ->latest()
            ->get();

        return view('admin.pantau.show', compact('ruangan', 'aspirasis'));
    }
}
