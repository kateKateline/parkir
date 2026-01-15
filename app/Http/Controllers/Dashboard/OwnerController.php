<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    public function dashboard()
    {
        $pendapatanHariIni = Transaksi::whereDate('waktu_keluar', today())
            ->sum('biaya_total') ?? 0;

        $pendapatanBulanIni = Transaksi::whereMonth('waktu_keluar', now()->month)
            ->whereYear('waktu_keluar', now()->year)
            ->sum('biaya_total') ?? 0;

        $totalTransaksi = Transaksi::where('status', 'keluar')->count();

        return view('dashboard.owner', compact('pendapatanHariIni', 'pendapatanBulanIni', 'totalTransaksi'));
    }
}
