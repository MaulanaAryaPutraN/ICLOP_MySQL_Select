<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->string('kode_kelas', 10)->primary();
            $table->string('kode_prodi', 3);
            $table->string('nama_kelas', 5);
            $table->foreign('kode_prodi')->references('kode_prodi')->on('prodi');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kelas');
    }
};
