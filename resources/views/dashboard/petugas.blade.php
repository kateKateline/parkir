@extends('layouts.app')

@section('title', 'Dashboard Petugas')

@section('content')
<div class="nav">
    <a href="{{ route('petugas.dashboard') }}">Dashboard</a>
    <a href="{{ route('parkir.masuk') }}">Parkir Masuk</a>
    <a href="{{ route('parkir.keluar') }}">Parkir Keluar</a>
    <a href="{{ route('parkir.riwayat') }}">Riwayat</a>
</div>

<h2>Dashboard Petugas</h2>

<h3 style="margin-top: 20px;">Parkir Aktif ({{ $parkirAktif->count() }})</h3>

@if($parkirAktif->count() > 0)
<table>
    <thead>
        <tr>
            <th>Plat Nomor</th>
            <th>Jenis</th>
            <th>Warna</th>
            <th>Area</th>
            <th>Waktu Masuk</th>
            <th>Durasi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($parkirAktif as $transaksi)
        <tr>
            <td>{{ $transaksi->kendaraan->plat_nomor }}</td>
            <td>{{ ucfirst($transaksi->kendaraan->jenis_kendaraan) }}</td>
            <td>{{ $transaksi->kendaraan->warna }}</td>
            <td>{{ $transaksi->areaParkir->nama_area }}</td>
            <td>{{ $transaksi->waktu_masuk->format('d/m/Y H:i') }}</td>
            <td>{{ now()->diffForHumans($transaksi->waktu_masuk, true) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p>Tidak ada kendaraan yang sedang parkir.</p>
@endif
@endsection
