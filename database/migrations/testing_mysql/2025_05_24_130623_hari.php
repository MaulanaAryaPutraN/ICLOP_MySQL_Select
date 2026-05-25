<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hari', function (Blueprint $table) {
            $table->string('kode_hari', 3)->primary();
            $table->string('nama_hari', 10);
        });
    }

    public function down()
    {
        Schema::dropIfExists('hari');
    }
};
