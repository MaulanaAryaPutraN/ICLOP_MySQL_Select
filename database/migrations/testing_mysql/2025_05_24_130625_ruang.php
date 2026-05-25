<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ruang', function (Blueprint $table) {
            $table->string('kode_ruang', 5)->primary();
            $table->string('nama_ruang', 20);
            $table->string('deskripsi_ruang', 100);
        });
    }

    public function down()
    {
        Schema::dropIfExists('ruang');
    }
};
