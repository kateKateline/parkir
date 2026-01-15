<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Transaksi;
use App\Models\AreaParkir;
use App\Models\Tarif;
use App\Models\LogAktifitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ParkirController extends Controller
{
    public function formMasuk()
    {
        $areas = AreaParkir::whereRaw('terisi < kapasitas')->get();
        return view('parkir.masuk', compact('areas'));
    }

    public function masuk(Request $request)
    {
        $request->validate([
            'plat_nomor' => 'required|string|max:15',
            'jenis_kendaraan' => 'required|in:motor,mobil,lainnya',
            'warna' => 'required|string|max:20',
            'pemilik' => 'nullable|string|max:100',
            'id_area' => 'required|exists:tb_area_parkir,id_area',
        ]);

        // Cek apakah area masih ada kapasitas
        $area = AreaParkir::findOrFail($request->id_area);
        if ($area->terisi >= $area->kapasitas) {
            return back()->withErrors(['id_area' => 'Area parkir penuh'])->withInput();
        }

        // Cari atau buat kendaraan
        $kendaraan = Kendaraan::firstOrCreate(
            ['plat_nomor' => strtoupper($request->plat_nomor)],
            [
                'jenis_kendaraan' => $request->jenis_kendaraan,
                'warna' => $request->warna,
                'pemilik' => $request->pemilik,
            ]
        );

        // Cek apakah kendaraan masih parkir
        $cekParkir = Transaksi::where('id_kendaraan', $kendaraan->id_kendaraan)
            ->where('status', 'parkir')
            ->first();

        if ($cekParkir) {
            return back()->withErrors(['plat_nomor' => 'Kendaraan ini masih parkir'])->withInput();
        }

        // Ambil tarif sesuai jenis kendaraan
        $tarif = Tarif::where('jenis_kendaraan', $request->jenis_kendaraan)->first();
        if (!$tarif) {
            return back()->withErrors(['jenis_kendaraan' => 'Tarif untuk jenis kendaraan ini belum ditentukan'])->withInput();
        }

        // Buat transaksi
        $transaksi = Transaksi::create([
            'id_kendaraan' => $kendaraan->id_kendaraan,
            'id_user' => Auth::id(),
            'id_tarif' => $tarif->id_tarif,
            'id_area' => $request->id_area,
            'waktu_masuk' => now(),
            'status' => 'parkir',
        ]);

        // Update terisi area parkir
        $area->increment('terisi');

        // Log aktivitas
        LogAktifitas::create([
            'id_user' => Auth::id(),
            'aktivitas' => "Kendaraan masuk: {$kendaraan->plat_nomor}",
            'waktu_aktivitas' => now(),
        ]);

        return redirect()->route('parkir.print', $transaksi->id_transaksi);
    }

    public function printKarcis($id)
    {
        $transaksi = Transaksi::with(['kendaraan', 'areaParkir', 'tarif', 'user'])
            ->findOrFail($id);

        return view('parkir.print', compact('transaksi'));
    }

    public function formKeluar()
    {
        return view('parkir.keluar');
    }

    public function scanBarcode(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
        ]);

        $idTransaksi = $request->barcode;
        $transaksi = Transaksi::with(['kendaraan', 'areaParkir', 'tarif'])
            ->where('id_transaksi', $idTransaksi)
            ->where('status', 'parkir')
            ->first();

        if (!$transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Karcis tidak valid atau sudah digunakan'
            ], 404);
        }

        $durasiJam = now()->diffInHours($transaksi->waktu_masuk) + 1;
        $biaya = $durasiJam * $transaksi->tarif->tarif_per_jam;

        return response()->json([
            'success' => true,
            'transaksi' => [
                'id_transaksi' => $transaksi->id_transaksi,
                'kendaraan' => [
                    'plat_nomor' => $transaksi->kendaraan->plat_nomor,
                    'jenis_kendaraan' => $transaksi->kendaraan->jenis_kendaraan,
                    'warna' => $transaksi->kendaraan->warna,
                ],
                'area_parkir' => [
                    'nama_area' => $transaksi->areaParkir->nama_area,
                ],
                'waktu_masuk' => $transaksi->waktu_masuk->toISOString(),
            ],
            'durasi_jam' => $durasiJam,
            'biaya' => $biaya
        ]);
    }

    public function keluarManual(Request $request)
    {
        $request->validate([
            'plat_nomor' => 'required|string',
        ]);

        $kendaraan = Kendaraan::where('plat_nomor', strtoupper($request->plat_nomor))->first();

        if (!$kendaraan) {
            return back()->withErrors(['plat_nomor' => 'Kendaraan tidak ditemukan'])->withInput();
        }

        $transaksi = Transaksi::with(['kendaraan', 'areaParkir', 'tarif'])
            ->where('id_kendaraan', $kendaraan->id_kendaraan)
            ->where('status', 'parkir')
            ->latest('waktu_masuk')
            ->first();

        if (!$transaksi) {
            return back()->withErrors(['plat_nomor' => 'Kendaraan tidak sedang parkir'])->withInput();
        }

        return view('parkir.keluar', compact('transaksi'));
    }

    public function prosesKeluar(Request $request)
    {
        $request->validate([
            'id_transaksi' => 'required|exists:tb_transaksi,id_transaksi',
        ]);

        $transaksi = Transaksi::with(['kendaraan', 'areaParkir', 'tarif'])->findOrFail($request->id_transaksi);

        if ($transaksi->status !== 'parkir') {
            return back()->withErrors(['error' => 'Transaksi sudah diproses'])->withInput();
        }

        $waktuKeluar = now();
        $durasiJam = $waktuKeluar->diffInHours($transaksi->waktu_masuk) + 1;
        $biayaTotal = $durasiJam * $transaksi->tarif->tarif_per_jam;

        $transaksi->update([
            'waktu_keluar' => $waktuKeluar,
            'durasi_jam' => $durasiJam,
            'biaya_total' => $biayaTotal,
            'status' => 'keluar',
        ]);

        // Update terisi area parkir
        $transaksi->areaParkir->decrement('terisi');

        // Log aktivitas
        LogAktifitas::create([
            'id_user' => Auth::id(),
            'aktivitas' => "Kendaraan keluar: {$transaksi->kendaraan->plat_nomor}, Bayar: Rp " . number_format($biayaTotal, 0, ',', '.'),
            'waktu_aktivitas' => now(),
        ]);

        return redirect()->route('parkir.riwayat')->with('success', 'Transaksi selesai. Total bayar: Rp ' . number_format($biayaTotal, 0, ',', '.'));
    }

    private function hitungBiaya($transaksi)
    {
        $durasiJam = now()->diffInHours($transaksi->waktu_masuk) + 1;
        return $durasiJam * $transaksi->tarif->tarif_per_jam;
    }

    public function riwayat()
    {
        $transaksi = Transaksi::with(['kendaraan', 'areaParkir', 'tarif', 'user'])
            ->latest('waktu_masuk')
            ->paginate(20);

        return view('parkir.riwayat', compact('transaksi'));
    }
}
