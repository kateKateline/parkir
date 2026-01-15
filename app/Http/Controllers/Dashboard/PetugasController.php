<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class PetugasController extends Controller
{
    public function dashboard()
    {
        $parkirAktif = Transaksi::where('status', 'parkir')
            ->with(['kendaraan', 'areaParkir'])
            ->latest('waktu_masuk')
            ->get();

        return view('dashboard.petugas', compact('parkirAktif'));
    }
}
