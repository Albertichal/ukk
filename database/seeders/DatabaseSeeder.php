<?php

namespace Database\Seeders;

use App\Models\Aspirasi;
use App\Models\InputAspirasi;
use App\Models\Kelas;
use App\Models\Kategori;
use App\Models\Ruangan;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Skip base data if already seeded
        if (User::where('username', 'admin')->doesntExist()) {
        // 1. Kelas
        Kelas::insert([
            ['nama_kelas' => 'X IPA 1'],
            ['nama_kelas' => 'X IPA 2'],
            ['nama_kelas' => 'X IPS 1'],
            ['nama_kelas' => 'X IPS 2'],
            ['nama_kelas' => 'XI IPA 1'],
            ['nama_kelas' => 'XI IPA 2'],
            ['nama_kelas' => 'XI IPS 1'],
            ['nama_kelas' => 'XI IPS 2'],
            ['nama_kelas' => 'XII IPA 1'],
            ['nama_kelas' => 'XII IPA 2'],
            ['nama_kelas' => 'XII IPS 1'],
            ['nama_kelas' => 'XII IPS 2'],
        ]);

        // 2. Users
        User::create(['name' => 'Administrator', 'username' => 'admin',    'nis' => null,      'kelas_id' => null, 'password' => bcrypt('admin123'),  'role' => 'admin']);
        User::create(['name' => 'Budi Santoso',  'username' => 'budi.pj', 'nis' => null,      'kelas_id' => null, 'password' => bcrypt('pj123'),     'role' => 'penanggung_jawab']);
        User::create(['name' => 'Ahmad Fauzi',   'username' => null,       'nis' => '2024001', 'kelas_id' => 9,    'password' => bcrypt('siswa123'),  'role' => 'siswa']);
        User::create(['name' => 'Siti Rahayu',   'username' => null,       'nis' => '2024002', 'kelas_id' => 12,   'password' => bcrypt('siswa123'),  'role' => 'siswa']);

        // 3. Kategori
        Kategori::insert([
            ['ket_kategori' => 'Kebersihan'],
            ['ket_kategori' => 'Peralatan Kelas'],
            ['ket_kategori' => 'Toilet'],
            ['ket_kategori' => 'Lapangan'],
            ['ket_kategori' => 'Laboratorium'],
        ]);

        // 4. Ruangan (penanggung_jawab_id = 2 → Budi Santoso)
        Ruangan::insert([
            ['nama_ruangan' => 'Lab IT',           'penanggung_jawab_id' => 2],
            ['nama_ruangan' => 'Ruang Kelas A',    'penanggung_jawab_id' => 2],
            ['nama_ruangan' => 'Toilet Lantai 1',  'penanggung_jawab_id' => 2],
            ['nama_ruangan' => 'Lapangan',         'penanggung_jawab_id' => 2],
            ['nama_ruangan' => 'Perpustakaan',     'penanggung_jawab_id' => 2],
        ]);
        } // end skip base data

        // 5. Data dummy pengaduan (skip if already exists) - berbagai status
        $ruangan1 = Ruangan::where('nama_ruangan', 'Lab IT')->first();
        $ruangan2 = Ruangan::where('nama_ruangan', 'Ruang Kelas A')->first();
        $siswa1   = User::where('nis', '2024001')->first();
        $siswa2   = User::where('nis', '2024002')->first();
        $pj       = User::where('username', 'budi.pj')->first();
        $kat1     = Kategori::where('ket_kategori', 'Kebersihan')->first();
        $kat2     = Kategori::where('ket_kategori', 'Peralatan Kelas')->first();
        $kat3     = Kategori::where('ket_kategori', 'Laboratorium')->first();

        // 6. Dummy users (PJ baru + siswa baru + ruangan baru)
        if (User::where('username', 'siti.pj')->doesntExist()) {
            User::create(['name' => 'Siti Aminah',       'username' => 'siti.pj', 'password' => bcrypt('pj123'),    'role' => 'penanggung_jawab']);
            User::create(['name' => 'Joko Widodo',       'username' => 'joko.pj', 'password' => bcrypt('pj123'),    'role' => 'penanggung_jawab']);

            $kelas10 = Kelas::where('nama_kelas', 'X IPA 1')->first();
            $kelas11 = Kelas::where('nama_kelas', 'XI IPS 1')->first();
            User::create(['name' => 'Budi Prasetyo',     'nis' => '2024003', 'kelas_id' => $kelas10->id_kelas, 'password' => bcrypt('siswa123'), 'role' => 'siswa']);
            User::create(['name' => 'Dewi Sartika',      'nis' => '2024004', 'kelas_id' => $kelas11->id_kelas, 'password' => bcrypt('siswa123'), 'role' => 'siswa']);
            User::create(['name' => 'Rizky Firmansyah',  'nis' => '2024005', 'kelas_id' => $kelas10->id_kelas, 'password' => bcrypt('siswa123'), 'role' => 'siswa']);

            $sitiPj = User::where('username', 'siti.pj')->first();
            $jokoPj = User::where('username', 'joko.pj')->first();
            Ruangan::create(['nama_ruangan' => 'Toilet Lantai 2', 'penanggung_jawab_id' => $sitiPj->id]);
            Ruangan::create(['nama_ruangan' => 'Aula',            'penanggung_jawab_id' => $jokoPj->id]);
            Ruangan::create(['nama_ruangan' => 'Kantin',          'penanggung_jawab_id' => $sitiPj->id]);
        }

        $dummyExists = InputAspirasi::where('lokasi', 'Sudut ruangan')->exists();
        if (!$dummyExists) :
        $data = [
            ['siswa'=>$siswa1,'kat'=>$kat1,'ruangan'=>$ruangan2,'lokasi'=>'Sudut ruangan',      'ket'=>'Lantai kotor dan berdebu',       'status'=>'Menunggu'],
            ['siswa'=>$siswa2,'kat'=>$kat2,'ruangan'=>$ruangan2,'lokasi'=>'Meja baris 3',        'ket'=>'Kursi patah tidak bisa dipakai', 'status'=>'Proses'],
            ['siswa'=>$siswa1,'kat'=>$kat3,'ruangan'=>$ruangan1,'lokasi'=>'Meja komputer no 5',  'ket'=>'Monitor mati total',             'status'=>'Tidak_Mampu'],
            ['siswa'=>$siswa2,'kat'=>$kat3,'ruangan'=>$ruangan1,'lokasi'=>'Server rack',          'ket'=>'Koneksi internet lambat',        'status'=>'Sedang_Dikerjakan'],
            ['siswa'=>$siswa1,'kat'=>$kat1,'ruangan'=>$ruangan2,'lokasi'=>'Seluruh ruangan',     'ket'=>'AC tidak dingin',                'status'=>'Selesai'],
            ['siswa'=>$siswa2,'kat'=>$kat2,'ruangan'=>$ruangan1,'lokasi'=>'Papan tulis',          'ket'=>'Spidol habis semua',             'status'=>'Ditolak'],
        ];

        foreach ($data as $d) {
            $input = InputAspirasi::create([
                'user_id'     => $d['siswa']->id,
                'id_kategori' => $d['kat']->id_kategori,
                'ruangan_id'  => $d['ruangan']->id_ruangan,
                'lokasi'      => $d['lokasi'],
                'ket'         => $d['ket'],
            ]);
            Aspirasi::create([
                'id_pelaporan' => $input->id_pelaporan,
                'id_kategori'  => $d['kat']->id_kategori,
                'status'       => $d['status'],
                'assigned_to'  => $pj->id,
                'feedback'     => $d['status'] === 'Selesai'     ? 'Sudah diperbaiki dan berfungsi normal' : null,
                'alasan_tolak' => $d['status'] === 'Ditolak'     ? 'Bukan tanggung jawab PJ ruangan ini'
                               : ($d['status'] === 'Tidak_Mampu' ? 'Butuh teknisi khusus dari luar'        : null),
            ]);
        }
        endif;
    }
}
