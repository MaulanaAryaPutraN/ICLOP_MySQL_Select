<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jp', function (Blueprint $table) {
            $table->integer('kode_jp')->primary();
            $table->time('jp_mulai');
            $table->time('jp_selesai');
        });
    }

    public function down()
    {
        Schema::dropIfExists('jp');
    }
};
