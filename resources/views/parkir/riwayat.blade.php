@extends('layouts.app')

@section('title', 'Riwayat Parkir')

@section('content')
<div class="nav">
    <a href="{{ route('petugas.dashboard') }}">Dashboard</a>
    <a href="{{ route('parkir.masuk') }}">Parkir Masuk</a>
    <a href="{{ route('parkir.keluar') }}">Parkir Keluar</a>
    <a href="{{ route('parkir.riwayat') }}">Riwayat</a>
</div>

<h2>Riwayat Transaksi Parkir</h2>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Plat Nomor</th>
            <th>Jenis</th>
            <th>Warna</th>
            <th>Area</th>
            <th>Waktu Masuk</th>
            <th>Waktu Keluar</th>
            <th>Durasi</th>
            <th>Biaya</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transaksi as $index => $trx)
        <tr>
            <td>{{ $transaksi->firstItem() + $index }}</td>
            <td>{{ $trx->kendaraan->plat_nomor }}</td>
            <td>{{ ucfirst($trx->kendaraan->jenis_kendaraan) }}</td>
            <td>{{ $trx->kendaraan->warna }}</td>
            <td>{{ $trx->areaParkir->nama_area }}</td>
            <td>{{ $trx->waktu_masuk->format('d/m/Y H:i') }}</td>
            <td>{{ $trx->waktu_keluar ? $trx->waktu_keluar->format('d/m/Y H:i') : '-' }}</td>
            <td>{{ $trx->durasi_jam ? $trx->durasi_jam . ' jam' : '-' }}</td>
            <td>{{ $trx->biaya_total ? 'Rp ' . number_format($trx->biaya_total, 0, ',', '.') : '-' }}</td>
            <td>
                <span style="padding: 5px 10px; border-radius: 3px; background: {{ $trx->status == 'parkir' ? '#f39c12' : '#27ae60' }}; color: white;">
                    {{ ucfirst($trx->status) }}
                </span>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div style="margin-top: 20px;">
    {{ $transaksi->links() }}
</div>
@endsection
