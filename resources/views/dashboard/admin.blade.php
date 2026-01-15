@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="nav">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="{{ route('admin.petugas.index') }}">Petugas</a>
    <a href="{{ route('admin.area.index') }}">Area Parkir</a>
    <a href="{{ route('admin.tarif.index') }}">Tarif</a>
    <a href="{{ route('admin.kendaraan.index') }}">Kendaraan</a>
    <a href="{{ route('laporan.index') }}">Laporan</a>
</div>

<h2>Dashboard Admin</h2>

<div class="grid">
    <div class="card">
        <h3>Total Petugas</h3>
        <div class="value">{{ $totalPetugas }}</div>
    </div>
    <div class="card">
        <h3>Total Area Parkir</h3>
        <div class="value">{{ $totalArea }}</div>
    </div>
    <div class="card">
        <h3>Total Transaksi</h3>
        <div class="value">{{ $totalTransaksi }}</div>
    </div>
    <div class="card">
        <h3>Transaksi Hari Ini</h3>
        <div class="value">{{ $transaksiHariIni }}</div>
    </div>
</div>
@endsection
