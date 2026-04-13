<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use App\Models\InputAspirasi;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AspirasiController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::all();

        return view('siswa.form', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'lokasi'      => 'required|string|max:50',
            'ket'         => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            $input = InputAspirasi::create([
                'user_id'     => auth()->id(),
                'id_kategori' => $request->id_kategori,
                'lokasi'      => $request->lokasi,
                'ket'         => $request->ket,
            ]);

            Aspirasi::create([
                'id_pelaporan' => $input->id_pelaporan,
                'id_kategori'  => $request->id_kategori,
                'status'       => 'Menunggu',
            ]);
        });

        return redirect()->route('siswa.riwayat')->with('success', 'Pengaduan berhasil dikirim');
    }

    public function riwayat()
    {
        $riwayats = auth()->user()
            ->inputAspirasi()
            ->with(['kategori', 'aspirasi.assignedTo'])
            ->latest()
            ->get();

        return view('siswa.riwayat', compact('riwayats'));
    }
}
