<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with(['kendaraan', 'areaParkir', 'tarif', 'user'])
            ->where('status', 'keluar');

        if ($request->tanggal_dari) {
            $query->whereDate('waktu_keluar', '>=', $request->tanggal_dari);
        }

        if ($request->tanggal_sampai) {
            $query->whereDate('waktu_keluar', '<=', $request->tanggal_sampai);
        }

        $transaksi = $query->latest('waktu_keluar')->paginate(50);

        $totalPendapatan = $transaksi->sum('biaya_total');
        $totalTransaksi = $transaksi->total();

        return view('laporan.index', compact('transaksi', 'totalPendapatan', 'totalTransaksi'));
    }
}
