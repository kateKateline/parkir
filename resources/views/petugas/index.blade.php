@extends('layouts.app')

@section('title', 'Manajemen Petugas')

@section('content')
<div class="nav">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="{{ route('admin.petugas.index') }}">Petugas</a>
    <a href="{{ route('admin.area.index') }}">Area Parkir</a>
    <a href="{{ route('admin.tarif.index') }}">Tarif</a>
    <a href="{{ route('admin.kendaraan.index') }}">Kendaraan</a>
    <a href="{{ route('laporan.index') }}">Laporan</a>
</div>

<h2>Manajemen Petugas</h2>

<div class="content" style="margin-bottom: 20px;">
    <h3>Tambah Petugas Baru</h3>
    <form method="POST" action="{{ route('admin.petugas.store') }}">
        @csrf
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 10px; align-items: end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" required>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Tambah</button>
        </div>
    </form>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Lengkap</th>
            <th>Username</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($petugas as $index => $p)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $p->nama_lengkap }}</td>
            <td>{{ $p->username }}</td>
            <td>
                <span style="padding: 5px 10px; border-radius: 3px; background: {{ $p->status_aktif ? '#27ae60' : '#e74c3c' }}; color: white;">
                    {{ $p->status_aktif ? 'Aktif' : 'Tidak Aktif' }}
                </span>
            </td>
            <td>
                <button onclick="editPetugas({{ $p->id_user }}, '{{ $p->nama_lengkap }}', '{{ $p->username }}', {{ $p->status_aktif ? 'true' : 'false' }})">Edit</button>
                <form method="POST" action="{{ route('admin.petugas.destroy', $p->id_user) }}" style="display: inline;" onsubmit="return confirm('Yakin hapus?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Modal Edit -->
<div id="edit-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 5px; max-width: 500px; width: 90%;">
        <h3>Edit Petugas</h3>
        <form method="POST" id="edit-form">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" id="edit-nama" required>
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" id="edit-username" required>
            </div>
            <div class="form-group">
                <label>Password (Kosongkan jika tidak diubah)</label>
                <input type="password" name="password" id="edit-password">
            </div>
            <div class="form-group">
                <label>Status Aktif</label>
                <select name="status_aktif" id="edit-status" required>
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div>
            <button type="submit">Update</button>
            <button type="button" onclick="closeEditModal()" class="btn" style="background: #95a5a6;">Batal</button>
        </form>
    </div>
</div>

<script>
function editPetugas(id, nama, username, status) {
    document.getElementById('edit-form').action = '/admin/petugas/' + id;
    document.getElementById('edit-nama').value = nama;
    document.getElementById('edit-username').value = username;
    document.getElementById('edit-status').value = status ? '1' : '0';
    document.getElementById('edit-password').value = '';
    document.getElementById('edit-modal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('edit-modal').style.display = 'none';
}
</script>
@endsection
