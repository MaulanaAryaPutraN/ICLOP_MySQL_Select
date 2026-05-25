<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mysql_queries', function (Blueprint $table) {
            $table->bigIncrements('id'); // BIGINT(20) UNSIGNED AUTO_INCREMENT
            $table->unsignedBigInteger('submission_id'); // Foreign key ke mysql_student_submissions
            $table->integer('question_number')->unsigned(); // Nomor soal (1-10)
            $table->text('query'); // Query yang dimasukkan oleh user
            $table->timestamps();

            $table->foreign('submission_id')->references('ID')->on('mysql_student_submissions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mysql_queries');
    }
};
