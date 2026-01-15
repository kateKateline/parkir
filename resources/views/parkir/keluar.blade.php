@extends('layouts.app')

@section('title', 'Parkir Keluar')

@section('content')
<div class="nav">
    <a href="{{ route('petugas.dashboard') }}">Dashboard</a>
    <a href="{{ route('parkir.masuk') }}">Parkir Masuk</a>
    <a href="{{ route('parkir.keluar') }}">Parkir Keluar</a>
    <a href="{{ route('parkir.riwayat') }}">Riwayat</a>
</div>

<h2>Parkir Keluar</h2>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
    <div class="content">
        <h3>Scan Barcode Karcis</h3>
        <form id="scan-form">
            <div class="form-group">
                <label>Barcode/Kode Karcis</label>
                <input type="text" id="barcode-input" placeholder="Scan atau input kode karcis" autofocus>
            </div>
            <button type="button" onclick="scanBarcode()">Scan Barcode</button>
        </form>
    </div>

    <div class="content">
        <h3>Atau Input Manual (Jika Karcis Hilang)</h3>
        <form method="POST" action="{{ route('parkir.keluar.manual') }}">
            @csrf
            <div class="form-group">
                <label>Plat Nomor</label>
                <input type="text" name="plat_nomor" placeholder="Contoh: B1234XYZ" required style="text-transform: uppercase;">
            </div>
            <button type="submit">Cari Kendaraan</button>
        </form>
    </div>
</div>

@if(isset($transaksi))
<div id="transaksi-info" class="content" style="background: #e8f5e9;">
    <h3>Detail Transaksi</h3>
    <div class="info">
        <p><strong>Plat Nomor:</strong> {{ $transaksi->kendaraan->plat_nomor }}</p>
        <p><strong>Jenis:</strong> {{ ucfirst($transaksi->kendaraan->jenis_kendaraan) }}</p>
        <p><strong>Warna:</strong> {{ $transaksi->kendaraan->warna }}</p>
        <p><strong>Area:</strong> {{ $transaksi->areaParkir->nama_area }}</p>
        <p><strong>Waktu Masuk:</strong> {{ $transaksi->waktu_masuk->format('d/m/Y H:i:s') }}</p>
        <p><strong>Waktu Keluar:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
        @php
            $durasiJam = now()->diffInHours($transaksi->waktu_masuk) + 1;
            $biaya = $durasiJam * $transaksi->tarif->tarif_per_jam;
        @endphp
        <p><strong>Durasi:</strong> {{ $durasiJam }} jam</p>
        <p><strong style="font-size: 20px;">Total Bayar: Rp {{ number_format($biaya, 0, ',', '.') }}</strong></p>
    </div>
    <form method="POST" action="{{ route('parkir.keluar.proses') }}" style="margin-top: 20px;">
        @csrf
        <input type="hidden" name="id_transaksi" value="{{ $transaksi->id_transaksi }}">
        <button type="submit" class="btn-success" style="width: 100%;">Konfirmasi Pembayaran</button>
    </form>
</div>
@endif

<div id="result" style="display: none;"></div>

<script>
function scanBarcode() {
    const barcode = document.getElementById('barcode-input').value;
    if (!barcode) {
        alert('Masukkan kode barcode');
        return;
    }

    fetch('{{ route("parkir.keluar.scan") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ barcode: barcode })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const transaksi = data.transaksi;
            const durasi = data.durasi_jam;
            const biaya = data.biaya;
            
            const html = `
                <div class="content" style="background: #e8f5e9; margin-top: 20px;">
                    <h3>Detail Transaksi</h3>
                    <p><strong>Plat Nomor:</strong> ${transaksi.kendaraan.plat_nomor}</p>
                    <p><strong>Jenis:</strong> ${transaksi.kendaraan.jenis_kendaraan.charAt(0).toUpperCase() + transaksi.kendaraan.jenis_kendaraan.slice(1)}</p>
                    <p><strong>Warna:</strong> ${transaksi.kendaraan.warna}</p>
                    <p><strong>Area:</strong> ${transaksi.area_parkir.nama_area}</p>
                    <p><strong>Waktu Masuk:</strong> ${new Date(transaksi.waktu_masuk).toLocaleString('id-ID')}</p>
                    <p><strong>Durasi:</strong> ${durasi} jam</p>
                    <p><strong style="font-size: 20px;">Total Bayar: Rp ${biaya.toLocaleString('id-ID')}</strong></p>
                    <form method="POST" action="{{ route('parkir.keluar.proses') }}" style="margin-top: 20px;">
                        @csrf
                        <input type="hidden" name="id_transaksi" value="${transaksi.id_transaksi}">
                        <button type="submit" class="btn-success" style="width: 100%;">Konfirmasi Pembayaran</button>
                    </form>
                </div>
            `;
            document.getElementById('result').innerHTML = html;
            document.getElementById('result').style.display = 'block';
            document.getElementById('barcode-input').value = '';
        } else {
            alert(data.message || 'Karcis tidak valid');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
    });
}

document.getElementById('barcode-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        scanBarcode();
    }
});
</script>
@endsection
