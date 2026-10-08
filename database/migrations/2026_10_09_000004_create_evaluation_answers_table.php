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
        Schema::create('evaluation_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('evaluation_id');
            $table->unsignedBigInteger('question_id');
            $table->text('answer')->nullable();
            $table->decimal('score', 4, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['evaluation_id', 'question_id'], 'eval_question_unique');
            $table->foreign('evaluation_id', 'fk_eval_answers_evaluation')->references('id')->on('evaluations')->cascadeOnDelete();
            $table->foreign('question_id', 'fk_eval_answers_question')->references('id')->on('evaluation_questions')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_answers');
    }
};
