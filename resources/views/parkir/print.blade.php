<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karcis Parkir</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 400px; margin: 0 auto; }
        .karcis { border: 2px solid #000; padding: 20px; text-align: center; }
        .karcis h2 { margin-bottom: 15px; }
        .barcode { margin: 20px 0; }
        .info { text-align: left; margin: 15px 0; }
        .info p { margin: 5px 0; }
        .barcode-number { font-size: 18px; font-weight: bold; letter-spacing: 2px; margin: 10px 0; }
        @media print {
            button { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="karcis">
        <h2>KARCIS PARKIR</h2>
        <hr>
        <div class="info">
            <p><strong>Plat Nomor:</strong> {{ $transaksi->kendaraan->plat_nomor }}</p>
            <p><strong>Jenis:</strong> {{ ucfirst($transaksi->kendaraan->jenis_kendaraan) }}</p>
            <p><strong>Warna:</strong> {{ $transaksi->kendaraan->warna }}</p>
            <p><strong>Area:</strong> {{ $transaksi->areaParkir->nama_area }}</p>
            <p><strong>Waktu Masuk:</strong> {{ $transaksi->waktu_masuk->format('d/m/Y H:i:s') }}</p>
        </div>
        <hr>
        <div class="barcode">
            <div class="barcode-number">{{ $transaksi->id_transaksi }}</div>
            <div id="barcode"></div>
            <small>Simpan karcis ini untuk keluar parkir</small>
        </div>
    </div>
    <div style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #3498db; color: white; border: none; cursor: pointer;">Print</button>
        <a href="{{ route('parkir.masuk') }}" style="display: inline-block; padding: 10px 20px; background: #27ae60; color: white; text-decoration: none; margin-left: 10px;">Kendaraan Baru</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script>
        JsBarcode("#barcode", "{{ $transaksi->id_transaksi }}", {
            format: "CODE128",
            width: 2,
            height: 50,
            displayValue: true
        });
    </script>
</body>
</html>
