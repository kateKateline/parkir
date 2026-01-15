<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AreaParkir;
use App\Models\Tarif;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'nama_lengkap' => 'Administrator',
            'username' => 'admin',
            'password' => Hash::make('123'),
            'role' => 'admin',
            'status_aktif' => true,
        ]);

        // Create Sample Petugas
        User::create([
            'nama_lengkap' => 'Petugas 1',
            'username' => 'petugas',
            'password' => Hash::make('123'),
            'role' => 'petugas',
            'status_aktif' => true,
        ]);
        // Create Sample Owner
        User::create([
            'nama_lengkap' => 'welt Yanfa',
            'username' => 'owner',
            'password' => Hash::make('123'),
            'role' => 'owner',
            'status_aktif' => true,
        ]);

        // Create Sample Area Parkir
        AreaParkir::create([
            'nama_area' => 'Area A',
            'kapasitas' => 50,
            'terisi' => 0,
        ]);

        AreaParkir::create([
            'nama_area' => 'Area B',
            'kapasitas' => 30,
            'terisi' => 0,
        ]);

        // Create Tarif
        Tarif::create([
            'jenis_kendaraan' => 'motor',
            'tarif_per_jam' => 2000,
        ]);

        Tarif::create([
            'jenis_kendaraan' => 'mobil',
            'tarif_per_jam' => 5000,
        ]);

        Tarif::create([
            'jenis_kendaraan' => 'lainnya',
            'tarif_per_jam' => 3000,
        ]);
    }
}
