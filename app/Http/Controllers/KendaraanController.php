<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    public function index()
    {
        $kendaraan = Kendaraan::latest()->paginate(20);
        return view('kendaraan.index', compact('kendaraan'));
    }
}
