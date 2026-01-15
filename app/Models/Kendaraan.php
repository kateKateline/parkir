<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    protected $table = 'tb_kendaraan';
    protected $primaryKey = 'id_kendaraan';

    protected $fillable = [
        'plat_nomor',
        'jenis_kendaraan',
        'warna',
        'pemilik',
    ];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_kendaraan');
    }
}
