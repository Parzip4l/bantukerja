<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('generator_download_logs', function (Blueprint $table): void {
            $table->id();
            $table->string('generator_type')->index();
            $table->string('template_slug')->nullable()->index();
            $table->enum('action', ['preview', 'download_pdf', 'print', 'copy']);
            $table->string('ip_hash', 64)->nullable();
            $table->string('user_agent_hash', 64)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('generator_download_logs');
    }
};
