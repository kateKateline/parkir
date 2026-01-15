<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LogAktifitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PetugasController extends Controller
{
    public function index()
    {
        $petugas = User::where('role', 'petugas')->latest()->get();
        return view('petugas.index', compact('petugas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:50',
            'username' => 'required|string|max:50|unique:tb_user,username',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'petugas',
            'status_aktif' => true,
        ]);

        LogAktifitas::create([
            'id_user' => Auth::id(),
            'aktivitas' => "Menambah petugas: {$request->nama_lengkap}",
            'waktu_aktivitas' => now(),
        ]);

        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:50',
            'username' => 'required|string|max:50|unique:tb_user,username,' . $id . ',id_user',
            'password' => 'nullable|string|min:6',
            'status_aktif' => 'required|boolean',
        ]);

        $petugas = User::findOrFail($id);
        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'username' => $request->username,
            'status_aktif' => $request->status_aktif,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $petugas->update($data);

        LogAktifitas::create([
            'id_user' => Auth::id(),
            'aktivitas' => "Mengupdate petugas: {$request->nama_lengkap}",
            'waktu_aktivitas' => now(),
        ]);

        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil diupdate');
    }

    public function destroy($id)
    {
        $petugas = User::findOrFail($id);
        $namaPetugas = $petugas->nama_lengkap;
        $petugas->delete();

        LogAktifitas::create([
            'id_user' => Auth::id(),
            'aktivitas' => "Menghapus petugas: {$namaPetugas}",
            'waktu_aktivitas' => now(),
        ]);

        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil dihapus');
    }
}
