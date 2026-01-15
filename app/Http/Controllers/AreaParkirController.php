<?php

namespace App\Http\Controllers;

use App\Models\AreaParkir;
use App\Models\LogAktifitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AreaParkirController extends Controller
{
    public function index()
    {
        $areas = AreaParkir::latest()->get();
        return view('area.index', compact('areas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_area' => 'required|string|max:50',
            'kapasitas' => 'required|integer|min:1',
        ]);

        AreaParkir::create([
            'nama_area' => $request->nama_area,
            'kapasitas' => $request->kapasitas,
            'terisi' => 0,
        ]);

        LogAktifitas::create([
            'id_user' => Auth::id(),
            'aktivitas' => "Menambah area parkir: {$request->nama_area}",
            'waktu_aktivitas' => now(),
        ]);

        return redirect()->route('area.index')->with('success', 'Area parkir berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_area' => 'required|string|max:50',
            'kapasitas' => 'required|integer|min:1',
        ]);

        $area = AreaParkir::findOrFail($id);
        $kapasitasLama = $area->kapasitas;

        if ($request->kapasitas < $area->terisi) {
            return back()->withErrors(['kapasitas' => 'Kapasitas tidak boleh kurang dari kendaraan yang sedang parkir'])->withInput();
        }

        $area->update([
            'nama_area' => $request->nama_area,
            'kapasitas' => $request->kapasitas,
        ]);

        LogAktifitas::create([
            'id_user' => Auth::id(),
            'aktivitas' => "Mengupdate area parkir: {$request->nama_area}",
            'waktu_aktivitas' => now(),
        ]);

        return redirect()->route('area.index')->with('success', 'Area parkir berhasil diupdate');
    }

    public function destroy($id)
    {
        $area = AreaParkir::findOrFail($id);

        if ($area->terisi > 0) {
            return back()->withErrors(['error' => 'Area masih digunakan']);
        }

        $namaArea = $area->nama_area;
        $area->delete();

        LogAktifitas::create([
            'id_user' => Auth::id(),
            'aktivitas' => "Menghapus area parkir: {$namaArea}",
            'waktu_aktivitas' => now(),
        ]);

        return redirect()->route('area.index')->with('success', 'Area parkir berhasil dihapus');
    }
}
