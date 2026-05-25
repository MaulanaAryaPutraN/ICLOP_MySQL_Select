<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dosen', function (Blueprint $table) {
            $table->string('kode_dosen', 4)->primary();
            $table->string('nama_dosen', 100);
        });
    }

    public function down()
    {
        Schema::dropIfExists('dosen');
    }
};
