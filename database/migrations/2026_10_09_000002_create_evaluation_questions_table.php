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
        Schema::create('evaluation_questions', function (Blueprint $table) {
            $table->id();
            $table->string('category')->nullable()->index(); // e.g. 'Kedisiplinan', 'Kompetensi Teknis', 'Kerjasama'
            $table->text('question');
            $table->text('description')->nullable();
            $table->enum('type', ['rating', 'select', 'radio', 'text', 'number', 'textarea'])->default('rating');
            $table->json('options')->nullable(); // For select/radio options or rating scale configuration
            $table->integer('min_score')->default(1);
            $table->integer('max_score')->default(5);
            $table->integer('order')->default(0)->index();
            $table->boolean('is_required')->default(true);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_questions');
    }
};
