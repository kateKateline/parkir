<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAktifitas extends Model
{
    use HasFactory;

    protected $table = 'tb_log_aktivitas';
    protected $primaryKey = 'id_log';

    protected $fillable = [
        'id_user',
        'aktivitas',
        'waktu_aktivitas',
    ];

    protected function casts(): array
    {
        return [
            'waktu_aktivitas' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
