<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use App\Models\InputAspirasi;
use App\Models\Kategori;
use App\Models\Pengaturan;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AspirasiController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::all();
        $ruangans = Ruangan::with('penanggungjawab')->get();
        $aktifCount = Aspirasi::whereHas('inputAspirasi', function ($q) {
            $q->where('user_id', auth()->id());
        })->whereNotIn('status', ['Selesai', 'Ditolak'])->count();

        $batas = (int) Pengaturan::nilai('batas_laporan_aktif', 2);

        return view('siswa.form', compact('kategoris', 'ruangans', 'aktifCount', 'batas'));
    }

    public function store(Request $request)
    {
        $aktif = Aspirasi::whereHas('inputAspirasi', function ($q) {
            $q->where('user_id', auth()->id());
        })->whereNotIn('status', ['Selesai', 'Ditolak'])->count();

        $batas = (int) Pengaturan::nilai('batas_laporan_aktif', 2);
        if ($aktif >= $batas) {
            return back()->with('error', "Anda sudah memiliki {$batas} laporan aktif. Tunggu hingga salah satu selesai atau ditolak.");
        }

        $request->validate([
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'ruangan_id' => 'required|exists:ruangan,id_ruangan',
            'lokasi' => 'required|string|max:50',
            'ket' => 'required|string|max:255',
            'foto_laporan' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::transaction(function () use ($request) {
            $ruangan = Ruangan::findOrFail($request->ruangan_id);

            $fotoPath = null;
            if ($request->hasFile('foto_laporan')) {
                $fotoPath = $request->file('foto_laporan')->store('aspirasi/laporan', 'public');
            }

            $input = InputAspirasi::create([
                'user_id' => auth()->id(),
                'id_kategori' => $request->id_kategori,
                'ruangan_id' => $request->ruangan_id,
                'lokasi' => $request->lokasi,
                'ket' => $request->ket,
            ]);

            Aspirasi::create([
                'id_pelaporan' => $input->id_pelaporan,
                'id_kategori' => $request->id_kategori,
                'status' => 'Menunggu',
                'assigned_to' => $ruangan->penanggung_jawab_id,
                'foto_laporan' => $fotoPath,
            ]);
        });

        return redirect()->route('siswa.riwayat')->with('success', 'Pengaduan berhasil terkirim ke PJ ruangan');
    }

    public function riwayat()
    {
        $riwayats = auth()->user()
            ->inputAspirasi()
            ->with(['kategori', 'ruangan', 'aspirasi.assignedTo'])
            ->latest()
            ->get();

        return view('siswa.riwayat', compact('riwayats'));
    }
}
