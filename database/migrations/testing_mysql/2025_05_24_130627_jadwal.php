<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jadwal', function (Blueprint $table) {
            $table->increments('kode_jadwal');
            $table->string('kode_kelas', 10);
            $table->string('kode_dosen', 4);
            $table->string('kode_mk', 5);
            $table->string('kode_ruang', 5);
            $table->string('kode_hari', 3);
            $table->integer('jp_mulai');
            $table->integer('jp_selesai');

            // Foreign Keys
            $table->foreign('kode_kelas')->references('kode_kelas')->on('kelas');
            $table->foreign('kode_dosen')->references('kode_dosen')->on('dosen');
            $table->foreign('kode_mk')->references('kode_mk')->on('mk');
            $table->foreign('kode_ruang')->references('kode_ruang')->on('ruang');
            $table->foreign('kode_hari')->references('kode_hari')->on('hari');
            $table->foreign('jp_mulai')->references('kode_jp')->on('jp');
            $table->foreign('jp_selesai')->references('kode_jp')->on('jp');
        });
    }

    public function down()
    {
        Schema::dropIfExists('jadwal');
    }
};
