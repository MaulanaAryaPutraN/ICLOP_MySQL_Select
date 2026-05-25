<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('select_topics', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->integer('countdown_seconds')->default(3600);
            $table->string('schema_file_name')->nullable();
            $table->string('schema_file_path')->nullable();
            $table->boolean('is_sequential')->default(false);
            $table->timestamps();
        });

        Schema::create('select_topic_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained('select_topics')->onDelete('cascade');
            $table->string('title');
            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('total_percobaan')->default(0);
            $table->integer('total_tugas')->default(0);
            $table->timestamps();
        });

        Schema::create('select_expected_queries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_detail_id')->constrained('select_topic_details')->onDelete('cascade');
            $table->enum('type', ['percobaan', 'tugas'])->default('tugas');
            $table->integer('answer_number');
            $table->text('expected_query');
            $table->string('expected_table')->nullable();
            $table->timestamps();

            $table->unique(['topic_detail_id', 'type', 'answer_number'], 'unique_expected_query');
        });

        Schema::create('select_queries', function (Blueprint $table) {
            $table->id();
            $table->text('query');
            $table->timestamps();
        });

        Schema::create('select_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('query_id')->constrained('select_queries')->onDelete('cascade');
            $table->text('feedback')->nullable();
            $table->text('validation_error')->nullable();
            $table->timestamps();
        });

        Schema::create('select_student_topic_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('topic_id')->constrained('select_topics')->onDelete('cascade');
            $table->timestamp('started_at')->nullable();
            $table->integer('duration_seconds')->nullable();

            // Total detik yang sudah dipakai (terakumulasi setiap pause)
            $table->unsignedInteger('elapsed_seconds')->default(0);

            // Sisa detik tepat saat student menekan back/keluar.
            // Diisi oleh pauseTimer dari nilai countdown JS.
            // NULL = belum pernah pause.
            $table->unsignedInteger('remaining_seconds')->nullable();

            // Waktu saat student meninggalkan halaman.
            // NULL = timer sedang berjalan normal.
            $table->timestamp('paused_at')->nullable();

            $table->boolean('is_finished')->default(false);
            $table->timestamps();

            // Tidak unique per user+topic karena student bisa enroll ulang (is_reset)
        });

        Schema::create('select_student_submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enroll_id')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('topic_detail_id')->constrained('select_topic_details')->onDelete('cascade');
            $table->enum('submission_type', ['percobaan', 'tugas']);
            $table->integer('answer_number');
            $table->foreignId('query_id')->nullable()->constrained('select_queries')->onDelete('set null');
            $table->foreignId('feedback_id')->nullable()->constrained('select_feedbacks')->onDelete('set null');
            $table->enum('status', ['true', 'false'])->default('false');
            $table->timestamps();
        });

        Schema::create('select_user_reset', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('topic_id')->constrained('select_topics')->onDelete('cascade');
            $table->boolean('is_reset')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'topic_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('select_user_reset');
        Schema::dropIfExists('select_student_submissions');
        Schema::dropIfExists('select_student_topic_times');
        Schema::dropIfExists('select_feedbacks');
        Schema::dropIfExists('select_queries');
        Schema::dropIfExists('select_expected_queries');
        Schema::dropIfExists('select_topic_details');
        Schema::dropIfExists('select_topics');
    }
};
