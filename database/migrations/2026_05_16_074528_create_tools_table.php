<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->index();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('short_description');
            $table->longText('body')->nullable();
            $table->string('icon')->nullable();
            $table->string('tool_type');
            $table->string('meta_title');
            $table->text('meta_description');
            $table->string('og_image')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_published')->default(false)->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();

            $table->foreign('category_id', 'tools_category_id_foreign')
                ->references('id')
                ->on('categories')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tools');
    }
};
