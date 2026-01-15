@extends('layouts.app')

@section('title', 'Laporan Transaksi')

@section('content')
<div class="nav">
    <a href="{{ route(auth()->user()->role . '.dashboard') }}">Dashboard</a>
    <a href="{{ route('laporan.index') }}">Laporan</a>
</div>

<h2>Laporan Transaksi</h2>

<div class="content" style="margin-bottom: 20px;">
    <form method="GET" action="{{ route('laporan.index') }}">
        <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 10px; align-items: end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label>Tanggal Dari</label>
                <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label>Tanggal Sampai</label>
                <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}">
            </div>
            <button type="submit">Filter</button>
        </div>
    </form>
</div>

<div class="grid" style="margin-bottom: 20px;">
    <div class="card">
        <h3>Total Pendapatan</h3>
        <div class="value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
    </div>
    <div class="card">
        <h3>Total Transaksi</h3>
        <div class="value">{{ $totalTransaksi }}</div>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Plat Nomor</th>
            <th>Jenis</th>
            <th>Area</th>
            <th>Waktu Masuk</th>
            <th>Waktu Keluar</th>
            <th>Durasi</th>
            <th>Biaya</th>
            <th>Petugas</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transaksi as $index => $trx)
        <tr>
            <td>{{ $transaksi->firstItem() + $index }}</td>
            <td>{{ $trx->kendaraan->plat_nomor }}</td>
            <td>{{ ucfirst($trx->kendaraan->jenis_kendaraan) }}</td>
            <td>{{ $trx->areaParkir->nama_area }}</td>
            <td>{{ $trx->waktu_masuk->format('d/m/Y H:i') }}</td>
            <td>{{ $trx->waktu_keluar ? $trx->waktu_keluar->format('d/m/Y H:i') : '-' }}</td>
            <td>{{ $trx->durasi_jam ? $trx->durasi_jam . ' jam' : '-' }}</td>
            <td>{{ $trx->biaya_total ? 'Rp ' . number_format($trx->biaya_total, 0, ',', '.') : '-' }}</td>
            <td>{{ $trx->user->nama_lengkap }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div style="margin-top: 20px;">
    {{ $transaksi->links() }}
</div>
@endsection
