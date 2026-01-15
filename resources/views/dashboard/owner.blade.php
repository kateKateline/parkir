@extends('layouts.app')

@section('title', 'Dashboard Owner')

@section('content')
<div class="nav">
    <a href="{{ route('owner.dashboard') }}">Dashboard</a>
    <a href="{{ route('laporan.index') }}">Laporan</a>
</div>

<h2>Dashboard Owner</h2>

<div class="grid">
    <div class="card">
        <h3>Pendapatan Hari Ini</h3>
        <div class="value">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</div>
    </div>
    <div class="card">
        <h3>Pendapatan Bulan Ini</h3>
        <div class="value">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</div>
    </div>
    <div class="card">
        <h3>Total Transaksi</h3>
        <div class="value">{{ $totalTransaksi }}</div>
    </div>
</div>
@endsection
