<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('prodi', function (Blueprint $table) {
            $table->string('kode_prodi', 3)->primary();
            $table->string('nama_prodi', 100);
        });
    }

    public function down()
    {
        Schema::dropIfExists('prodi');
    }
};
// command to run this migration in a testing environment:
// php artisan migrate --env=testing --path=database/migrations/testing_mysql/2025_05_24_130620_dosen.php
