<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $db = DB::getDatabaseName();

        Schema::connection('nodejsDB')->create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->json('tech_stack')->comment('keys: framework, language, database, testing');
            $table->string('github_url');
            $table->string('image');
            $table->timestamps();
        });

        Schema::connection('nodejsDB')->create('projects_default_file_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->json('structure');
            $table->json('excluded');
            $table->json('replacements');
            $table->timestamps();
        });

        Schema::connection('nodejsDB')->create('submissions', function (Blueprint $table) use ($db) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on(new Expression($db . '.users'))->cascadeOnDelete();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['file', 'url']);
            $table->string('path')->comment('The path to the file or the url');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed']);
            $table->json('results')->nullable()->comment('The results of the submission');
            $table->integer('attempts')->default(1)->comment('The number of attempts to process the submission');
            $table->timestamp('start')->nullable();
            $table->timestamp('end')->nullable();
            $table->integer('port')->nullable()->comment('The port number of the submission');
            $table->timestamps();
        });

        Schema::connection('nodejsDB')->create('temporary_files', function (Blueprint $table) {
            $table->id();
            $table->string('folder_path');
            $table->string('file_name');
            $table->timestamps();
        });

        Schema::connection('nodejsDB')->create('execution_steps', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('commands');
            $table->timestamps();
        });

        Schema::connection('nodejsDB')->create('project_execution_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('execution_step_id')->constrained()->cascadeOnDelete();
            $table->json('variables')->nullable();
            $table->integer('order');
            $table->timestamps();
        });

        Schema::connection('nodejsDB')->create('submission_histories', function (Blueprint $table) use ($db) {
            $table->id();
            $table->foreignId('submission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->references('id')->on(new Expression($db . '.users'))->cascadeOnDelete();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['file', 'url']);
            $table->string('path')->comment('The path to the file or the url');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed']);
            $table->json('results')->nullable()->comment('The results of the submission');
            $table->integer('attempts');
            $table->timestamp('start')->nullable();
            $table->timestamp('end')->nullable();
            $table->integer('port')->nullable()->comment('The port number of the submission');
            $table->text('description')->nullable()->comment('The description of the submission');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('nodejsDB')->dropIfExists('submission_histories');

        Schema::connection('nodejsDB')->dropIfExists('project_execution_steps');

        Schema::connection('nodejsDB')->dropIfExists('execution_steps');

        Schema::connection('nodejsDB')->dropIfExists('temporary_files');

        Schema::connection('nodejsDB')->dropIfExists('submissions');

        Schema::connection('nodejsDB')->dropIfExists('projects_default_file_structures');

        Schema::connection('nodejsDB')->dropIfExists('projects');
    }
};
