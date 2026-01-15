<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AreaParkir;
use App\Models\Tarif;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalPetugas = User::where('role', 'petugas')->count();
        $totalArea = AreaParkir::count();
        $totalTransaksi = Transaksi::count();
        $transaksiHariIni = Transaksi::whereDate('created_at', today())->count();

        return view('dashboard.admin', compact('totalPetugas', 'totalArea', 'totalTransaksi', 'transaksiHariIni'));
    }
}
