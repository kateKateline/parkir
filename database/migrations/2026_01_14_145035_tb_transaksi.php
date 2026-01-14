<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tb_transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');

            $table->foreignId('id_kendaraan')
                ->constrained('tb_kendaraan', 'id_kendaraan')
                ->cascadeOnDelete();

            $table->foreignId('id_user')
                ->constrained('tb_user', 'id_user')
                ->cascadeOnDelete();

            $table->foreignId('id_tarif')
                ->constrained('tb_tarif', 'id_tarif');

            $table->foreignId('id_area')
                ->constrained('tb_area_parkir', 'id_area');

            $table->dateTime('waktu_masuk');
            $table->dateTime('waktu_keluar')->nullable();

            $table->integer('durasi_jam')->nullable();
            $table->decimal('biaya_total', 10, 0)->nullable();

            $table->enum('status', ['parkir', 'keluar']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_transaksi');
    }
};
