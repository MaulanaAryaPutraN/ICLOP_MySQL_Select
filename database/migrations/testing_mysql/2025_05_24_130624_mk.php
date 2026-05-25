<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mk', function (Blueprint $table) {
            $table->string('kode_mk', 5)->primary();
            $table->string('nama_mk', 100);
        });
    }

    public function down()
    {
        Schema::dropIfExists('mk');
    }
};
