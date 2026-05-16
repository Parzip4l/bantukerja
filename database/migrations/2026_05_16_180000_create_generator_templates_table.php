<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('generator_templates', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('generator_type')->index();
            $table->text('description')->nullable();
            $table->string('preview_image')->nullable();
            $table->string('view_path');
            $table->string('paper_size')->default('a4');
            $table->string('orientation')->default('portrait');
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('is_premium')->default(false);
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->json('settings')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('generator_templates');
    }
};
