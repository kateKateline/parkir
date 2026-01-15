<?php

namespace App\Http\Controllers;

use App\Models\Tarif;
use App\Models\LogAktifitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TarifController extends Controller
{
    public function index()
    {
        $tarif = Tarif::latest()->get();
        return view('tarif.index', compact('tarif'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_kendaraan' => 'required|in:motor,mobil,lainnya',
            'tarif_per_jam' => 'required|integer|min:0',
        ]);

        // Cek apakah sudah ada tarif untuk jenis kendaraan ini
        $existing = Tarif::where('jenis_kendaraan', $request->jenis_kendaraan)->first();
        if ($existing) {
            return back()->withErrors(['jenis_kendaraan' => 'Tarif untuk jenis kendaraan ini sudah ada'])->withInput();
        }

        Tarif::create([
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'tarif_per_jam' => $request->tarif_per_jam,
        ]);

        LogAktifitas::create([
            'id_user' => Auth::id(),
            'aktivitas' => "Menambah tarif: {$request->jenis_kendaraan} - Rp {$request->tarif_per_jam}/jam",
            'waktu_aktivitas' => now(),
        ]);

        return redirect()->route('tarif.index')->with('success', 'Tarif berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tarif_per_jam' => 'required|integer|min:0',
        ]);

        $tarif = Tarif::findOrFail($id);
        $tarifLama = $tarif->tarif_per_jam;
        $tarif->update([
            'tarif_per_jam' => $request->tarif_per_jam,
        ]);

        LogAktifitas::create([
            'id_user' => Auth::id(),
            'aktivitas' => "Mengupdate tarif {$tarif->jenis_kendaraan}: Rp {$tarifLama} -> Rp {$request->tarif_per_jam}/jam",
            'waktu_aktivitas' => now(),
        ]);

        return redirect()->route('tarif.index')->with('success', 'Tarif berhasil diupdate');
    }
}
