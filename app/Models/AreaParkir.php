<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaParkir extends Model
{
    use HasFactory;

    protected $table = 'tb_area_parkir';
    protected $primaryKey = 'id_area';

    protected $fillable = [
        'nama_area',
        'kapasitas',
        'terisi',
    ];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_area');
    }
}
