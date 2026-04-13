<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\Kategori;
use App\Models\Ruangan;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
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
    }
}
