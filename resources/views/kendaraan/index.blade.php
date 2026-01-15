@extends('layouts.app')

@section('title', 'Data Kendaraan')

@section('content')
<div class="nav">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="{{ route('admin.petugas.index') }}">Petugas</a>
    <a href="{{ route('admin.area.index') }}">Area Parkir</a>
    <a href="{{ route('admin.tarif.index') }}">Tarif</a>
    <a href="{{ route('admin.kendaraan.index') }}">Kendaraan</a>
    <a href="{{ route('laporan.index') }}">Laporan</a>
</div>

<h2>Data Kendaraan</h2>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Plat Nomor</th>
            <th>Jenis</th>
            <th>Warna</th>
            <th>Pemilik</th>
            <th>Tanggal Terdaftar</th>
        </tr>
    </thead>
    <tbody>
        @foreach($kendaraan as $index => $k)
        <tr>
            <td>{{ $kendaraan->firstItem() + $index }}</td>
            <td>{{ $k->plat_nomor }}</td>
            <td>{{ ucfirst($k->jenis_kendaraan) }}</td>
            <td>{{ $k->warna }}</td>
            <td>{{ $k->pemilik ?? '-' }}</td>
            <td>{{ $k->created_at->format('d/m/Y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div style="margin-top: 20px;">
    {{ $kendaraan->links() }}
</div>
@endsection
