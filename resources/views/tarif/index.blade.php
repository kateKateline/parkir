@extends('layouts.app')

@section('title', 'Manajemen Tarif')

@section('content')
<div class="nav">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="{{ route('admin.petugas.index') }}">Petugas</a>
    <a href="{{ route('admin.area.index') }}">Area Parkir</a>
    <a href="{{ route('admin.tarif.index') }}">Tarif</a>
    <a href="{{ route('admin.kendaraan.index') }}">Kendaraan</a>
    <a href="{{ route('laporan.index') }}">Laporan</a>
</div>

<h2>Manajemen Tarif Parkir</h2>

<div class="content" style="margin-bottom: 20px;">
    <h3>Tambah Tarif</h3>
    <form method="POST" action="{{ route('admin.tarif.store') }}">
        @csrf
        <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 10px; align-items: end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label>Jenis Kendaraan</label>
                <select name="jenis_kendaraan" required>
                    <option value="">Pilih Jenis</option>
                    <option value="motor">Motor</option>
                    <option value="mobil">Mobil</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label>Tarif per Jam (Rp)</label>
                <input type="number" name="tarif_per_jam" min="0" required>
            </div>
            <button type="submit">Tambah</button>
        </div>
    </form>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Jenis Kendaraan</th>
            <th>Tarif per Jam</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tarif as $index => $t)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ ucfirst($t->jenis_kendaraan) }}</td>
            <td>Rp {{ number_format($t->tarif_per_jam, 0, ',', '.') }}</td>
            <td>
                <button onclick="editTarif({{ $t->id_tarif }}, {{ $t->tarif_per_jam }})">Edit</button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Modal Edit -->
<div id="edit-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 5px; max-width: 500px; width: 90%;">
        <h3>Edit Tarif</h3>
        <form method="POST" id="edit-form">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Tarif per Jam (Rp)</label>
                <input type="number" name="tarif_per_jam" id="edit-tarif" min="0" required>
            </div>
            <button type="submit">Update</button>
            <button type="button" onclick="closeEditModal()" class="btn" style="background: #95a5a6;">Batal</button>
        </form>
    </div>
</div>

<script>
function editTarif(id, tarif) {
    document.getElementById('edit-form').action = '/admin/tarif/' + id;
    document.getElementById('edit-tarif').value = tarif;
    document.getElementById('edit-modal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('edit-modal').style.display = 'none';
}
</script>
@endsection
