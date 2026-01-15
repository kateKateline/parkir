<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'tb_transaksi';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'id_kendaraan',
        'id_user',
        'id_tarif',
        'id_area',
        'waktu_masuk',
        'waktu_keluar',
        'durasi_jam',
        'biaya_total',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'waktu_masuk' => 'datetime',
            'waktu_keluar' => 'datetime',
            'tarif_per_jam' => 'decimal:0',
            'biaya_total' => 'decimal:0',
        ];
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'id_kendaraan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function tarif()
    {
        return $this->belongsTo(Tarif::class, 'id_tarif');
    }

    public function areaParkir()
    {
        return $this->belongsTo(AreaParkir::class, 'id_area');
    }
}
