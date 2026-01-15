@extends('layouts.app')

@section('title', 'Parkir Masuk')

@section('content')
<div class="nav">
    <a href="{{ route('petugas.dashboard') }}">Dashboard</a>
    <a href="{{ route('parkir.masuk') }}">Parkir Masuk</a>
    <a href="{{ route('parkir.keluar') }}">Parkir Keluar</a>
    <a href="{{ route('parkir.riwayat') }}">Riwayat</a>
</div>

<h2>Form Parkir Masuk</h2>

<form method="POST" action="{{ route('parkir.masuk') }}">
    @csrf
    <div class="form-group">
        <label>Plat Nomor *</label>
        <input type="text" name="plat_nomor" value="{{ old('plat_nomor') }}" required placeholder="Contoh: B1234XYZ" style="text-transform: uppercase;">
    </div>

    <div class="form-group">
        <label>Jenis Kendaraan *</label>
        <select name="jenis_kendaraan" required>
            <option value="">Pilih Jenis</option>
            <option value="motor" {{ old('jenis_kendaraan') == 'motor' ? 'selected' : '' }}>Motor</option>
            <option value="mobil" {{ old('jenis_kendaraan') == 'mobil' ? 'selected' : '' }}>Mobil</option>
            <option value="lainnya" {{ old('jenis_kendaraan') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
        </select>
    </div>

    <div class="form-group">
        <label>Warna *</label>
        <input type="text" name="warna" value="{{ old('warna') }}" required placeholder="Contoh: Hitam, Merah, Putih">
    </div>

    <div class="form-group">
        <label>Pemilik (Opsional)</label>
        <input type="text" name="pemilik" value="{{ old('pemilik') }}" placeholder="Nama pemilik kendaraan">
    </div>

    <div class="form-group">
        <label>Area Parkir *</label>
        <select name="id_area" required>
            <option value="">Pilih Area</option>
            @foreach($areas as $area)
                <option value="{{ $area->id_area }}" {{ old('id_area') == $area->id_area ? 'selected' : '' }}>
                    {{ $area->nama_area }} ({{ $area->terisi }}/{{ $area->kapasitas }})
                </option>
            @endforeach
        </select>
    </div>

    <button type="submit">Simpan & Print Karcis</button>
    <a href="{{ route('petugas.dashboard') }}" class="btn" style="background: #95a5a6;">Batal</a>
</form>
@endsection
